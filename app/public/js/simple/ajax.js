jQuery.ajaxSetup({ 'beforeSend': function(xhr) {xhr.setRequestHeader("Accept", "text/javascript");} });

function _ajax_request(url, data, callback, type, method) {
    if (jQuery.isFunction(data)) {
        callback = data;
        data = {};
    }
    console.log(url);
	console.log(data);
    return jQuery.ajax({
        type: method,
        url: url,
        global: false,
        data: data,
        error: function(data) {
        	console.log('error');
        	console.log(data);
        },
        success: function(data) {
        	console.log(data);
        	callback(data);
        },
        dataType: type
        });
     
}

jQuery.extend({
    put: function(url, data, callback, type) {
        return _ajax_request(url, data, callback, type, 'PUT');
    },
    delete_: function(url, data, callback, type) {
        return _ajax_request(url, data, callback, type, 'POST');
    }
});

jQuery.fn.deleteWithAjax = function(callback) {
	
	this.preventDefault;
	this.removeAttr('onclick');
	this.unbind('click', false);
	this.live('click', function(e) {
		e.preventDefault();
		var link = ($(this).attr("href"));
	
		if ($(this).hasClass('no-confirm'))
		{
			$.delete_(link, {}, function(){}, "script");
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
		                    $.delete_(link, $(this).serialize(), callback, "json");
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
	});
	return this;
};

//This will "ajaxify" the links
function ajaxLinks(){
	
    $('a.delete').deleteWithAjax(redirect);
    $('a.remove-image').deleteWithAjax(removeImage);
    schoolAutocomplete();
}

function redirect(data)
{
	data.link ? link = data.link : link = data; 
	window.location.href =  link;
	
}

function removeImage(data)
{
	if (data.access == 0)
	{
		console.log(data);
		alert("You don't have access to this resource");
	}
	else
	{
		console.log(data);
		$('#'+data.file_id).remove();
		$('#file_' + data.file_id).remove();
		$('input[name*="file_id"]').each(function(){
			console.log($(this));
			if ($(this).val() == data.file_id)
			{
				var fieldset = $(this).parent();
				console.log(fieldset);
				fieldset.children('input[name*="Cache"]').val('');
				fieldset.children('input[name*="file_id"]').val('');
				fieldset.parent().show();
			}
		});
		var cached = new Array();
		var empty = new Array();
		$('.fileField').each(function(){
			var self  = $(this);
			if (self.children('fieldset').children('input[name*="Cache"]').val() != '')
			{
				cached.push(self);
			}
			else
			{
				empty.push(self);
			}
		});
		
		$('.fileField').remove();
		var last = 0;
		for (x in cached)
		{
			var elem = cached[x];
			last = parseInt(last) + 1;
			elem.children('.file_number').text(last);
			$('.column-right div:last-child').after(elem);
		}
		for (x in empty)
		{
			var elem = empty[x];
			last = parseInt(last) + 1;
			elem.children('.file_number').text(last);
			$('.column-right div:last-child').after(elem);
		}
		hideFileFields();
	}
}

function getHost()
{
	link = window.location.href;
	alert(link);
}


function schoolAutocomplete(){
	
	$(function() {
		function split( val ) {
			return val.split( /,\s*/ );
		}
		function extractLast( term ) {
			return split( term ).pop();
		}
	
		$( "#school" )
			// don't navigate away from the field on tab when selecting an item
			.bind( "keydown", function( event ) {
				if ( event.keyCode === $.ui.keyCode.TAB &&
						$( this ).data( "autocomplete" ).menu.active ) {
					event.preventDefault();
				}
			})
			.autocomplete({
				source: function( request, response ) {
					$.getJSON( "/schools/find", {
						term: extractLast( request.term )
					}, response );
				},
				search: function() {
					// custom minLength
					var term = extractLast( this.value );
					if ( term.length < 2 ) {
						return false;
					}
				},
				focus: function() {
					// prevent value inserted on focus
					return false;
				},
				select: function( event, ui ) {
					this.value = ui.item.value;
					return false;
				}
			});
	});
}

function uploadFile()
{
	$('#file').change(function(){
		
		var url = $('#FileForm').attr('action');
		
		$.ajax({
			type: "POST",
			url: url,
			//data: 'file$=' + ('#FileForm'),
			global: false,
			error: function(jqXHR, textStatus, errorThrown){
				console.log(errorThrown);
			},
			success: function(data, msg, jqXHR){
				console.log(data);
			},
			dataType: '	json'
		});
	});
}