/**
 * @author zefiryn
 */

$(document).ready(function(){
	
	if ($("#regulation").length)
		sortElements('#regulation', "/regulation/sort/", function(){sortNumbers("&sect; ", "");});
	
	if ($("#faq_l, #faq_r").length)
		sortElements('#faq_l, #faq_r', "/faq/sort/", function(id){sortNumbers("", ". ");reposition(id);});
	
});

function test(){
	alert('test');
}

function sortElements(id, link, sortCallback)
{	
	$( id ).sortable({
		placeholder: "ui-state-highlight",
		connectWith: ".connected",
		start: function(event, ui) {
			 $('.ui-state-highlight').height(ui.helper.height());
		 },
		update: function(event, ui) {
			sort(event, ui, link, sortCallback);
		}
	});
	
	$( id ).disableSelection();
}

function sort(event, ui, link, sortCallback)
{
	//moved paragraph
	var moveId = ui.item.attr('id');
	moveId = moveId.substring(moveId.indexOf('_')+1);
	
	//new position
	if ($("#item_"+moveId).prev().length != 0)
	{
		var newPrev = $("#item_"+moveId).prev().attr('id');
		newPrev = newPrev.substring(newPrev.indexOf('_')+1);
	}
	else
		newPrev = 0;
	
	var url = link+moveId+"/"+newPrev;
	
	sortCallback(moveId);
	
	jQuery.ajax({
        type: "GET",
        url: url,
        global: false,
        success: function(){}, 
        error: function(){
        	alert('An error occurred');
        }
	});
}

function sortNumbers(prev, post)
{
	$(".regulation_number, .position").each(function(index){
		i = index + 1;
		$(this).html(prev+i+post);
	});	
}

function reposition(id){
	
}