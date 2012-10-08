/*
 * jQuery Plugin: Suggest box entry field
 * Version 1.0
 *
 * Copyright (c) 2012 Diego Ramírez (http://lowfill.org)
 * 
 * Based on TokenInput made for James Smith 
 * 
 * Copyright (c) 2009 James Smith (http://loopj.com)
 * Licensed jointly under the GPL and MIT licenses,
 * choose which one suits your project best!
 *
 */
(function($) {

	$.fn.suggestBox = function(url, options) {
		var settings = $.extend({
			url : url,
			searchDelay : 300,
			minChars : 1,
			method : "GET",
			contentType : "json",
			queryParam : "q",
			onResult : null,
			target_box : null,
			target_box_style : "",
			target_box_title : ""
		}, options);

		return this.each(function() {
			var list = new $.suggestBox(this, settings);
		});
	};

	$.suggestBox = function(input, settings) {

		// Keep track of the timeout
		var timeout;

		// Keys "enum"
		var KEY = {
			BACKSPACE : 8,
			TAB : 9,
			RETURN : 13,
			ESC : 27,
			LEFT : 37,
			UP : 38,
			RIGHT : 39,
			DOWN : 40,
			COMMA : 188
		};

		var target_box;

		setup_target_box();

		$(input).blur(function() {
			// hide_target();
		}).keydown(function(event) {
			switch (event.keyCode) {
			case KEY.BACKSPACE:
				if ($(this).val().length <= 1) {
					hide_target();
				} else {
					// set a timeout just long enough to let
					// this function finish.
					setTimeout(function() {
						do_search(false);
					}, 5);
				}
				break;

			case KEY.TAB:
			case KEY.RETURN:
			case KEY.COMMA:
				hide_target();
				return true;

			default:
				if (is_printable_character(event.keyCode)) {
					// set a timeout just long enough to let
					// this function finish.
					setTimeout(function() {
						do_search(false);
					}, 5);
				}
				break;
			}
		});

		function is_printable_character(keycode) {
			if ((keycode >= 48 && keycode <= 90) || // 0-1a-z
			(keycode >= 96 && keycode <= 111) || // numpad 0-9 + - / * .
			(keycode >= 186 && keycode <= 192) || // ; = , - . / ^
			(keycode >= 219 && keycode <= 222) // ( \ ) '
			) {
				return true;
			} else {
				return false;
			}
		}
		// Do a search and show the "searching" dropdown if the input is longer
		// than settings.minChars
		function do_search(immediate) {
			var query = $(input).val().toLowerCase();
			if (query && query.length) {
				if (query.length >= settings.minChars) {
					if (immediate) {
						run_search(query);
					} else {
						clearTimeout(timeout);
						timeout = setTimeout(function() {
							run_search(query);
						}, settings.searchDelay);
					}
				}
			}
		}

		// Do the actual search
		function run_search(query) {
			var queryStringDelimiter = settings.url.indexOf("?") < 0 ? "?"
					: "&";
			var callback = function(results) {
				if ($.isFunction(settings.onResult)) {
					results = settings.onResult.call(this, results);
				}
				populate_suggestion_box(results);
			};

			if (settings.method == "POST") {
				$.post(settings.url + queryStringDelimiter
						+ settings.queryParam + "=" + query, {}, callback,
						settings.contentType);
			} else {
				$.get(settings.url + queryStringDelimiter + settings.queryParam
						+ "=" + query, {}, callback, settings.contentType);
			}
		}

		function populate_suggestion_box(results) {
			if (results && results.length > 0) {
				show_target();
				target_box.empty();

				if (settings.target_box_title != undefined) {
					var title = $('<h3>');
					title.addClass('suggestBox-box-title');
					title.html(settings.target_box_title);
					target_box.append(title);
				}

				var resultList = $('<ul>');
				$.each(results, function(index, item) {
					var resultItem = $('<li>');
					var itemValue = item.name;
					if(item.url != undefined){
						itemValue=$('<a>');
						itemValue.text(item.name);
						itemValue.attr('href',item.url);
					}
					resultItem.append(itemValue);
					resultList.append(resultItem);
				});
				target_box.append(resultList);
			}
			else{
				hide_target();
			}
		}

		function setup_target_box() {

			target_box = $("#"
					+ (settings.target_box || ($(input).attr("id") + "-suggestBox-target")));

			if (target_box.length == 0) {
				target_box = $('<div>');
				target_box.addClass('suggestBox-box');
				target_box.hide();
				$(input).parent().append(target_box);
			}
			target_box.addClass(settings.target_box_style);
		}

		function hide_target() {
			target_box.hide();
		}

		function show_target() {
			target_box.show();
		}

	}
})(jQuery);