/**
 * @author zefiryn
 */

//live draggable
(function ($) {
    $.fn.liveDraggable = function (opts) {
        this.live("mousemove", function() {
            $(this).draggable(opts);
        });
    };
}(jQuery));

$(document).ready(function(){
	
	if ($("#regulation").length > 0)
	{
		sortElements('#left, #right', "/regulation/sort/", function(){sortNumbers($("#regulation"), "&sect; ", "");}, true);
	}
	
	if ($("#faq").length > 0)
	{
		sortElements('#left, #right', "/faq/sort/", function(id){sortNumbers($("#faq"), "", ". ");}, true);
	}
	
	if ($("#diploma_gallery").length > 0)
	{
		sortColumnElements('#diploma_gallery', function(event, ui){sortDiplomaImages(event, ui, "/diploma/sort/");}, false);
	}
	
	if ($("#newsFiles").length > 0)
	{
		sortColumnElements('#newsFiles', function(event, ui){sortNewsFiles(event,ui,"/news/sort");});
	}
	
	if ($("#press_files").length > 0)
	{
		sortColumnElements('#press_files', function(event, ui){sortPressFiles(event, ui, "/press/sort/");}, false);
	}
	
	if ($('.caption').length > 0)
	{
		showCaptions();
	}
	
	if ($('.admin_button').length > 0)
	{
		$('.ui-icon-check.click').click(function(){
			var self = $(this);
			var id = self.attr('id').substring(6);
			var checkbox = $('#'+id);
			checkbox.attr('checked', !checkbox.attr('checked'));
			self.toggleClass('ui-icon-checked');
		});
		
	}
	
	if ($('p.votes').length > 0)
	{
		voting();		 
	}
	if ($('.stage_choice').length > 0)
	{
		changeStage();
	}
	if ($('#stageSelect').length > 0)
	{
		filterForm();
	}
	if ($('.dispute').length > 0)
	{
		bindSpanHint();
	}
	
	if ($('div.caption'))
	{
		editTranslation();
	}
	
	if ($('a.publish_results').length > 0) 
	{
		publish_edition();
	}
	
	if ($('a.press_file').length > 0) 
	{
		press_form();
	}
	voteSettings();
});

function showCaptions()
{
	//show only the one that has no translation
	$('.caption p').hide();
	$('span.translation_missing').closest('p').show();
	
	$('.caption h3 span').click(function(){
		var elem =$(this).parent();
		var par = elem.siblings('p');
		var visible = elem.siblings('p:visible');
		
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

function sortColumnElements(id, callback, disable)
{	
	$( id ).sortable({
		placeholder: "ui-state-highlight",
		start: function(event, ui) {
			console.log($('.ui-state-highlight'));
			 $('.ui-state-highlight').height(ui.helper.height());
		 },
		update: function(event, ui) {
			callback(event, ui);
		}
	});
	
	if (typeof disable == "undefined") {
	    disable = true;
	  }

	if (disable) {
		$( id ).disableSelection();
	}
	else {
		$( id ).sortable({
			disabled: true,
		});
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

function sortDiplomaImages(event, ui, link)
{
	var diplomaId = $('.dyplom').attr('id');
	var fileId = ui.item.attr('id');
	var position = $('.sort_item').index($('#'+fileId)) + 1;
	
	jQuery.ajax({
        type: "POST",
        url: link,
        data: {'id': diplomaId, 'file_id': fileId, 'position': position},
        global: false,
        beforeSend: function(){
        	console.log(link);
        	console.log(this.data);
        },
        success: function(){}, 
        error: function(data){
        	console.log(data);
        	alert('An error occurred');
        }
	});
}

function sortNewsFiles(event, ui, link)
{
	var newsId = $('#newsFiles').data('news-id');
	var file = ui.item.attr('id');
	var position = $('.news_files').index($('#'+file)) + 1;
	var fileId = $('#'+file).data('file-id');
	
	var data = {'news_id': newsId, 'file_id': fileId, 'position': position};
	console.log(data);
	jQuery.ajax({
        type: "post",
        url: '/news/sort',
        data: data,
        dataType: 'json',
        success: function(data){
        	console.log(data);
        }, 
        error: function(data){
        	console.log(data);
        	alert('An error occurred');
        }
	});
}

function sortPressFiles(event, ui, link)
{
	var id = ui.item.attr('id');
	var position = $('.sort_item').index($('#'+id)) + 1;
	
	jQuery.ajax({
        type: "POST",
        url: link,
        data: {'id': id, 'position': position},
        global: false,
        beforeSend: function(){
        	console.log(link);
        	console.log(this.data);
        },
        success: function(){}, 
        error: function(data){
        	console.log(data);
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
					
					loader.remove();
					
					var tr = self.closest('tr');
					tr.attr('data-application-score', data.appScore);  
					if (tr.attr('data-juror-grade') != null) 
					{
						tr.attr('data-juror-grade', data.grade);
					}
				}
				else if (data.error)
				{
					alert(data.error);
					loader.remove();
				}
				else
				{
					loader.remove();
					alert('Unknown error occured');
				}
					
			},
		});
		
	});
}

function filterForm()
{
	$('#filter_options').click(function(){
		$('form.filter_options').slideToggle('slow');
	});
	
	$('.selectionOption ul li').click(function(){
		var self = $(this);
		self.parent().children('li').removeClass('checked');
		self.addClass('checked');
		if (self.attr('id') == 'filter_range') 
		{
			$('#rangeSelect').show();
		}
		else 
		{
			$('#rangeSelect').hide();
		}
	});
	
	$('#filter').click(function(e){
		var self = $(this);
		e.preventDefault();
		var data = {
				'stage' : $('#stageSelect li.checked').data('stage'),
				'sort' : $('#sortSelect li.checked').data('sort'),
				'sort_order': $('#sortOrder li.checked').data('sort-order'),
				'filter' : $('#filterSelect li.checked').data('filter'),
				'rangeStart' : $('#rangeStart').val(),
				'rangeEnd' : $('#rangeEnd').val(),
				};
		
		console.log(data);
		var loader = $('#loader').clone().addClass('cloned');
		$.ajax({
			'url': '/applications/index',
			'data': data,
			'type': 'GET',
			'dataType': 'html',
			'beforeSend': function(){
				loader.css('display', 'inline-block');
				self.parent().append(loader);
			},
			error: function(data){
				console.log(data);
				alert(data);
				loader.remove();
			},
			success: function(data){
				loader.remove();
				document.querySelector('div.data').innerHTML = '';
				document.querySelector('div.data').innerHTML = data;
				applicationFilesFancyBox();
			}});
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
		//console.log(existingTranslation);
		if (existingTranslation  != 'empty')
		{
			$('#text').val(existingTranslation);
		}		
		
		//cancel
		$('#leave').click(function(){
			parent.html(parentClone);
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
					console.log(this.url);
				},
				'error': function(data){
					console.log(data);
					alert('There was an error while performing the request');
					parent.html(parentClone);
				},
				'success': function(data){
					console.log(data);
					parentClone.find('span').removeClass().css('color', '#000').html(data.translation);
					parent.html(parentClone);
				}
			});
		});
	});
}

function voteSettings()
{
	$('div.juror_box span.remove_user').live('click', function(){
		
		var self = $(this);
		var clone = self.closest('div.juror_user_box').clone();
		var juror_div = self.closest('div.juror_box');
		var userbox = self.closest('div.juror_user_box');
		
		//make ajax call to reset juror assotiation
		var data = userbox.data();
		var baseUrl = data.url;
		
		$.ajax({
			'url': baseUrl + 'users/reset-juror',
			'data': {'user_id': data.userId},
			'type': 'POST',
			'dataType': 'json',
			'error': function(data) {
				console.log(data);
			},
			'success' : function(data) {
				if (data.success)
				{
					userbox.remove();
					$('#unassigned_jurors').append(clone);
				}
			}
		});
		
	});
	
	$('div.juror_user_box').draggable({
		revert: "invalid",
		cursor: "move", 
		cursorAt: { top: 5 },
		scroll: true,
		zIndex: 100,
		stack: '.juror_box',
		helper: 'clone',
		appendTo: 'body',
		start: function(event, ui) {
			$(this).addClass('drag-start');
		}
	});
	$('div.juror_box div.juror_data').droppable({
		activeClass: "juror_box_area",
		hoverClass: "juror_box_area_hover",
		accept: '.juror_user_box',
		helper: 'clone',
		activate: function(event, ui) {
			//$('#unassigned_jurors').append(ui.helper);
		},
		drop: function(event, ui){
			addUserToJuror(ui.draggable, $(this));			
		}
	});
}

function addUserToJuror(user, juror)
{
	user.css({position: 'relative', top: 0, left: 0}).removeClass('ui-draggable');
	juror.children('.clear').before(user);
	
	var data = {'user_id' : user.data('user-id'), 'juror_id': juror.data('juror-id')};
	var baseUrl = user.data('url');
	$.ajax({
		'url': baseUrl + 'users/assign-juror',
		'data': data,
		'type': 'POST',
		'dataType': 'json',
		'error': function(data, textStatus) {
			console.log(data);
			alert('An error occured: ' + textStatus);
		},
		'success' : function(data) {
			if (data.fail) 
			{
				alert("An error occured: \n" + data.fail);
				$('#unassigned_jurors').append(user);
			}
			console.log(data);
		}
	});
}

function publish_edition()
{
	$('a.publish_results').click(function(e){
		var self = $(this);
		e.preventDefault();
		$.ajax({
			url : '/editions/publish',
			data : {'edition_id' : self.data('edition-id')},
			type: 'post',
			dataType: 'json',
			error: function(data) {
				console.log(data);
			},
			success: function(data) {
				console.log(data);
				$('img[class*="ui-icon-check"]').removeClass('ui-icon-check').addClass('ui-icon-print');
				self.find('img').removeClass('ui-icon-print').addClass('ui-icon-check');
			},
		});
	});
}

function press_form()
{
	$('p.press_file').delegate('a.press_file', 'click', function(e){
		e.preventDefault();
		var self = $(this);
		var number = $('div.press_file').length;
		var file = $('div.press_file:first').clone();
		var input = file.find('input[type="file"]');
		input.attr('id', 'element_path_' + number);
		input.attr('name', 'element_path_' + number);
		input.attr('value', null);
		$('div.press_file:last').after(file);
	});
}