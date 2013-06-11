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
  
  if ($('#work_desc_eng').length )
    letterCounter('work_desc_eng', 'word_counter_eng');
  
  if($('#model_specific_fields').length > 0) {
    showModelScale();
  }
  
  hintImages();
  
  setDateFields();
  
  hideFileFields();
    
  if ($('a.form_file').length )
  {
    runFancyBox('a.form_file', true);
  }
  
  if ($('a.application_files').length )
  {
    applicationFilesFancyBox();
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
  //deleteAppImage();

  if ($('#news_files').length)
  {
    deleteNewsFiles();
  }
  if ($('iframe').length)
  {
    prepareNewsForm();
  }
  
  if ($('form.newsForm .lang_choice').length)
  {
    newsLangChoice();
  }
  if ($('form.diploma .lang_choice').length)
  {
    diplomaLangChoice();
  }
  
  if ($('#applicationForm').length)
  {
    disableSubmitOnEnter();
  }
  
  if ($('.editor').length)
  {  
    $('.editor').tinymce({
      // Location of TinyMCE script
      script_url : '/js/tiny_mce/tiny_mce.js',

      // General options
      theme : "advanced",
      plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

      // Theme options
      theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
      theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
      theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
      theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
      theme_advanced_toolbar_location : "top",
      theme_advanced_toolbar_align : "left",
      theme_advanced_statusbar_location : "bottom",
      theme_advanced_resizing : true,
      
      // Skin options
      skin : "o2k7",
      skin_variant : "silver",
    });
  }
  
  if ($('.tinymce').length > 0) {
    $('textarea.tinymce').tinymce({
       // Location of TinyMCE script
            script_url : '/js/tiny_mce/tiny_mce.js',

            // General options
            theme : "advanced",
            plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

            // Theme options
            theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
            theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
            theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
            theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
            theme_advanced_toolbar_location : "top",
            theme_advanced_toolbar_align : "left",
            theme_advanced_statusbar_location : "bottom",
            theme_advanced_resizing : true,
            
            // Skin options
            skin : "default",


    });
  }
});

function applicationFilesFancyBox() 
{
  
  runFancyBox('a.application_files', true);
  
  if ($('.miniature').length)
  {
    $('.miniature').click(function(){
      var rel = $(this).attr('rel');
      $('a.application_files[rel='+rel+']:first-child').click();
    });
  }  
  
}

function disableSubmitOnEnter()
{
  //var formFields = $('input[type!="hidden"], textarea, select')
  $('input, select').keypress(function(e){
    if (e.which == 13)
    {
      e.preventDefault();
      /*
       * go to next field 
      var curElem = $(this);
      formFields.each(function(index){
        var elem = $(this);
        if (elem.attr('name') == curElem.attr('name'))
        {
          formFields[index + 1].focus();
        }
      });
      */
    }
  });
}

function deleteNewsFiles()
{
  $('input[type=file]').change(function(){
    $('#submit').click();
  });
  
  $('.remove-image').click(function(e){
    e.preventDefault();
    var file = $(this).attr('href') + ', ';
    var files = $('#news_files').val();
    files = files.replace(file, '');
    $('#news_files').val(files);
    
    $(this).parent().parent().remove();
    $('.img_hint').remove();
    rearangeRows('app_images');
  });
}

function rearangeRows(id)
{
  var clear = $('.clearfix:first').clone();
  $('.clearfix').remove();
  
  $('#'+id).html();
  $('#'+id).children().each(function(index, element){
    var i = index + 1;
    
    $('#'+id).append(element);
    if (i % 4 == 0)
      $('#'+id).append(clear);
  });
}

function prepareNewsForm()
{
  $('form').submit(function(e){
    var files = $('#newsfiles').contents().find('#news_files').val();
    $('#files').val(files);
  });
  
  $('#newsfiles').load(function(){
    if ($('#files').val() != '')
    {
      var files = $('#files').val();
      var frame = $('#newsfiles').contents().find('#news_files');
      frame.val(files);
      $('#files').val('');    //reset to not overwrite
    }
  });
  
}

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
            div.parent().remove();
            $(cache).val("");
          $(id).val("");
          $(cache).parent().parent().show();
          }, 
          error: function(){
            alert('An error occurred');
          }
    });
  }
  );
}

function newsLangChoice() 
{
  //show first div according to lang settings
  if ($('.current').length == 0)
  {
    var lang = $('.lang-selected').text().toLowerCase(); 
  }
  else
  {
    var lang = $('.current').text();
    $('.current').removeClass('current');
  }
  $('form div').hide();
  $('#' + lang).show();
  $('.choice_' + lang).addClass('current');
  
  $('.lang_choice li').click(function(){
    var lang = $(this).text();
    $('form div').hide();
    $('#' + lang).show();
    $('.current').removeClass('current');
    $('.choice_' + lang).addClass('current');
  });
}

function diplomaLangChoice() 
{
  //show first div according to lang settings
  if ($('.current').length == 0)
  {
    var lang = $('.lang-selected').text().toLowerCase(); 
  }
  else
  {
    var lang = $('.current').text();
    $('.current').removeClass('current');
  }
  $('form div.lang_div').hide();
  $('#' + lang).show();
  $('.choice_' + lang).addClass('current');
  
  $('.lang_choice li').click(function(){
    var lang = $(this).text();
    $('form div.lang_div').hide();
    $('#' + lang).show();
    $('.current').removeClass('current');
    $('.choice_' + lang).addClass('current');
  });
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
    setTimeout(function(){clearFlash();}, 2500);
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
  console.log($('#'+counter));
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
    $(this).bind('mouseout click mousedown mouseup', (function(){
        if ($('.img_hint'))
        {
          $(this).attr('alt', text).attr('title', text);
          $('.img_hint').remove();
        }
      })
    );
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
  var show = 0;
  //hide fields, show only one for new file
  $('.fileField').each(function(index){
    var fieldset = $(this).find('fieldset');
    
    if (fieldset.find('input[name*="Cache"]').val() != '')
      $(this).hide();  //fieldset with uploaded file
    else if (show == 0)
      show = 1;//first new file input
    else if (show == 1)
      $(this).hide(); //hide input
  });
  
  //bind showing next input on change
  $('input[type="file"]').change(function(){
    var elementWrapper = $(this).parent().parent();
    
    if (elementWrapper.next().length != 0)
    {
      $(this).parent().parent().next().show();
    }
  });
  
  //resort numbers
  $('.file_number').each(function(index){
    var number = index + 1;
    $(this).text(number);
  });
}

function runFancyBox(obj, autoScale)
{
    var obj = $(obj);
    obj.click(function(e){
      e.preventDefault();
    });
    
    obj.fancybox({
      'transitionIn'  :  'elastic',
      'transitionOut'  :  'elastic',
      'titlePosition'  :   'over',
      'speedIn'    :  600, 
      'speedOut'    :  200, 
      'autoScale'   :   autoScale,
      'overlayShow'  :  true
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

function showModelScale() {
  $('#work_type_id').on('change', function(){
    if ($(this).val() == '2') {
      $('#model_specific_fields').show();
    }
    else {
      $('#model_specific_fields').hide();
    }
  });
}