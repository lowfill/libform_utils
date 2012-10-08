<?php
/**
 * Elgg libform_utils plugin
 *
 * @package ElggLibFormUtils
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
 * @author Diego Andrés Ramírez Aragón <dramirezaragon@gmail.com>
 * @copyright Diego Andrés Ramírez Aragón 2010
 * @link http://github.com/lowfill/libform_utils
 */

/**
 * libform_utils initialization
 */
function libform_utils_init(){
	elgg_extend_view('css','libform/css');
	
	register_page_handler('libform','libform_page_handler');
	register_page_handler('suggest','suggest_page_handler');
	register_page_handler('grid','grid_page_handler');
	register_page_handler('timeline','timeline_page_handler');

	set_include_path(dirname(__FILE__)."/vendors/pear/:".get_include_path());
}

/**
 * libform_utils page handler
 * @param $page
 */
function libform_page_handler($page){
	include dirname(__FILE__)."/examples.php";
}

/**
 * Suggest page handler
 *
 * Process urls that match /pg/suggest/(user|group)
 * @param $page
 */
function suggest_page_handler($page){
	global $CONFIG;
	$query=get_input("q");
	if(count($page)>0 && in_array($page[0],array('user','group'))){
		if(!empty($query)){
			switch($page[0]){
				case 'user':
					$join = "JOIN {$CONFIG->dbprefix}users_entity ue ON e.guid = ue.guid";
					$where = "(ue.guid = e.guid AND (ue.username LIKE '%$query%' OR ue.name LIKE '%$query%'))";
					break;
				case 'group':
					$join = "JOIN {$CONFIG->dbprefix}groups_entity ue ON e.guid = ue.guid";
					$where = "(ue.guid = e.guid	AND (ue.name LIKE '%$query%'))";
					break;
			}
			$options = array(
            'type'=>$page[0],
            'subtype'=>ELGG_ENTITIES_NO_VALUE,
        	'joins'=>array($join),
        	'wheres'=>array($where),
			);
			$entities = elgg_get_entities($options);
		}
		$data = array();
		if(!empty($entities)){
			foreach($entities as $entity){
				$data[]=array('id'=>$entity->guid,'name'=>$entity->name,'url'=>$entity->getURL());
			}
		}
	}
	else{
		$function_name = "{$page[0]}_suggest_hook";
		$extra_param = (isset($page[1]))?$page[1]:"";
		if(function_exists($function_name)){
			$data = $function_name($query,$extra_param);
		}
	}
	header("Content-type: application/json");
	echo json_encode($data);
	exit;
}

/**
 * Grid page handler
 * @param $page
 */
function grid_page_handler($page){
	$handler = (isset($page[0]))?$page[0]:"users";
	$view = "grid/{$handler}_data";
	if(elgg_view_exists($view)){
		header("Content-type: application/json");
		echo elgg_view($view);
		exit;
			
	}
}
/**
 * Timeline page handler
 * @param $page
 */
function timeline_page_handler($page){
	$handler = (isset($page[0]))?$page[0]:"users";
	$view = "timeline/{$handler}_data";
	if(elgg_view_exists($view)){
		header("Content-type: application/json");
		echo elgg_view($view);
		exit;
			
	}
}

/**
 * Get the list of validators and messages from an string
 *
 * @param string $validator_str ';' separated validators string
 * @param string $messages String with the custom messages for the field
 * @return string
 */
function libform_get_validators($validator_str,$messages=""){
	$validators = array();
	$u_validators = explode(";",$validator_str);
	if(!is_array($u_validators)){
		$u_validators = array($u_validators);
	}
	foreach($u_validators as $validator){
		if(empty($validator)) continue;
		if(strpos($validator,":")>0){
			$validators[]="{$validator}";
		}
		else{
			$validators[]="{$validator}:true";
		}
	}
	if(!empty($messages)){
		$validators[]="messages:{".$messages."}";
	}
	return "{".implode(",",$validators)."}";
}

function libform_get_countries(){
	static $countries = array();
	$raw = file(dirname(__FILE__)."/vendors/countries.txt");
	if(empty($countries)){
		foreach($raw as $country){
			$country = explode(";",$country);
			$countries[$country[0]]=$country[1];
		}
		$countries = array_map(elgg_echo,$countries);
	}
	return $countries;
}

function libform_get_country($code){
	$countries = libform_get_countries();
	return $countries[$code];
}

function libform_get_timezone($timezone,$full=true){
	$timezones = libform_get_timezones();
	$timezone = $timezones[$timezone];
	if(!$full){
		$timezone = substr($timezone,strpos($timezone, ")")+1);
	}
	return $timezone;
}
	
function libform_get_timezones(){
	// Data taked from Google Calendar timezones plugin
	$timezones = array(
		'Pacific/Midway' => '(GMT-11:00) Midway',
		'Pacific/Honolulu' => '(GMT-10:00) ' . "Hawaii Time",
		'Pacific/Tahiti' => '(GMT-10:00) Tahiti',
		'Pacific/Marquesas' => '(GMT-09:30) Marquesas',
		'America/Anchorage' => '(GMT-09:00) ' . "Alaska Time",
		'Pacific/Gambier' => '(GMT-09:00) Gambier',
		'America/Los_Angeles' => '(GMT-08:00) ' . "Pacific Time",
		'America/Tijuana' => '(GMT-08:00) Tijuana',
		'America/Vancouver' => '(GMT-08:00) Vancouver',
		'America/Whitehorse' => '(GMT-08:00) Whitehorse',
		'Pacific/Pitcairn' => '(GMT-08:00) Pitcairn',
		'America/Dawson_Creek' => '(GMT-07:00) Dawson Creek',
		'America/Denver' => '(GMT-07:00) ' . "Mountain Time",
		'America/Edmonton' => '(GMT-07:00) Edmonton',
		'America/Hermosillo' => '(GMT-07:00) Hermosillo',
		'America/Mazatlan' => '(GMT-07:00) Chihuahua, Mazatlan',
		'America/Phoenix' => '(GMT-07:00) Arizona',
		'America/Yellowknife' => '(GMT-07:00) Yellowknife',
		'America/Belize' => '(GMT-06:00) Belize',
		'America/Chicago' => '(GMT-06:00) ' . "Central Time",
		'America/Costa_Rica' => '(GMT-06:00) Costa Rica',
		'America/El_Salvador' => '(GMT-06:00) El Salvador',
		'America/Guatemala' => '(GMT-06:00) Guatemala',
		'America/Managua' => '(GMT-06:00) Managua',
		'America/Mexico_City' => '(GMT-06:00) Mexico City',
		'America/Regina' => '(GMT-06:00) Regina',
		'America/Tegucigalpa' => '(GMT-06:00) Tegucigalpa)',
		'America/Winnipeg' => '(GMT-06:00) Winnipeg',
		'Pacific/Easter' => '(GMT-06:00) Easter Island',
		'Pacific/Galapagos' => '(GMT-06:00) Galapagos',
		'America/Bogota' => '(GMT-05:00) Bogota',
		'America/Cayman' => '(GMT-05:00) Cayman',
		'America/Grand_Turk' => '(GMT-05:00) Grand Turk',
		'America/Guayaquil' => '(GMT-05:00) Guayaquil',
		'America/Havana' => '(GMT-05:00) Havana',
		'America/Iqaluit' => '(GMT-05:00) Iqaluit',
		'America/Jamaica' => '(GMT-05:00) Jamaica',
		'America/Lima' => '(GMT-05:00) Lima',
		'America/Montreal' => '(GMT-05:00) Montreal',
		'America/Nassau' => '(GMT-05:00) Nassau',
		'America/New_York' => '(GMT-05:00) ' . "Eastern Time",
		'America/Panama' => '(GMT-05:00) Panama',
		'America/Port-au-Prince' => '(GMT-05:00) Port-au-Prince',
		'America/Toronto' => '(GMT-05:00) Toronto',
		'America/Caracas' => '(GMT-04:30) Caracas',
		'America/Anguilla' => '(GMT-04:00) Anguilla',
		'America/Antigua' => '(GMT-04:00) Antigua',
		'America/Aruba' => '(GMT-04:00) Aruba',
		'America/Asuncion' => '(GMT-04:00) Asuncion',
		'America/Barbados' => '(GMT-04:00) Barbados',
		'America/Boa_Vista' => '(GMT-04:00) Boa Vista',
		'America/Campo_Grande' => '(GMT-04:00) Campo Grande',
		'America/Cuiaba' => '(GMT-04:00) Cuiaba',
		'America/Curacao' => '(GMT-04:00) Curacao',
		'America/Dominica' => '(GMT-04:00) Dominica',
		'America/Grenada' => '(GMT-04:00) Grenada',
		'America/Guadeloupe' => '(GMT-04:00) Guadeloupe',
		'America/Guyana' => '(GMT-04:00) Guyana',
		'America/Halifax' => '(GMT-04:00) Atlantic Time',
		'America/La_Paz' => '(GMT-04:00) La Paz',
		'America/Manaus' => '(GMT-04:00) Manaus',
		'America/Martinique' => '(GMT-04:00) Martinique',
		'America/Montserrat' => '(GMT-04:00) Montserrat',
		'America/Port_of_Spain' => '(GMT-04:00) Port of Spain',
		'America/Porto_Velho' => '(GMT-04:00) Porto Velho',
		'America/Puerto_Rico' => '(GMT-04:00) Puerto Rico',
		'America/Rio_Branco' => '(GMT-04:00) Rio Branco',
		'America/Santiago' => '(GMT-04:00) Santiago',
		'America/Santo_Domingo' => '(GMT-04:00) Santo Domingo',
		'America/St_Kitts' => '(GMT-04:00) St. Kitts',
		'America/St_Lucia' => '(GMT-04:00) St. Lucia',
		'America/St_Thomas' => '(GMT-04:00) St. Thomas',
		'America/St_Vincent' => '(GMT-04:00) St. Vincent',
		'America/Thule' => '(GMT-04:00) Thule',
		'America/Tortola' => '(GMT-04:00) Tortola',
		'Antarctica/Palmer' => '(GMT-04:00) Palmer',
		'Atlantic/Bermuda' => '(GMT-04:00) Bermuda',
		'Atlantic/Stanley' => '(GMT-04:00) Stanley',
		'America/St_Johns' => '(GMT-03:30) Newfoundland',
		'America/Araguaina' => '(GMT-03:00) Araguaina',
		'America/Argentina/Buenos_Aires' => '(GMT-03:00) Buenos Aires',
		'America/Bahia' => '(GMT-03:00) Salvador',
		'America/Belem' => '(GMT-03:00) Belem',
		'America/Cayenne' => '(GMT-03:00) Cayenne',
		'America/Fortaleza' => '(GMT-03:00) Fortaleza',
		'America/Godthab' => '(GMT-03:00) Godthab',
		'America/Maceio' => '(GMT-03:00) Maceio',
		'America/Miquelon' => '(GMT-03:00) Miquelon',
		'America/Montevideo' => '(GMT-03:00) Montevideo',
		'America/Paramaribo' => '(GMT-03:00) Paramaribo',
		'America/Recife' => '(GMT-03:00) Recife',
		'America/Sao_Paulo' => '(GMT-03:00) Sao Paulo',
		'Antarctica/Rothera' => '(GMT-03:00) Rothera',
		'America/Noronha' => '(GMT-02:00) Noronha',
		'Atlantic/South_Georgia' => '(GMT-02:00) South Georgia',
		'America/Scoresbysund' => '(GMT-01:00) Scoresbysund',
		'Atlantic/Azores' => '(GMT-01:00) Azores',
		'Atlantic/Cape_Verde' => '(GMT-01:00) Cape Verde',
		'Africa/Abidjan' => '(GMT+00:00) Abidjan',
		'Africa/Accra' => '(GMT+00:00) Accra',
		'Africa/Bamako' => '(GMT+00:00) Bamako',
		'Africa/Banjul' => '(GMT+00:00) Banjul',
		'Africa/Bissau' => '(GMT+00:00) Bissau',
		'Africa/Casablanca' => '(GMT+00:00) Casablanca',
		'Africa/Conakry' => '(GMT+00:00) Conakry',
		'Africa/Dakar' => '(GMT+00:00) Dakar',
		'Africa/El_Aaiun' => '(GMT+00:00) El Aaiun',
		'Africa/Freetown' => '(GMT+00:00) Freetown',
		'Africa/Lome' => '(GMT+00:00) Lome',
		'Africa/Monrovia' => '(GMT+00:00) Monrovia',
		'Africa/Nouakchott' => '(GMT+00:00) Nouakchott',
		'Africa/Ouagadougou' => '(GMT+00:00) Ouagadougou',
		'Africa/Sao_Tome' => '(GMT+00:00) Sao Tome',
		'America/Danmarkshavn' => '(GMT+00:00) Danmarkshavn',
		'Atlantic/Canary' => '(GMT+00:00) Canary Islands',
		'Atlantic/Faroe' => '(GMT+00:00) Faeroe',
		'Atlantic/Reykjavik' => '(GMT+00:00) Reykjavik',
		'Atlantic/St_Helena' => '(GMT+00:00) St Helena',
		'Etc/GMT' => '(GMT+00:00) GMT (no daylight saving)',
		'Europe/Dublin' => '(GMT+00:00) Dublin',
		'Europe/Lisbon' => '(GMT+00:00) Lisbon',
		'Europe/London' => '(GMT+00:00) London',
		'Africa/Algiers' => '(GMT+01:00) Algiers',
		'Africa/Bangui' => '(GMT+01:00) Bangui',
		'Africa/Brazzaville' => '(GMT+01:00) Brazzaville',
		'Africa/Ceuta' => '(GMT+01:00) Ceuta',
		'Africa/Douala' => '(GMT+01:00) Douala',
		'Africa/Kinshasa' => '(GMT+01:00) Kinshasa',
		'Africa/Lagos' => '(GMT+01:00) Lagos',
		'Africa/Libreville' => '(GMT+01:00) Libreville',
		'Africa/Luanda' => '(GMT+01:00) Luanda',
		'Africa/Malabo' => '(GMT+01:00) Malabo',
		'Africa/Ndjamena' => '(GMT+01:00) Ndjamena',
		'Africa/Niamey' => '(GMT+01:00) Niamey',
		'Africa/Porto-Novo' => '(GMT+01:00) Porto-Novo',
		'Africa/Tunis' => '(GMT+01:00) Tunis',
		'Africa/Windhoek' => '(GMT+01:00) Windhoek',
		'Europe/Amsterdam' => '(GMT+01:00) Amsterdam',
		'Europe/Andorra' => '(GMT+01:00) Andorra',
		'Europe/Belgrade' => '(GMT+01:00) Belgrade',
		'Europe/Berlin' => '(GMT+01:00) Berlin',
		'Europe/Brussels' => '(GMT+01:00) Brussels',
		'Europe/Budapest' => '(GMT+01:00) Budapest',
		'Europe/Copenhagen' => '(GMT+01:00) Copenhagen',
		'Europe/Gibraltar' => '(GMT+01:00) Gibraltar',
		'Europe/Luxembourg' => '(GMT+01:00) Luxembourg',
		'Europe/Madrid' => '(GMT+01:00) Madrid',
		'Europe/Malta' => '(GMT+01:00) Malta',
		'Europe/Monaco' => '(GMT+01:00) Monaco',
		'Europe/Oslo' => '(GMT+01:00) Oslo',
		'Europe/Paris' => '(GMT+01:00) Paris',
		'Europe/Prague' => '(GMT+01:00) Prague',
		'Europe/Rome' => '(GMT+01:00) Rome',
		'Europe/Stockholm' => '(GMT+01:00) Stockholm',
		'Europe/Tirane' => '(GMT+01:00) Tirane',
		'Europe/Vaduz' => '(GMT+01:00) Vaduz',
		'Europe/Vienna' => '(GMT+01:00) Vienna',
		'Europe/Warsaw' => '(GMT+01:00) Warsaw',
		'Europe/Zurich' => '(GMT+01:00) Zurich',
		'Africa/Blantyre' => '(GMT+02:00) Blantyre',
		'Africa/Bujumbura' => '(GMT+02:00) Bujumbura',
		'Africa/Cairo' => '(GMT+02:00) Cairo',
		'Africa/Gaborone' => '(GMT+02:00) Gaborone',
		'Africa/Harare' => '(GMT+02:00) Harare',
		'Africa/Johannesburg' => '(GMT+02:00) Johannesburg',
		'Africa/Kigali' => '(GMT+02:00) Kigali',
		'Africa/Lubumbashi' => '(GMT+02:00) Lubumbashi',
		'Africa/Lusaka' => '(GMT+02:00) Lusaka',
		'Africa/Maputo' => '(GMT+02:00) Maputo',
		'Africa/Maseru' => '(GMT+02:00) Maseru',
		'Africa/Mbabane' => '(GMT+02:00) Mbabane',
		'Africa/Tripoli' => '(GMT+02:00) Tripoli',
		'Asia/Amman' => '(GMT+02:00) Amman',
		'Asia/Beirut' => '(GMT+02:00) Beirut',
		'Asia/Damascus' => '(GMT+02:00) Damascus',
		'Asia/Gaza' => '(GMT+02:00) Gaza',
		'Asia/Jerusalem' => '(GMT+02:00) Jerusalem',
		'Asia/Nicosia' => '(GMT+02:00) Nicosia',
		'Europe/Athens' => '(GMT+02:00) Athens',
		'Europe/Bucharest' => '(GMT+02:00) Bucharest',
		'Europe/Chisinau' => '(GMT+02:00) Chisinau',
		'Europe/Helsinki' => '(GMT+02:00) Helsinki',
		'Europe/Istanbul' => '(GMT+02:00) Istanbul',
		'Europe/Kaliningrad' => '(GMT+02:00) Kaliningrad',
		'Europe/Kiev' => '(GMT+02:00) Kiev',
		'Europe/Minsk' => '(GMT+02:00) Minsk',
		'Europe/Riga' => '(GMT+02:00) Riga',
		'Europe/Sofia' => '(GMT+02:00) Sofia',
		'Europe/Tallinn' => '(GMT+02:00) Tallinn',
		'Europe/Vilnius' => '(GMT+02:00) Vilnius',
		'Africa/Addis_Ababa' => '(GMT+03:00) Addis Ababa',
		'Africa/Asmara' => '(GMT+03:00) Asmera',
		'Africa/Dar_es_Salaam' => '(GMT+03:00) Dar es Salaam',
		'Africa/Djibouti' => '(GMT+03:00) Djibouti',
		'Africa/Kampala' => '(GMT+03:00) Kampala',
		'Africa/Khartoum' => '(GMT+03:00) Khartoum',
		'Africa/Mogadishu' => '(GMT+03:00) Mogadishu',
		'Africa/Nairobi' => '(GMT+03:00) Nairobi',
		'Antarctica/Syowa' => '(GMT+03:00) Syowa',
		'Asia/Aden' => '(GMT+03:00) Aden',
		'Asia/Baghdad' => '(GMT+03:00) Baghdad',
		'Asia/Bahrain' => '(GMT+03:00) Bahrain',
		'Asia/Kuwait' => '(GMT+03:00) Kuwait',
		'Asia/Qatar' => '(GMT+03:00) Qatar',
		'Asia/Riyadh' => '(GMT+03:00) Riyadh',
		'Europe/Moscow' => '(GMT+03:00) Moscow',
		'Indian/Antananarivo' => '(GMT+03:00) Antananarivo',
		'Indian/Comoro' => '(GMT+03:00) Comoro',
		'Indian/Mayotte' => '(GMT+03:00) Mayotte',
		'Asia/Tehran' => '(GMT+03:30) Tehran',
		'Asia/Baku' => '(GMT+04:00) Baku',
		'Asia/Dubai' => '(GMT+04:00) Dubai',
		'Asia/Muscat' => '(GMT+04:00) Muscat',
		'Asia/Tbilisi' => '(GMT+04:00) Tbilisi',
		'Asia/Yerevan' => '(GMT+04:00) Yerevan',
		'Europe/Samara' => '(GMT+04:00) Samara',
		'Indian/Mahe' => '(GMT+04:00) Mahe',
		'Indian/Mauritius' => '(GMT+04:00) Mauritius',
		'Indian/Reunion' => '(GMT+04:00) Reunion',
		'Asia/Kabul' => '(GMT+04:30) Kabul',
		'Asia/Aqtau' => '(GMT+05:00) Aqtau',
		'Asia/Aqtobe' => '(GMT+05:00) Aqtobe',
		'Asia/Ashgabat' => '(GMT+05:00) Ashgabat',
		'Asia/Dushanbe' => '(GMT+05:00) Dushanbe',
		'Asia/Karachi' => '(GMT+05:00) Karachi',
		'Asia/Tashkent' => '(GMT+05:00) Tashkent',
		'Asia/Yekaterinburg' => '(GMT+05:00) Yekaterinburg',
		'Indian/Kerguelen' => '(GMT+05:00) Kerguelen',
		'Indian/Maldives' => '(GMT+05:00) Maldives',
		'Asia/Calcutta' => '(GMT+05:30) India',
		'Asia/Colombo' => '(GMT+05:30) Colombo',
		'Asia/Katmandu' => '(GMT+05:45) Katmandu',
		'Antarctica/Mawson' => '(GMT+06:00) Mawson',
		'Antarctica/Vostok' => '(GMT+06:00) Vostok',
		'Asia/Almaty' => '(GMT+06:00) Almaty',
		'Asia/Bishkek' => '(GMT+06:00) Bishkek',
		'Asia/Dhaka' => '(GMT+06:00) Dhaka',
		'Asia/Omsk' => '(GMT+06:00) Omsk, Novosibirsk',
		'Asia/Thimphu' => '(GMT+06:00) Thimphu',
		'Indian/Chagos' => '(GMT+06:00) Chagos',
		'Asia/Rangoon' => '(GMT+06:30) Rangoon',
		'Indian/Cocos' => '(GMT+06:30) Cocos',
		'Antarctica/Davis' => '(GMT+07:00) Davis',
		'Asia/Bangkok' => '(GMT+07:00) Bangkok',
		'Asia/Hovd' => '(GMT+07:00) Hovd',
		'Asia/Jakarta' => '(GMT+07:00) Jakarta',
		'Asia/Krasnoyarsk' => '(GMT+07:00) Krasnoyarsk',
		'Asia/Phnom_Penh' => '(GMT+07:00) Phnom Penh',
		'Asia/Saigon' => '(GMT+07:00) Hanoi',
		'Asia/Vientiane' => '(GMT+07:00) Vientiane',
		'Indian/Christmas' => '(GMT+07:00) Christmas',
		'Antarctica/Casey' => '(GMT+08:00) Casey',
		'Asia/Brunei' => '(GMT+08:00) Brunei',
		'Asia/Choibalsan' => '(GMT+08:00) Choibalsan',
		'Asia/Hong_Kong' => '(GMT+08:00) Hong Kong',
		'Asia/Irkutsk' => '(GMT+08:00) Irkutsk',
		'Asia/Kuala_Lumpur' => '(GMT+08:00) Kuala Lumpur',
		'Asia/Macau' => '(GMT+08:00) Macau',
		'Asia/Makassar' => '(GMT+08:00) Makassar',
		'Asia/Manila' => '(GMT+08:00) Manila',
		'Asia/Shanghai' => '(GMT+08:00) China - Beijing',
		'Asia/Singapore' => '(GMT+08:00) Singapore',
		'Asia/Taipei' => '(GMT+08:00) Taipei',
		'Asia/Ulaanbaatar' => '(GMT+08:00) Ulaanbaatar',
		'Australia/Perth' => '(GMT+08:00) Western Time - Perth',
		'Asia/Dili' => '(GMT+09:00) Dili',
		'Asia/Jayapura' => '(GMT+09:00) Jayapura',
		'Asia/Pyongyang' => '(GMT+09:00) Pyongyang',
		'Asia/Seoul' => '(GMT+09:00) Seoul',
		'Asia/Tokyo' => '(GMT+09:00) Tokyo',
		'Asia/Yakutsk' => '(GMT+09:00) Yakutsk',
		'Pacific/Palau' => '(GMT+09:00) Palau',
		'Australia/Adelaide' => '(GMT+09:30) Adelaide',
		'Australia/Darwin' => '(GMT+09:30) Darwin',
		'Antarctica/DumontDUrville' => '(GMT+10:00) Dumont D\47Urville',
		'Asia/Vladivostok' => '(GMT+10:00) Yuzhno-Sakhalinsk',
		'Australia/Brisbane' => '(GMT+10:00)  Brisbane',
		'Australia/Hobart' => '(GMT+10:00) Hobart',
		'Australia/Sydney' => '(GMT+10:00) Melbourne, Sydney',
		'Pacific/Guam' => '(GMT+10:00) Guam',
		'Pacific/Port_Moresby' => '(GMT+10:00) Port Moresby',
		'Pacific/Saipan' => '(GMT+10:00) Saipan',
		'Pacific/Truk' => '(GMT+10:00) Truk',
		'Asia/Magadan' => '(GMT+11:00) Magadan',
		'Pacific/Efate' => '(GMT+11:00) Efate',
		'Pacific/Guadalcanal' => '(GMT+11:00) Guadalcanal',
		'Pacific/Kosrae' => '(GMT+11:00) Kosrae',
		'Pacific/Noumea' => '(GMT+11:00) Noumea',
		'Pacific/Ponape' => '(GMT+11:00) Ponape',
		'Pacific/Norfolk' => '(GMT+11:30) Norfolk',
		'Asia/Kamchatka' => '(GMT+12:00) Petropavlovsk-Kamchatskiy',
		'Pacific/Auckland' => '(GMT+12:00) Auckland',
		'Pacific/Fiji' => '(GMT+12:00) Fiji',
		'Pacific/Funafuti' => '(GMT+12:00) Funafuti',
		'Pacific/Kwajalein' => '(GMT+12:00) Kwajalein',
		'Pacific/Majuro' => '(GMT+12:00) Majuro',
		'Pacific/Nauru' => '(GMT+12:00) Nauru',
		'Pacific/Tarawa' => '(GMT+12:00) Tarawa',
		'Pacific/Wake' => '(GMT+12:00) Wake',
		'Pacific/Wallis' => '(GMT+12:00) Wallis',
		'Pacific/Enderbury' => '(GMT+13:00) Enderbury',
		'Pacific/Tongatapu' => '(GMT+13:00) Tongatapu',
		'Pacific/Kiritimati' => '(GMT+14:00) Kiritimati'
	);
	return $timezones;
}

register_elgg_event_handler('init','system','libform_utils_init');

?>