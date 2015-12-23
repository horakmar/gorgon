function addRow(obj, id){
	sel = "." + id + "cont";
	startTR = $(obj).closest(sel);

	newTR = $(startTR).clone(true).insertBefore(startTR);
	$(newTR).find(".IDcode").val('');

	Label = $(startTR).children().first().text();
	LabPref = Label.slice(0,1);
	Num = Number(Label.slice(1));

//	$("#debug").text("addRow, " + sel + ", " + Num);
	
	$(newTR).nextAll().each(function(){
		$(this).find(".IDlabel").text(LabPref + ++Num);
	});
}
function removeRow(obj, id){
	startTR = $(obj).closest("." + id + "cont");
	label = $(startTR).children().first().text();
	$(startTR).nextAll().each(function(){
		labelObj = $(this).find(".IDlabel");
		newlabel = $(labelObj).text();
		$(labelObj).text(label);
		label = newlabel;
	});
    $(startTR).remove();
}
$(document).ready(function(){
    $(".removeCP").click(function(){
		removeRow(this, "CP");
	});
    $(".addCP").click(function(){
		addRow(this, "CP");
	});
    $(".removeB").click(function(){
		removeRow(this, "B");
	});
    $(".addB").click(function(){
		addRow(this, "B");
	});
}); 
