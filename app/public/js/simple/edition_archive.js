$(document).ready(function(){
	bindApps();
});

function bindApps()
{
	$('.confirm_archive').click(function(e){
		e.preventDefault();
		var in_ids = $('input[name=apps]').val();
		ids = in_ids.split(',');
		processing = [];
		loader = $('#loader').clone().addClass('cloned');
		confirm_status = $('img.confirmed').clone();
		error_status = $('img.canceled').clone();
		archiveApps();
	});
}

function archiveApps()
{
	if (processing.length == 0 && ids.length > 0)
	{
		processing.push(ids[0]);
		archiveApp(ids.shift());
	}
	else if (ids.length == 0)
	{
		$('#saving_end').dialog({
				modal: false,
				buttons: {
					'OK': function() {
						$( this ).dialog( "close" );
					},
				}
		});
	}
}

function archiveApp(id)
{
	loader.css('display', 'block');
	var self = $('#app_'+id);
	$.ajax({
		url: window.location.href,
		data: {'apps' : id,},
		type: 'POST',
		dataType: 'json',
		beforeSend: function(){
			self.prepend(loader);
		},
		error: function(data){
			loader.remove();
			self.prepend(error_status.clone().show());
			processing.pop();
			archiveApps();
		},
		success: function(data){
			loader.remove();
			if (data.hasError == true) {
				var e = error_status.clone().attr('title', data[id]['error']);
				self.prepend(e.show());
			}
			else {
				self.prepend(confirm_status.clone().show());
			}
			processing.pop();
			archiveApps();
		},
	});
}