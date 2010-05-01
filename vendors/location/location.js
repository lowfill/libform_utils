/**
*
*/
//TODO Cache loaded info
//FIXME Pass this information to the countries file
function getCountryInfo(name,target,value){
	default_lang = 'en';
	$.ajax({
		url: 'http://ws.geonames.org/countryInfoJSON',
		dataType:'jsonp',
		data: {
			country: name,
			lang: default_lang
		},
		success: function(response) {
			// ws returns an error message
			if (response.status) {
				$("#alert").html(response.status.message+' ('+response.status.value+')').show();
			}
			// ws returns an array of data
			if (response.geonames && response.geonames.length) {
				var code = response.geonames[0].geonameId;
				loadStates(code,target,value);
			}
		},
		error: function() {
			// error handling goes here
			$("#alert").html('ws timeout').show();
			return false;
		}
	});
}

function loadStates(code,target,value){
	default_lang = 'en';
	$.ajax({
		url: 'http://ws.geonames.org/childrenJSON',
		dataType:'jsonp',
		data: {
			geonameId: code,
			style: 'full',
			lang: default_lang
		},
		success: function(response) {
			// ws returns an error message
			if (response.status) {
				$("#alert").html(response.status.message+' ('+response.status.value+')').show();
			}
			// ws returns an array of data
			if (response.geonames && response.geonames.length) {
				target.empty();
				target.append("<option value=\"\">"+CHOOSE_ONE+"</option>");
				$.each(response.geonames, function() {
					selected = "";
					if(value==this.adminName1){
						selected="selected=\"selected\"";
					}
					var vname = (this.adminName1)?this.adminName1:this.name;
					target.append("<option "+selected+" value=\""+vname+"\" class=\""+this.geonameId+"\">"+vname+"</option>");
				});
			}
		},
		error: function() {
			// error handling goes here
			$("#alert").html('ws timeout').show();
		}
	});
}