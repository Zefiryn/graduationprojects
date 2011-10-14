/**
 * @author zefiryn
 */

$(document).ready(function(){
	
	regulationDragAndDrop();
	
});

function regulationDragAndDrop()
{	
	$( "#regulation" ).sortable({
		placeholder: "ui-state-highlight",
		update: function(event, ui) {
			sortRegualtion(event, ui);
		}
	});
	
	$( "#regulation" ).disableSelection();
}

function sortRegualtion(event, ui)
{
	//moved paragraph
	var paragraphId = ui.item.attr('id');
	paragraphId = paragraphId.substring(paragraphId.indexOf('_')+1);
	
	//new position
	if ($("#paragraph_"+paragraphId).prev().length != 0)
	{
		var newPrev = $("#paragraph_"+paragraphId).prev().attr('id');
		newPrev = newPrev.substring(newPrev.indexOf('_')+1);
	}
	else
		newPrev = 0;
	
	var link = "/regulation/sort/"+paragraphId+"/"+newPrev;
	
	sortNumbers();
	
	jQuery.ajax({
        type: "GET",
        url: link,
        global: false,
        success: function(){}, 
        error: function(){
        	alert('An error occurred');
        }
	});
}

function sortNumbers()
{
	$(".regulation_number").each(function(index){
		i = index + 1;
		$(this).html("&sect; "+i);
	});	
}