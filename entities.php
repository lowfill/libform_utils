<?php

define('ELGG_ENTITIES_ANY_VALUE', NULL);
define('ELGG_ENTITIES_NO_VALUE', 0);

/**
 * Get all entities.  NB: Plural arguments can be written as
 * singular if only specifying a single element.  (e.g., 'type' => 'object'
 * vs 'types' => array('object')).
 *
 * @param array $options Array in format:
 *
 * 	types => NULL|STR entity type (SQL: type IN ('type1', 'type2') Joined with subtypes by AND...see below)
 *
 * 	subtypes => NULL|STR entity subtype (SQL: subtype IN ('subtype1', 'subtype2))
 *
 * 	type_subtype_pairs => NULL|ARR (array('type' => 'subtype')) (SQL: type = '$type' AND subtype = '$subtype') pairs
 *
 * 	owner_guids => NULL|INT entity guid
 *
 * 	container_guids => NULL|INT container_guid
 *
 * 	site_guids => NULL (current_site)|INT site_guid
 *
 * 	order_by => NULL (time_created desc)|STR SQL order by clause
 *
 * 	limit => NULL (10)|INT SQL limit clause
 *
 * 	offset => NULL (0)|INT SQL offset clause
 *
 * 	created_time_lower => NULL|INT Created time lower boundary in epoch time
 *
 * 	created_time_upper => NULL|INT Created time upper boundary in epoch time
 *
 * 	modified_time_lower => NULL|INT Modified time lower boundary in epoch time
 *
 * 	modified_time_upper => NULL|INT Modified time upper boundary in epoch time
 *
 * 	count => TRUE|FALSE return a count instead of entities
 *
 * 	wheres => array() Additional where clauses to AND together
 *
 * 	joins => array() Additional joins
 *
 * @return 	if count, int
 * 			if not count, array or false if no entities
 */
function elgg_get_entities(array $options = array()) {
	global $CONFIG;

	$defaults = array(
		'types'					=>	ELGG_ENTITIES_ANY_VALUE,
		'subtypes'				=>	ELGG_ENTITIES_ANY_VALUE,
		'type_subtype_pairs'	=>	ELGG_ENTITIES_ANY_VALUE,

		'owner_guids'			=>	ELGG_ENTITIES_ANY_VALUE,
		'container_guids'		=>	ELGG_ENTITIES_ANY_VALUE,
		'site_guids'			=>	$CONFIG->site_guid,

		'modified_time_lower'	=>	ELGG_ENTITIES_ANY_VALUE,
		'modified_time_upper'	=>	ELGG_ENTITIES_ANY_VALUE,
		'created_time_lower'	=>	ELGG_ENTITIES_ANY_VALUE,
		'created_time_upper'	=>	ELGG_ENTITIES_ANY_VALUE,

		'order_by' 				=>	'e.time_created desc',
		'group_by'				=>	ELGG_ENTITIES_ANY_VALUE,
		'limit'					=>	10,
		'offset'				=>	0,
		'count'					=>	FALSE,
		'selects'				=>	array(),
		'wheres'				=>	array(),
		'joins'					=>	array()
	);


	$options = array_merge($defaults, $options);

	$singulars = array('type', 'subtype', 'owner_guid', 'container_guid', 'site_guid', 'type_subtype_pair');
	$options = elgg_normalise_plural_options_array($options, $singulars);

	// evaluate where clauses
	if (!is_array($options['wheres'])) {
		$options['wheres'] = array($options['wheres']);
	}

	$wheres = $options['wheres'];

	$wheres[] = elgg_get_entity_type_subtype_where_sql('e', $options['types'], $options['subtypes'], $options['type_subtype_pairs']);
	$wheres[] = elgg_get_entity_site_where_sql('e', $options['site_guids']);
	$wheres[] = elgg_get_entity_owner_where_sql('e', $options['owner_guids']);
	$wheres[] = elgg_get_entity_container_where_sql('e', $options['container_guids']);
	$wheres[] = elgg_get_entity_time_where_sql('e', $options['created_time_upper'],
		$options['created_time_lower'], $options['modified_time_upper'], $options['modified_time_lower']);

	// remove identical where clauses
	$wheres = array_unique($wheres);

	// see if any functions failed
	// remove empty strings on successful functions
	foreach ($wheres as $i => $where) {
		if ($where === FALSE) {
			return FALSE;
		} elseif (empty($where)) {
			unset($wheres[$i]);
		}
	}

	// evaluate join clauses
	if (!is_array($options['joins'])) {
		$options['joins'] = array($options['joins']);
	}

	// remove identical join clauses
	$joins = array_unique($options['joins']);

	foreach ($joins as $i => $join) {
		if ($join === FALSE) {
			return FALSE;
		} elseif (empty($join)) {
			unset($joins[$i]);
		}
	}

	// evalutate selects
	if ($options['selects']) {
		$selects = '';
		foreach ($options['selects'] as $select) {
			$selects = ", $select";
		}
	} else {
		$selects = '';
	}

	if (!$options['count']) {
		$query = "SELECT DISTINCT e.*{$selects} FROM {$CONFIG->dbprefix}entities e ";
	} else {
		$query = "SELECT count(DISTINCT e.guid) as total FROM {$CONFIG->dbprefix}entities e ";
	}

	// add joins
	foreach ($joins as $j) {
		$query .= " $j ";
	}

	// add wheres
	$query .= ' WHERE ';

	foreach ($wheres as $w) {
		$query .= " $w AND ";
	}

	// Add access controls
	$query .= get_access_sql_suffix('e');
	if (!$options['count']) {
		if ($options['group_by'] = sanitise_string($options['group_by'])) {
			$query .= " GROUP BY {$options['group_by']}";
		}

		if ($options['order_by'] = sanitise_string($options['order_by'])) {
			$query .= " ORDER BY {$options['order_by']}";
		}

		if ($options['limit']) {
			$limit = sanitise_int($options['limit']);
			$offset = sanitise_int($options['offset']);
			$query .= " LIMIT $offset, $limit";
		}

		$dt = get_data($query, "entity_row_to_elggstar");

		//@todo normalize this to array()
		return $dt;
	} else {
		$total = get_data_row($query);
		return (int)$total->total;
	}
}

/**
 * Returns type and subtype SQL appropriate for inclusion in an IN clause.
 *
 * @param string $table entity table prefix.
 * @param NULL|$types
 * @param NULL|array $subtypes
 * @param NULL|array $pairs
 * @return FALSE|string
 */
function elgg_get_entity_type_subtype_where_sql($table, $types, $subtypes, $pairs) {
	// subtype depends upon type.
	if ($subtypes && !$types) {
		elgg_log("Cannot set subtypes without type.", 'WARNING');
		return FALSE;
	}

	// short circuit if nothing is requested
	if (!$types && !$subtypes && !$pairs) {
		return '';
	}

	// these are the only valid types for entities in elgg as defined in the DB.
	$valid_types = array('object', 'user', 'group', 'site');

	// pairs override
	$wheres = array();
	if (!is_array($pairs)) {
		if (!is_array($types)) {
			$types = array($types);
		}

		if ($subtypes && !is_array($subtypes)) {
			$subtypes = array($subtypes);
		}

		// decrementer for valid types.  Return FALSE if no valid types
		$valid_types_count = count($types);
		$valid_subtypes_count = 0;
		// remove invalid types to get an accurate count of
		// valid types for the invalid subtype detection to use
		// below.
		// also grab the count of ALL subtypes on valid types to decrement later on
		// and check against.
		//
		// yes this is duplicating a foreach on $types.
		foreach ($types as $type) {
			if (!in_array($type, $valid_types)) {
				$valid_types_count--;
				unset ($types[array_search($type, $types)]);
			} else {
				// do the checking (and decrementing) in the subtype section.
				$valid_subtypes_count += count($subtypes);
			}
		}

		// return false if nothing is valid.
		if (!$valid_types_count) {
			return FALSE;
		}

		// subtypes are based upon types, so we need to look at each
		// type individually to get the right subtype id.
		foreach ($types as $type) {
			$subtype_ids = array();
			if ($subtypes) {
				foreach ($subtypes as $subtype) {
					// check that the subtype is valid (with ELGG_ENTITIES_NO_VALUE being a valid subtype)
					if (ELGG_ENTITIES_NO_VALUE === $subtype || $subtype_id = get_subtype_id($type, $subtype)) {
						$subtype_ids[] = (ELGG_ENTITIES_NO_VALUE === $subtype) ? ELGG_ENTITIES_NO_VALUE : $subtype_id;
					} else {
						$valid_subtypes_count--;
						elgg_log("Type-subtype $type:$subtype' does not exist!", 'WARNING');
						continue;
					}
				}

				// return false if we're all invalid subtypes in the only valid type
				if ($valid_subtypes_count <= 0) {
					return FALSE;
				}
			}

			if (is_array($subtype_ids) && count($subtype_ids)) {
				$subtype_ids_str = implode(',', $subtype_ids);
				$wheres[] = "({$table}.type = '$type' AND {$table}.subtype IN ($subtype_ids_str))";
			} else {
				$wheres[] = "({$table}.type = '$type')";
			}
		}
	} else {
		// using type/subtype pairs
		$valid_pairs_count = count($pairs);
		$valid_pairs_subtypes_count = 0;

		// same deal as above--we need to know how many valid types
		// and subtypes we have before hitting the subtype section.
		// also normalize the subtypes into arrays here.
		foreach ($pairs as $paired_type => $paired_subtypes) {
			if (!in_array($paired_type, $valid_types)) {
				$valid_pairs_count--;
				unset ($pairs[array_search($paired_type, $pairs)]);
			} else {
				if ($paired_subtypes && !is_array($paired_subtypes)) {
					$pairs[$paired_type] = array($paired_subtypes);
				}
				$valid_pairs_subtypes_count += count($paired_subtypes);
			}
		}

		if ($valid_pairs_count <= 0) {
			return FALSE;
		}
		foreach ($pairs as $paired_type => $paired_subtypes) {
			// this will always be an array because of line 2027, right?
			// no...some overly clever person can say pair => array('object' => null)
			if (is_array($paired_subtypes)) {
				$paired_subtype_ids = array();
				foreach ($paired_subtypes as $paired_subtype) {
					if (ELGG_ENTITIES_NO_VALUE === $paired_subtype || ($paired_subtype_id = get_subtype_id($paired_type, $paired_subtype))) {
						$paired_subtype_ids[] = (ELGG_ENTITIES_NO_VALUE === $paired_subtype) ? ELGG_ENTITIES_NO_VALUE : $paired_subtype_id;
					} else {
						$valid_pairs_subtypes_count--;
						elgg_log("Type-subtype $paired_type:$paired_subtype' does not exist!", 'WARNING');
						// return false if we're all invalid subtypes in the only valid type
						continue;
					}
				}

				// return false if there are no valid subtypes.
				if ($valid_pairs_subtypes_count <= 0) {
					return FALSE;
				}


				if ($paired_subtype_ids_str = implode(',', $paired_subtype_ids)) {
					$wheres[] = "({$table}.type = '$paired_type' AND {$table}.subtype IN ($paired_subtype_ids_str))";
				}
			} else {
				$wheres[] = "({$table}.type = '$paired_type')";
			}
		}
	}

	// pairs override the above.  return false if they don't exist.
	if (is_array($wheres) && count($wheres)) {
		$where = implode(' OR ', $wheres);
		return "($where)";
	}

	return '';
}

/**
 * Gets SQL for site entities
 *
 * @param string $table entity table name
 * @param NULL|array $site_guids
 * @return FALSE|string
 */
function elgg_get_entity_site_where_sql($table, $site_guids) {
	// short circuit if nothing requested
	if (!$site_guids) {
		return '';
	}

	if (!is_array($site_guids)) {
		$site_guids = array($site_guids);
	}

	$site_guids_sanitised = array();
	foreach ($site_guids as $site_guid) {
		if (!$site_guid || ($site_guid != sanitise_int($site_guid))) {
			return FALSE;
		}
		$site_guids_sanitised[] = $site_guid;
	}

	if ($site_guids_str = implode(',', $site_guids_sanitised)) {
		return "({$table}.site_guid IN ($site_guids_str))";
	}

	return '';
}

/**
 * Returns SQL for owner and containers.
 *
 * @todo Probably DRY up once things are settled.
 * @param str $table
 * @param NULL|array $owner_guids
 * @return FALSE|str
 */
function elgg_get_entity_owner_where_sql($table, $owner_guids) {
	// short circuit if nothing requested
	// 0 is a valid owner_guid.
	if (!$owner_guids && $owner_guids !== 0) {
		return '';
	}

	// normalize and sanitise owners
	if (!is_array($owner_guids)) {
		$owner_guids = array($owner_guids);
	}

	$owner_guids_sanitised = array();
	foreach ($owner_guids as $owner_guid) {
		if (($owner_guid != sanitise_int($owner_guid))) {
			return FALSE;
		}
		$owner_guids_sanitised[] = $owner_guid;
	}

	$where = '';

	// implode(',', 0) returns 0.
	if (($owner_str = implode(',', $owner_guids_sanitised)) && ($owner_str !== FALSE) && ($owner_str !== '')) {
		$where = "({$table}.owner_guid IN ($owner_str))";
	}

	return $where;
}

/**
 * Returns SQL for containers.
 *
 * @param string $table entity table prefix
 * @param NULL|array $container_guids
 * @return FALSE|string
 */
function elgg_get_entity_container_where_sql($table, $container_guids) {
	// short circuit if nothing is requested.
	// 0 is a valid container_guid.
	if (!$container_guids && $container_guids !== 0) {
		return '';
	}

	// normalize and sanitise containers
	if (!is_array($container_guids)) {
		$container_guids = array($container_guids);
	}

	$container_guids_sanitised = array();
	foreach ($container_guids as $container_guid) {
		if (($container_guid != sanitise_int($container_guid))) {
			return FALSE;
		}
		$container_guids_sanitised[] = $container_guid;
	}

	$where = '';

	// implode(',', 0) returns 0.
	if (FALSE !== $container_str = implode(',', $container_guids_sanitised)) {
		$where = "({$table}.container_guid IN ($container_str))";
	}

	return $where;
}

/**
 * Returns SQL where clause for entity time limits.
 *
 * @param string $table Prefix for entity table name.
 * @param NULL|int $time_created_upper
 * @param NULL|int $time_created_lower
 * @param NULL|int $time_updated_upper
 * @param NULL|int $time_updated_lower
 *
 * @return FALSE|str FALSE on fail, string on success.
 */
function elgg_get_entity_time_where_sql($table, $time_created_upper = NULL, $time_created_lower = NULL,
	$time_updated_upper = NULL, $time_updated_lower = NULL) {

	$wheres = array();

	// exploit PHP's loose typing (quack) to check that they are INTs and not str cast to 0
	if ($time_created_upper && $time_created_upper == sanitise_int($time_created_upper)) {
		$wheres[] = "{$table}.time_created <= $time_created_upper";
	}

	if ($time_created_lower && $time_created_lower == sanitise_int($time_created_lower)) {
		$wheres[] = "{$table}.time_created >= $time_created_lower";
	}

	if ($time_updated_upper && $time_updated_upper == sanitise_int($time_updated_upper)) {
		$wheres[] = "{$table}.time_updated <= $time_updated_upper";
	}

	if ($time_updated_lower && $time_updated_lower == sanitise_int($time_updated_lower)) {
		$wheres[] = "{$table}.time_updated >= $time_updated_lower";
	}

	if (is_array($wheres) && count($wheres) > 0) {
		$where_str = implode(' AND ', $wheres);
		return "($where_str)";
	}

	return '';
}

/**
 * Normalise the singular keys in an options array
 * to the plural keys.
 *
 * @param $options
 * @param $singulars
 * @return array
 */
function elgg_normalise_plural_options_array($options, $singulars) {
	foreach ($singulars as $singular) {
		$plural = $singular . 's';

		// normalize the singular to plural
		// isset() returns FALSE for array values of NULL, so they are ignored.
		// everything else falsy is included.
		//if (isset($options[$singular]) && $options[$singular] !== NULL && $options[$singular] !== FALSE) {
		if (isset($options[$singular])) {
			if (isset($options[$plural])) {
				if (is_array($options[$plural])) {
					$options[$plural][] = $options[$singlar];
				} else {
					$options[$plural] = array($options[$plural], $options[$singular]);
				}
			} else {
				$options[$plural] = array($options[$singular]);
			}
		}
		unset($options[$singular]);
	}

	return $options;
}

/**
 * Throws a message to the Elgg logger
 *
 * The Elgg log is currently implemented such that any messages sent at a level
 * greater than or equal to the debug setting will be sent to elgg_dump.
 * The default location for elgg_dump is the screen except for notices.
 *
 * Note: No messages will be displayed unless debugging has been enabled.
 *
 * @param str $message User message
 * @param str $level NOTICE | WARNING | ERROR | DEBUG
 * @return bool
 */
function elgg_log($message, $level='NOTICE') {
	global $CONFIG;

	// only log when debugging is enabled
	if (isset($CONFIG->debug)) {
		// debug to screen or log?
		$to_screen = !($CONFIG->debug == 'NOTICE');

		switch ($level) {
			case 'ERROR':
				// always report
				elgg_dump("$level: $message", $to_screen, $level);
				break;
			case 'WARNING':
			case 'DEBUG':
				// report except if user wants only errors
				if ($CONFIG->debug != 'ERROR') {
					elgg_dump("$level: $message", $to_screen, $level);
				}
				break;
			case 'NOTICE':
			default:
				// only report when lowest level is desired
				if ($CONFIG->debug == 'NOTICE') {
					elgg_dump("$level: $message", FALSE, $level);
				}
				break;
		}

		return TRUE;
	}

	return FALSE;
}

?>