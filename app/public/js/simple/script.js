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
	
	if ($('#new_paragraph_text_1').length )
		add_regulation_paragraph();
	
	hintImages();
	
	setDateFields();
	
	checkbox();
	
	hideFileFields();
	
	highlightField();
	
	if ($('a.form_file').length )
	{
		runFancyBox('a.form_file', true);
	}
	
	if ($('a.miniature').length )
	{
		runFancyBox('a.miniature', true);
	}
	
	if ($('a.application').length )
	{
		runFancyBox('a.application', true);
	}
	
	if ($('a.files').length )
	{
		runFancyBox('a.files', true);
	}
	
	
	
	scrollToError();
});

function add_regulation_paragraph()
{
	//get html of the elements to copy
	label_no = $('#new_paragraph_no_1').prev().clone();
	input_no = $('#new_paragraph_no_1').clone().removeAttr('id'); 
	label_text = $('#new_paragraph_text_1').prev().clone();
	input_text = $('#new_paragraph_text_1').clone().removeAttr('id');
	
	$('textarea:last').change(function(){
		no = $(this).attr('name');
		no = parseInt(no.substring(no.lastIndexOf('_')+1, no.length)) + 1;
		no.toString();
		input_new_no = input_no;
		input_new_no.attr('name', 'new_paragraph_no_'+no);
		
		input_new_text = input_text;
		input_new_text.attr('name', 'new_paragraph_text_'+no);

		$(this).after(input_new_text).after(label_text).after(input_new_no).after(label_no);
	});	
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

function checkbox()
{
	bindCheckbox('show_email');
	bindCheckbox('personal_data_agreement');
	
	//application index
	$('.admin_button').next().click(function(){
		if ($(this).prev().is(':checked'))
		{
			$(this).prev().attr('checked', false);
			$(this).toggleClass('checked');
			$(this).toggleClass('ui-icon-white');
		}
		else
		{
			$(this).prev().attr('checked', true);
			$(this).toggleClass('checked');
			$(this).toggleClass('ui-icon-white');
		}
	});
}

function bindCheckbox(field)
{
	$( "#"+field ).button();
	if ($('#'+field).is(':checked'))
	{
		$( "#"+field ).button("option", "icons", {primary: 'ui-icon-check'});
	}
	else
	{
		$("label[for='"+field+"']").addClass('ui-state-white');
		$( "#"+field ).button("option", "icons", {primary: 'ui-icon-closethick'});
	}
	
	$('#'+field).change(function(){
		if ($("label[for='"+field+"'] span:first-child").hasClass('ui-icon-check'))
		{
			$("label[for='"+field+"'] span:first-child").removeClass('ui-icon-check');
			$("label[for='"+field+"']").removeClass('ui-state-default').addClass('ui-state-white');
			$("label[for='"+field+"'] span:first-child").addClass('ui-icon-closethick');
		}
		else if ($("label[for='"+field+"'] span:first-child").hasClass('ui-icon-closethick'))
		{
			$("label[for='"+field+"'] span:first-child").removeClass('ui-icon-closethick');
			$("label[for='"+field+"']").removeClass('ui-state-white').addClass('ui-state-default');
			$("label[for='"+field+"'] span:first-child").addClass('ui-icon-check');
		}
	});
}

function hideFileFields()
{
	$('.fileFieldset').hide();
	$('.fileFieldset:first').show();
	
	$('a.form_file').each(function(){
		$(this).parent().parent().show();
	});
	$('.fileFieldset div.error-div').each(function(){
		$(this).parent().show();
	});
	
	$('.fileFieldset').each(function(){
		var input = $(this).find('input[type="file"]');
		var no = input.attr('name');
		no = no.substring(no.lastIndexOf("_")+1);
		var legend = $(this).find('legend');
		legend.text(legend.text() + " " + no);
	});
	
	$('.fileFieldset').change(function(){
		if ($(this).next().length != null)
			$(this).next().show();
	});
}

function highlightField()
{
	$('input, textarea, select').focusin(function(){
		$(this).css('-webkit-box-shadow', '0px 0px 8px 3px #880000');
		$(this).css('-moz-box-shadow', '0px 0px 8px 3px #880000');
		$(this).css('box-shadow', '0px 0px 8px 3px #880000');
	});
	$('input, textarea, select').focusout(function(){
		$(this).css('-webkit-box-shadow', '');
		$(this).css('-moz-box-shadow', '');
		$(this).css('box-shadow', '');
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