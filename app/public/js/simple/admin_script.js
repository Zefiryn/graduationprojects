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
	if ($('.dispute').length)
	{
		bindSpanHint();
	}
	
	if ($('div.caption'))
	{
		editTranslation();
	}
});

function showCaptions()
{
	//show only the one that has no translation
	$('.caption p').hide();
	$('span.translation_missing').closest('p').show();
	
	$('.caption h3').click(function(){
		var par = $(this).siblings('p');
		var visible = $(this).siblings('p:visible');
		
		if ((visible.length > 0 && visible.length < par.length) || visible.length == 0)
		{
			par.show();
		}
		else
		{
			par.hide();
		}
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
				
				if (data.success)
				{
					var vote = data.success;
					self.parent().find('span.voted').removeClass().addClass(mainClass);
					
					var lastVote = self.parent().find('span.'+ mainClass + ':last').data('vote');
					if (vote == 0)
						var voteClass = 'first';
					else if (vote == lastVote)
						var voteClass = 'last';
					else
						var voteClass = vote;
					
					var voteBox = self.parent().find('span[data-vote=' + vote + ']');
					voteBox.addClass('voted vote_' + voteClass);
					
					var filtered = $('#filter').val();
					loader.remove();
					if (filtered != -2)
					{
						if (filtered != vote) self.closest('tr').hide();
					}
				}
				else
				{
					
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
		var start = new Date().getTime();
		if (val == -3)
		{
			$('tr.applicationData').hide();
			$('div.dispute.info').closest('tr').show();
		}
		else if (val == -2) 
		{
			$('tr.applicationData').show();
		}
		else if (val == -1)
		{
			$('tr.applicationData').hide();
			$('tr.applicationData[data-juror-grade=""]').show();
		}
		else
		{	
			$('tr.applicationData').hide();
			$('tr.applicationData[data-juror-grade="'+ val +'"]').show();
		}
		console.log((new Date().getTime() - start)/1000);
		
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
			dataType: 'json',
			beforeSend: function(){
				loader.css('display', 'inline-block');
				self.parent().append(loader);
			},
			error: function(data){
				console.log(data);
				alert(data);
				loader.remove();
			},
			success: function(data){
				console.log(data);
				if (data.html)
				{
					votebox.replaceWith(data.html);
				}
				else 
				{
					alert('An error occured when receiving data');
				}
				
			},
		});
		
	});
}

function bindSpanHint(){
	
	$('span.hint').hide();
	$('div.dispute, a.dispute').live({
		mouseover: function(e){
			var self = $(this);
			var hint = self.children('.hint');
			hint.show();
		},
		mouseout: function(){
			var self = $(this);
			self.children('.hint').hide();
		},
		click: function(e) {
			e.preventDefault();
			var self = $(this);
			if (!self.hasClass('info'))
			{
				self.children('.hint').hide();
				var appId = self.data('application-id');
				var marked = self.data('marked');
				
				if (marked == 1) 
				{
					var url = '/applications/removedispute';
				}
				else
				{
					var url = '/applications/dispute';
				}
				var loader = $('#loader-white').clone().addClass('cloned');
				var span = self.children('span.dispute');
				$.ajax({
					'url': url,
					'data': {'id': appId},
					'type': 'POST',
					'dataType': 'json',
					'beforeSend': function(){
						loader.css('display', 'inline-block');
						span.html('').append(loader);
					},
					'error': function(data){
						console.log(data);
						alert(data.error);
						loader.remove();
					},
					'success': function(data){
						loader.remove();
						
						if (marked == 0)
						{
							span.html('&ndash;');
							self.data('marked', '1');
						}
						else
						{
							span.html('&plus;');
							self.data('marked', '0');
						}
						console.log(data);
						var row =$('tr[data-application-id="'+ data.succcess +'"]');
						console.log(row);
						if (data.dispute != '') {
							var bubble = row.find('div[class="dispute info"]');
							if (bubble.length != 0)
							{
								bubble.replaceWith(data.dispute);
							}
							else
							{
								row.find('td.work_subject').prepend(data.dispute);
							}
						}
						else {
							row.find('div[class="dispute info"]').remove();
						}
					},
				});
			}
		}
	});
}

function editTranslation()
{
	$('.edit_translation').live('click', function(e){
		e.preventDefault();
		
		var self = $(this);
		var data = {'loc_lang': self.data('lang'),
					'id': self.data('caption-id')};
		var parent = self.closest('p');
		var parentClone = parent.clone();
		var strong = parent.find('strong');
		var form = '<textarea name="text" id="text" class="width1 inline"></textarea><p class="submit center"><input type="submit" name="leave" id="leave" value="Cancel" class="submit unprefered"><input type="submit" name="submit" id="submit" value="Save" class="submit prefered"></p>';

		//show form
		parent.html(strong).append('<br />').append(form);
		
		//add existing text
		var existingTranslation = parentClone.children('span').text();
		console.log(existingTranslation);
		if (existingTranslation  != 'empty')
		{
			$('#text').val(existingTranslation);
		}
		
		
		//cancel
		$('#leave').click(function(){
			parent.replaceWith(parentClone);
		});
		
		//save form
		$('#submit').click(function(){
			
			var loader = $('#loader').clone().addClass('cloned');
			var url = '/localizations/' + data['loc_lang'] + '/edit/' + data['id'];
			data['text'] = $('#text').val();
			
			$.ajax({
				'url': url,
				'data': {'text': data['text']},
				'type': 'POST',
				'dataType': 'json',
				'beforeSend': function(){
					loader.css('display', 'inline-block');
					parent.children('strong').after(loader);
				},
				'error': function(data){
					console.log(data);
					alert(data.error);
					
				},
				'success': function(data){
					console.log(data);
					parentClone.find('span').removeClass().css('color', '#000').html(data.translation);
					parent.replaceWith(parentClone);
				}
			});
		});
	});
}