/*
 * SelSo 1.0 - Client-side selection sorter
 * Version 1.0
 *
 * Copyright (c) 2007 Guillaume Andrieu
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */

/**
 *
 * @description Takes a selection, for example some li elements in a ul,
 * and sorts them according to the value of some classed element
 * they all share. The elements of the selection must have an id attribute.
 *
 * @example $('li').selso({type:'num' , orderBy:'span.value'});
 * @desc Will sort all the li's elements in their respective ul, according to
 * the numerical value in the span.value contained in the li.
 *
 * @example $('li').selso({type:'num' , orderBy:'span.value',direction:'desc'});
 * @desc Will sort all the li's elements in their respective ul, according to
 * the numerical value in the span.value contained in the li, in descending order.
 *
 * @example $('li').selso({type:'alpha' , extract:function(obj){ return $(obj).attr('id'); }});
 * @desc Will sort all the li's elements in their respective ul, according to
 * the result of the extract function, here giving the id attribute of each li.
 *
 *
 * @example $('#table').tablesorter({ headers: { 0: { sorter: false}, 1: {sorter: false} } });
 * @desc Create a tablesorter interface and disableing the first and secound column headers.
 *
 * @example $('#table').tablesorter({ 0: {sorter:"integer"}, 1: {sorter:"currency"} });
 * @desc Create a tablesorter interface and set a column parser for the first and secound column.
 *
 *
 * @param Object settings An object literal containing key/value pairs to provide optional settings.
 *
 * @option String type (optional) 			A String giving the type of ordering : for the moment, only 'alpha' or 'num'
 * 												Default value: "alpha"
 *
 * @option String orderBy (optional) 			A String representing a jQuery selector. This selector will be applied inside each selected element.
 * 												Default value: "span.value"
 *
 * @option String direction (optional) 			A string to know in which order to sort : 'asc'ending or descending.
 * 												'asc' means ascending, anything else means descending
 * 												Default value: "asc"
 *
 * @option Function extract (optional) 	A Function taking an element, and sending back the value given to that object in the ordering process.
 * 												Default value: none
 *
 * @option Function orderFn (optional) 	A function that will be given as the parameter of the .sort() function.
 * 												Its parameters are two objects of type {id:'...',val:'...'}, and returns a number,
 * 												positive if the first object is greater,
 * 												negative is the second is greater,
 * 												0 otherwise.
 * 												Default value: none
 * @type jQuery
 *
 * @name selso
 *
 * @cat Plugins/Selso
 *
 * @author Guillaume Andrieu/subtenante@yahoo.fr
 */

(function($) {

	$.extend({
		selso:{

			defaults:{
				type:'alpha',               // type of sorting : alpha, num, date, ip, ...
				orderBy:'span.value',       // selector of the elements containing the value to order by
				direction:'asc'
			},

			extractVal: function(type,text){
				if (type=='num'){
					return 1*text;
				}
				return text;
			},

			accentsTidy: function(s){
				var r=s.toLowerCase();
				r = r.replace(new RegExp(/\s/g),"");
				r = r.replace(new RegExp(/[àáâãäå]/g),"a");
				r = r.replace(new RegExp(/æ/g),"ae");
				r = r.replace(new RegExp(/ç/g),"c");
				r = r.replace(new RegExp(/[èéêë]/g),"e");
				r = r.replace(new RegExp(/[ìíîï]/g),"i");
				r = r.replace(new RegExp(/ñ/g),"n");
				r = r.replace(new RegExp(/[òóôõö]/g),"o");
				r = r.replace(new RegExp(/œ/g),"oe");
				r = r.replace(new RegExp(/[ùúûü]/g),"u");
				r = r.replace(new RegExp(/[ýÿ]/g),"y");
				r = r.replace(new RegExp(/\W/g),"");
				return r;
			},

			orderAlpha: function(a,b){
				var l = Math.min(a.length,b.length);
				for (var i=0;i<l;i++){
					if (a.charAt(i)<b.charAt(i)) return -1;
					else if (a.charAt(i)>b.charAt(i)) return 1;
				}
				if (a.length>b.length) return 1;
				if (a.length<b.length) return -1;
				return 0;
			},

			alphaGreaterThan: function(s1,s2){
				return this.orderAlpha(s1,s2);
			},

			stablesort: function(a,of){
				var r = a;
				var s;
				for (var i=1;i<r.length;i++){
					var j=1;
					while(i>=j && of(r[i-j],r[i])>0) {j++;}
					if (j>0){
						s = r.slice(0,i-j+1);
						s.push(r[i]);
						s = s.concat(r.slice(i-j+1,i));
						if (i<r.length-1) { s = s.concat(r.slice(i+1)); }
						r = s;}}
				return r;
			}
		}
	});

	$.fn.extend({

		outhtml: function() {
			if (this.length)
				return $('<div>').append($(this[0]).clone()).html();
			return null;
		},

		prependToParent : function(){
			return this.each(function(){
				$(this).parent().prepend($(this).remove());
			});
		},

		selso: function(settings) {

			var tt = settings.type || $.selso.defaults.type;
			var to = settings.orderBy || $.selso.defaults.orderBy;
			var td = settings.direction || $.selso.defaults.direction;
			var te = settings.extract; // function that reads and parses the value in the selected element, if setted, orderBy will be ignored
			var of = settings.orderFn; // of : ordering function

			if (!$.isFunction(te)){
				te = function(obj){
					return $.selso.extractVal(tt,$(to,obj).text());
				};
			}

			var arr = [];
			this.each(function(){
				arr.unshift({
					id:this.id,
					val:te(this)
				});
			});

			// Setting the ordering function if not given in the settings
			if ($.isFunction(of)){
			}
			else if (tt=='num'){
				of = function(a,b){return a.val-b.val;};
			}
			else { // alpha by default...
				if (tt=='accents') {
					$.map(arr,function(n){n.val = $.selso.accentsTidy(n.val);});}
				of = function(a,b){return $.selso.alphaGreaterThan(a.val,b.val);};
			}
			var off=of;
			if(td=='asc'){off = function(a,b){return -1*of(a,b);}}
			arr = $.selso.stablesort(arr,off);

			for (var i=0;i<arr.length;i++){
				$('#'+arr[i].id).prependToParent();
			}

			return this;

		}
	});

})(jQuery);

// jQuery comboselect plugin
// version 1.0.2
// (c)2008 Jason Huck
// http://devblog.jasonhuck.com/
//
// Transforms a single select element into a pair of multi-selects
// with controls to move items left to right and vice versa. Keeps
// items sorted alphabetically in both lists (if desired). Selected
// items are submitted by the original form element. Double-clicking
// moves an item from one side to the other.
//
// Written against jQuery 1.2.3, but older versions may work.
//
// Requires the jQuery Selso plugin:
// http://plugins.jquery.com/project/selso
//
// Usage: $('#myselect').comboselect({
// 		sort: [string,'none'|'left'|'right'|default:'both'],	// which sides to sort
// 		addbtn: [string,default:' &gt; '], 						// label for the "add" button
// 		rembtn: [string,default:' &lt; ']						// label for the "remove" button
// });
//
// Version History
// 1.0.2	Now works correctly if the form is not the immediate parent of the select.
//			Clears originally selected options before updating with user's new selections on submit.
//			Correctly transforms selects whose options were added dynamically.
// 1.0.1	Correctly transforms inputs which already had options selected.
// 1.0.0	Initial release.


(function($){
	jQuery.fn.comboselect = function(settings){
		settings = jQuery.extend({
			sort: 'both',		// which sides to sort: none, left, right, or both
			addbtn: ' &gt; ',	// text of the "add" button
			rembtn: ' &lt; '	// text of the "remove" button
		}, settings);

		this.each(function(){
			// the id of the original element
			var selectID = this.id;

			// ids for the left and right sides
			// of the combo box we're creating
			var leftID = selectID + '_left';
			var rightID = selectID + '_right';

			// the form which contains the original element
			var theForm = $(this).parents('form');

			// place to store markup for the combo box
			var combo = '';

			// copy of the options from the original element
			// var opts = $(this).children().clone();
			var opts = $(this).find('option').clone();

			// add an ID to each option for the sorting plugin
			opts.each(function(){
				$(this).attr('id', $(this).attr('value'));
			});

			// build the combo box
			combo += '<fieldset class="comboselect">';
			combo += '<select id="' + leftID + '" name="' + leftID + '" class="csleft" multiple="multiple">';
			combo += '</select>';
			combo += '<fieldset>';
			combo += '<input type="button" class="csadd" value="' + settings.addbtn + '" />';
			combo += '<input type="button" class="csremove" value="' + settings.rembtn + '" />';
			combo += '</fieldset>';
			combo += '<select id="' + rightID + '" name="' + rightID + '" class="csright" multiple="multiple">';
			combo += '</select>';
			combo += '</fieldset>';

			// hide the original element and
			// add the combo box after it
			$(this).hide().after(combo);

			// find the combo box in the DOM and append
			// a copy of the options from the original
			// element to the left side
			theForm.find('#' + leftID).append(opts);

			// bind a submit event to the enclosing form
			theForm.submit(function(){
				// clear the original form element of selected options
				$('#' + selectID).find('option:selected').removeAttr('selected');

				// look at each option element
				// from the right side...
				$('#' + rightID).find('option').each(function(){
					// select the corresponding option
					// from the original element
					var v = $(this).attr('value');
					$('#' + selectID).find('option[value="' + v + '"]').attr('selected','selected');
				});

				return true;
			});
		});

		// double-click moves an item to the other list
		$('select.csleft').dblclick(function(){
			$(this).parent().find('fieldset input.csadd').click();
		});

		$('select.csright').dblclick(function(){
			$(this).parent().find('fieldset input.csremove').click();
		});

		// add/remove buttons
		$('input.csadd').click(function(){
			var left = $(this).parent().parent().find('select.csleft');
			var leftOpts = $(this).parent().parent().find('select.csleft option:selected');
			var right = $(this).parent().parent().find('select.csright');
			right.append(leftOpts);
			sortBoxes(left.attr('id'), right.attr('id'));
		});

		$('input.csremove').click(function(){
			var left = $(this).parent().parent().find('select.csleft');
			var right = $(this).parent().parent().find('select.csright');
			var rightOpts = $(this).parent().parent().find('select.csright option:selected');
			left.append(rightOpts);
			sortBoxes(left.attr('id'), right.attr('id'));
		});

		// sort the boxes and clear highlighted items
		function sortBoxes(leftID, rightID){
			switch(settings.sort){
				case 'none': var toSort = null;
				case 'left': var toSort = $('#' + leftID); break;
				case 'right': var toSort = $('#' + rightID); break;
				default: var toSort = $('#' + leftID + ', #' + rightID);
			}

			if(settings.sort != 'none'){
				toSort.find('option').selso({
					type: 'alpha',
					extract: function(o){ return $(o).text(); }
				});
			}

			// clear highlights
			$('#' + leftID + ', #' + rightID).find('option:selected').removeAttr('selected');
		}

		// add any items that were already selected
		$('input.csadd').click();

		return this;
	};
})(jQuery);