/**
 * @author zefiryn
 */

$(document).ready(function(){
	
	if ($("#regulation").length)
	{
		sortElements('#left, #right', "/regulation/sort/", function(){sortNumbers($("#regulation"), "&sect; ", "");}, true);
	}
	if ($("#faq").length)
		sortElements('#left, #right', "/faq/sort/", function(id){sortNumbers($("#faq"), "", ". ");}, true);
	
	if ($("#diploma_gallery").length)
	{
		var id = $('.dyplom').attr('id');
		sortColumnElements('#diploma_gallery', "/diploma/" + id + "/sort/");
	}
	
	if ($('.caption').length)
	{
		showCaptions();
	}
	
	if ($('.admin_button').length)
	{
		$('.ui-icon-check').click(function(){
			var self = $(this);
			var id = self.attr('id').substring(6);
			var checkbox = $('#'+id);
			checkbox.attr('checked', !checkbox.attr('checked'));
			self.toggleClass('ui-icon-checked');
		});
		
	}
	
	if ($('p.votes').length)
	{
		voting();
		 
	}
	if ($('#stageSelectForm').length)
	{
		stageSelect();
	}
	
	if ($('#filterForm').length)
	{
		filterForm();
	}
	if ($('.stage_choice').length)
	{
		changeStage();
	}
});

function showCaptions()
{
	
	$('.caption h3').click(function(){
		$(this).siblings('p').toggle();//slideToggle('slow');
	});
}


function sortElements(id, link, sortCallback, disable)
{	
	$( id ).sortable({
		placeholder: "ui-state-highlight",
		connectWith: ".connected",
		disabled: disable,
		start: function(event, ui) {
			 $('.ui-state-highlight').height(ui.helper.height());
		 },
		stop: function (event, ui) {
			
		},
		update: function(event, ui) {
			sort(event, ui, link, sortCallback);
			sortElements(id, link, sortCallback);
		}
	});
	
	//$( id ).disableSelection();
	
	if (disable) 
	{
		$('.enable-sort').mousedown(function(){
			
			$( id ).sortable({
				disabled: false,
			});
		});
		
		$('.enable-sort').mouseup(function(){
			
			$( id ).sortable({
				disabled: true,
			});
		});
		
	}
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

function stageSelect()
{
	$('#stageSelect').change(function(){
		$('#stageSelectForm').submit();}
	);
}

function voting()
{
	$('p.votes span.vote, p.votes span.admin_vote').live('click', function(){
		var self = $(this);
		
		if (self.hasClass('vote'))
			var mainClass = 'vote';
		else
			var mainClass = 'admin_vote';
		
		var data = {
			juror_id: self.data('juror-id'),
			stage_id: self.data('stage-id'),
			application_id: self.data('application-id'),
			vote: self.data('vote'),
		};
		var loader = $('#loader').clone().addClass('cloned');
		
		console.log(data);
		$.ajax({
			url: '/application/vote',
			data: data,
			type: 'POST',
			dataType: 'json',
			beforeSend: function(){
				loader.css('display', 'inline-block');
				self.parent().append(loader);
			},
			error: function(data){
				console.log(data);
				alert(data.error);
				loader.remove();
			},
			success: function(data){
				console.log(data);
				var vote = data.success;
				console.log(vote);
				self.parent().find('span.voted').removeClass().addClass(mainClass);
				self.parent().find('span[data-vote=' + vote + ']').addClass('voted voted_' + vote);
				var filtered = $('#filter').val();
				loader.remove();
				if (filtered != -2)
				{
					if (filtered != vote) self.closest('tr').hide();
				}
				
			},
		});
		
	});
}

function filterForm()
{
	$('#filter').change(function(){
		
		val = $(this).val();
		console.log('Selected ' + val);
		if (val == -2) 
		{
			$('tr.applicationData').show();
		}
		else if (val == -1)
		{
			$('tr.applicationData').show();
			$('.voted').each(function(){
				$(this).closest('tr').hide();
			})
		}
		else
		{
			$('tr.applicationData').hide();
			$('.voted').each(function(){
				var self = $(this);
				if (self.data('vote') == val)
				{
					self.closest('tr').show();
				} 
			});
		}
		
		
	});
}

function changeStage()
{
	$('.stage_choice').live('click', function(){
		var self = $(this);
		var votebox = $('div.vote_box');
		var application_id = votebox.data('application-id');
		var stage = self.data('stage');
		var loader = $('#loader').clone().addClass('cloned');
		
		$.ajax({
			url: '/applications/getstage',
			data: {'id': application_id, 'stage': stage},
			type: 'GET',
			beforeSend: function(){
				loader.css('display', 'inline-block');
				self.parent().append(loader);
			},
			error: function(data){
				console.log(data);
				alert(data.error);
				loader.remove();
			},
			success: function(data){
				console.log(data);
				votebox.replaceWith(data);
				
			},
		});
		
	});
}