// JavaScript Document
var interventionURL = "http://interventionapp.com/";

function autocomplete(query, action, container, input, is_support_group)
{
	input.attr('user_id', null);
	if (query == '') {
		container.fadeOut();
		container.empty();
		return;
	}
	var path = interventionURL + 'api/autocomplete.php?query=' + query + '&' + 'action=' + action;
	$.ajax({
		url: path,
		success: function(data) {
			data = JSON.parse(data);
			if (data.length <= 0) {
				container.fadeOut();
			} else {
				container.fadeIn();
			}
    		container.empty();
			for(var i = 0; i < data.length; ++i) {
				var ok = true;
				if (is_support_group) {
					$('.support-group-members-container div').each( function(index) {
						if ($(this).attr('user_id') == data[i]['user_id']) {
							ok = false;
						}
					});
				}
				if (ok == true) {
					container.append('<li user_id="' + data[i]['user_id'] + '">'+data[i]['name']+'</li>');
				}
			}
			$('li', container).click(function() {
				input.attr('user_id', $(this).attr('user_id'));
				input.val($(this).text());
				container.fadeOut();
				container.empty();
				if (is_support_group) {
					addNewSupporter($(this).attr('user_id'), $(this).text());
					input.val('');
					input.attr('user_id', null);
					input.focus();
				}
			});
  		}
	});
}

function addNewSupporter(user_id, name) {
	var container = $('.support-group-members-container');
	container.prepend ("<div class='label label-primary' user_id='" + user_id + "'>" + name + "<button type='button' class='close'>&times;</button></div>");
	$('.label button', container).click( function() {
		$(this).parent().remove();
	});
}