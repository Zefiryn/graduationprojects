/**
 * @author zefiryn
 */

$(document).ready(function(){
	
	if ($("#regulation").length)
		sortElements('#left, #right', "/regulation/sort/", function(){sortNumbers($("#regulation"), "&sect; ", "");});
	
	if ($("#faq").length)
		sortElements('#left, #right', "/faq/sort/", function(id){sortNumbers($("#faq"), "", ". ");});
	
	if ($("#diploma_gallery").length)
	{
		var id = $('.dyplom').attr('id');
		sortColumnElements('#diploma_gallery', "/diploma/" + id + "/sort/");
	}
	
	if ($('.caption').length)
	{
		showCaptions();
	}
});

function showCaptions()
{
	
	$('.caption h3').click(function(){
		$(this).siblings('p').toggle();//slideToggle('slow');
	});
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
			sortElements(id, link, sortCallback);
		}
	});
	
	$( id ).disableSelection();
}

function sortColumnElements(id, link)
{	
	$( id ).sortable({
		placeholder: "ui-state-highlight",
		start: function(event, ui) {
			 $('.ui-state-highlight').height(ui.helper.height());
		 },
		update: function(event, ui) {
			sortDiplomaImages(event, ui, link);
			//sortColumnElements(id, link);
		}
	});
	
	$( id ).disableSelection();
}

function sortDiplomaImages(event, ui, link)
{
	var id = ui.item.attr('id');
	console.log(id);
	var position = $('.sort_item').index($('#'+id)) + 1;
	console.log(position);
	var  url = link + id + "/" + position;
	console.log(url);
	
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

function sort(event, ui, link, sortCallback)
{
	sortCallback();
	
	//moved paragraph
	var elemId = ui.item.attr('id').substring(ui.item.attr('id').indexOf('_')+1);
	
	var elem = $('#'+ui.item.attr('id'));
	var newPos = $('.sort_item').index(elem) + 1;
	
	var url = link+elemId+"/"+newPos;
	
	console.log(url);
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

function sortNumbers(elem, prev, post)
{
	var columnLength = Math.ceil($(".regulation_number, .position").length/2);
	
	var newList = elem.clone();
	var leftColumn = $('#left').clone().html("");
	var rightColumn = $('#right').clone().html("");
	
	newList.html("");
	
	$(".sort_item").each(function(index, elem){
		
		if (index < columnLength)
			leftColumn.append(elem);
		else
			rightColumn.append(elem);

	});
	
	
	newList.append(leftColumn).append(rightColumn);
	elem.replaceWith(newList);
	
	$(".regulation_number, .position").each(function(index){
		var id = index + 1; 
		$(this).html(prev+id+post);
	});
}