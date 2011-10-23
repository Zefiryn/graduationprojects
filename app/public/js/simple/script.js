/**
 * @author zefiryn
 */

//SCROLL BIND HANDLER
$(document).ready(function(){
	
	//flash messages
	flashMessage();
	
	if ($('#mail_text').length )
		letterCounter('mail_text', 'word_counter');
	
	if ($('#work_desc').length )
		letterCounter('work_desc', 'word_counter');
	
	hintImages();
	
	setDateFields();
	
	hideFileFields();
		
	if ($('a.form_file').length )
	{
		runFancyBox('a.form_file', true);
	}
	
	if ($('a.application').length )
	{
		runFancyBox('a.application', true);
	}
	
	if ($('a.files').length )
	{
		runFancyBox('a.files', true);
	}
	
	if ($('div.error-div').length)
		scrollToError();
	
	
	ajaxLinks();
	
	menuSlide();
	
	//dragAppFiles();
	deleteAppImage();
});

function deleteAppImage()
{
	$('.remove-image').click(function(e){
		e.preventDefault();
		var div = $(this).parent();
		var file = $(this).attr('alt');
		var cache = '#'+file+'-'+file+'Cache';
		var id = '#'+file+'-file_id';
		
		jQuery.ajax({
	        type: "GET",
	        url: $(this).attr('href'),
	        global: false,
	        success: function(){
	        	div.parent().parent().remove();
	        	$(cache).val("");
	    		$(id).val("");
	        }, 
	        error: function(){
	        	alert('An error occurred');
	        }
		});
	}
	);
}

function dragAppFiles()
{
	$( '#app_images').sortable({
		placeholder: "image_move",
		
		start: function(event, ui) {
			 $('.image_move').width(ui.helper.width());
			 $('.image_move').height(ui.helper.height());
		 },
		update: function(event, ui) {
			var clear = $('.clearfix:first').clone();
			$('.clearfix').remove();
			var images = $('#app_images').clone().html("");
			
			$('.form_file').each(function(index, element){
				index = index + 1;
				if (index % 4 == 0)
				{
					images.append(element);
					images.append(clear);
				}
				else
					images.append(element);
			});
			
			$('#app_images').replaceWith(images);
			dragAppFiles();
		}
	});
	
	$( id ).disableSelection();
}

function menuSlide(){
	
	if (navigator.platform == 'iPhone' || navigator.platform == 'iPad')
	{
		$('.menu_label').click(function(){
			$(this).next().toggleClass('open_menu');
		});
	}
}

function flashMessage()
{
	if ($('#flash p').text() != '')
	{
		$('#flash').show();
		margin = 0 - $('#flash').height();
		$('#flash').css('margin-top', margin);
		
		$('#flash').animate({marginTop: 0}, 1500, 'easeOutBounce');
		setTimeout("clearFlash()", 2500);
	}
}

function clearFlash()
{
	margin = 0 - $('#flash').height();
	$('#flash').animate({marginTop: margin}, 1000, 'easeInExpo', function(){
		$(this).hide();
	});
}

function letterCounter(id, counter)
{
	$('#'+counter).text($('#'+id).val().length);
	$('#'+id).focusin(function(){
		setInterval("$('#"+counter+"').text($('#"+id+"').val().length)", 10);
	});
}

function hintImages()
{
	$('img.ui-icon').mouseover(function(e){
		var text = $(this).attr('title');
		var hint = '<p class="img_hint">' + text + '</p>';
		
		//clear in case browser would like to display this
		$(this).attr('alt', '').attr('title', '');
		
		$('body').prepend(hint);
		setPosition($('.img_hint'), e);
		
		$(this).mousemove(function(e){
			setPosition($('.img_hint'), e);
		});
		
		//clear
		$(this).mouseout(function(){
			$(this).attr('alt', text).attr('title', text);
			$('.img_hint').remove();
		});
	});	
}

function setPosition(obj, e)
{
	var top = Number(e.pageY) + 6;
	var left = Number(e.pageX) + 6;
	obj.css({'top': top+'px', 'left': left+'px'});
}

function setDateFields()
{
	
	$('.date').datepicker({ dateFormat: 'dd-mm-yy',
		 					showAnim: 'slideDown'});
	 $('.date').datepicker( "option", $.datepicker.regional[ getLang() ] );
}

function getLang()
{
	return $('#header').attr('name');
}


function hideFileFields()
{
	var last = 0;
	$('.fileFieldset').each(function(index){
		 
		if ($(this).find('input:file').length == 0)
		{
			$(this).hide();
			last = index;
		}
		else if(index > (last+1) )
		{
			$(this).hide();
		}
	});
	
	$('input:file').change(function(){
		var elem = $(this).parent().next();
		if (elem.attr('class') == 'error-div')
			elem = elem.next();
		
		elem.show();

	});
}

function runFancyBox(obj, autoScale)
{
		$(obj).click(function(e){
			e.preventDefault();
		});
		
		$(obj).fancybox({
			'transitionIn'	:	'elastic',
			'transitionOut'	:	'elastic',
			'titlePosition'	: 	'over',
			'speedIn'		:	600, 
			'speedOut'		:	200, 
			'autoScale' 	: 	autoScale,
			'overlayShow'	:	true
		});

}

function scrollToError()
{
	var errorPos = $('div.error-div:first').offset().top - 70;
	
	if($.browser.webkit)
	{
		$("body").scrollTop(errorPos);
	}
	else
	{
		$(document).scrollTop(errorPos);
	}
		
	
}