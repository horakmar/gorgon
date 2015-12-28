function addRow(obj){
	var startTR = $(obj).closest(".CPcont");
	var newTR = $(".CPtemplate").clone(true).insertBefore(startTR);
	$(newTR).removeClass("CPtemplate").addClass("CPcont");

	var Num = 0;
	$(".CPtemplate").nextAll().each(function(){
		$(this).find(".CPlabel").text("K" + ++Num);
	});
}
function removeRow(obj){
	var startTR = $(obj).closest(".CPcont");
	var label = $(startTR).children().first().text();
	$(startTR).nextAll().each(function(){
		var labelObj = $(this).find(".CPlabel");
		var newlabel = $(labelObj).text();
		$(labelObj).text(label);
		label = newlabel;
	});
    $(startTR).remove();
}
function setEnabled(obj){
	var container = $(obj).closest(".CPcont");
//	$("#debug").text($(obj).val());
	switch($(obj).val()){
	case "regular" :
		$(container).find("[name='cpsect[]']").prop('disabled',true);
		$(container).find("[name='cpchange[]']").prop('disabled',false);
		$(container).find("[name='cpdata[]']").prop('disabled',true);
		break;
	case "freeo" :
		$(container).find("[name='cpsect[]']").prop('disabled',false);
		$(container).find("[name='cpchange[]']").prop('disabled',false);
		$(container).find("[name='cpdata[]']").prop('disabled',true);
		break;
	case "bonus" :
		$(container).find("[name='cpsect[]']").prop('disabled',false);
		$(container).find("[name='cpchange[]']").prop('disabled',false);
		$(container).find("[name='cpdata[]']").prop('disabled',false);
		break;
	};
}
function unDisable(){
	$('[disabled]').prop('disabled', false);
	return true;
}
$(document).ready(function(){
    $(".removeCP").click(function(){
		removeRow(this);
	});
    $(".addCP").click(function(){
		addRow(this);
	});
});
