/**
 * @author zefiryn
 */

jQuery.ajaxSetup({ 'beforeSend': function(xhr) {xhr.setRequestHeader("Accept", "text/javascript")} })

function _ajax_request(url, data, callback, type, method) {
    if (jQuery.isFunction(data)) {
        callback = data;
        data = {};
    }
    return jQuery.ajax({
        type: method,
        url: url,
        global: false,
        data: data,
        success: callback,
        dataType: type
        });
}

jQuery.extend({
    put: function(url, data, callback, type) {
        return _ajax_request(url, data, callback, type, 'PUT');
    },
    delete_: function(url, data, callback, type) {
        return _ajax_request(url, data, callback, type, 'DELETE');
    }
});

jQuery.fn.deleteWithAjax = function() {
	
	this.preventDefault;
	this.removeAttr('onclick');
	this.unbind('click', false);
	this.click(function() {

	link = ($(this).attr("href"));

	if ($(this).hasClass('no-confirm'))
	{
		$.delete_(link, {}, redirect_to_root(data), "script");
		return true;
	}
	else
	{
		var confirmText = '<span class="ui-icon ui-icon-alert" style="float:left; margin:20px 7px 20px 0px;"></span>' + $('#dialog-confirm p').text();
		$('#dialog-confirm p').html(confirmText);
		
		var titleText = $('#ui-dialog-title-dialog-confirm').text();
		var deleteButton = $('#button_conf').text();
		var cancelButton = $('#button_close').text();

		$( "#dialog-confirm" ).dialog({
				draggable: true,
				resizable: false,
				title: titleText,
				modal: true,
				buttons:
				[{
					text: deleteButton, 
	                click: function() {
	                    $( this ).dialog( "close" );
	                    $.delete_(link, $(this).serialize(), refreshPage(), "script");
	                    return true;
	                }
				 },
				 {
					 text: cancelButton,
					 click: function() {
	                    $( this ).dialog( "close" );
	                    return false;
	                }
	            }]
		});
		
	    $( "#dialog-confirm" ).dialog("open");
	    return false;
	}
  })
  return this;
};

//This will "ajaxify" the links
function ajaxLinks(){
	
    $('a.delete').deleteWithAjax();
}

function refreshPage()
{
	link = window.location.href;
	window.location.href =  link;
}

function getHost()
{
	link = window.location.href;
	alert(link);
}
