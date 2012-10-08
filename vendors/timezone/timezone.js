
function libforms_timezone_showlocal(selector,label){
	var localdate = new Date();
	$(selector+"> small").each(function(){
		if($(selector).attr("data-time")!=localdate.getTimezoneOffset()){
			$(this).text("- "+date("h:i:a",$(this).text())+" "+label);			
		}
		else{
			$(this).text("");
			$(selector).parent().removeClass("localtime");
		}
	});
}
