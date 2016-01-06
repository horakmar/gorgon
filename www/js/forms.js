function addRow(obj, type){
	var startTR = $(obj).closest(".Ccont");
	var newTR = $(".Ctemplate").clone(true).insertBefore(startTR);
	$(newTR).removeClass("Ctemplate");

	if(type == 'cp'){
		var Num = 0;
		$(".Ctemplate").nextAll().each(function(){
			$(this).find(".CPlabel").text("K" + ++Num);
		});
	}
}
function removeRow(obj, type){
	var startTR = $(obj).closest(".Ccont");
	var label = $(startTR).children().first().text();
	if(type == 'cp'){
		$(startTR).nextAll().each(function(){
			var labelObj = $(this).find(".CPlabel");
			var newlabel = $(labelObj).text();
			$(labelObj).text(label);
			label = newlabel;
		});
	}
    $(startTR).remove();
}
function setEnabled(obj){
	var container = $(obj).closest(".Ccont");
//	$("#debug").text($(obj).val());
	switch($(obj).val()){
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
	default : // regular
		$(container).find("[name='cpsect[]']").prop('disabled',true);
		$(container).find("[name='cpchange[]']").prop('disabled',false);
		$(container).find("[name='cpdata[]']").prop('disabled',true);
		break;
	}
}
function setEnabledAll(){
	$('[name="cptype[]"]').each(function(){
		setEnabled(this);
	});
}
function unDisable(){
	$('[disabled]').prop('disabled', false);
	return true;
}
$(document).ready(function(){
    $(".removeCP").click(function(){
		removeRow(this, 'cp');
	});
    $(".addCP").click(function(){
		addRow(this, 'cp');
	});
    $(".removeCat").click(function(){
		removeRow(this, 'cat');
	});
    $(".addCat").click(function(){
		addRow(this, 'cat');
	});
	setEnabledAll();
});
