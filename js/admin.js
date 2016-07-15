function adminMakeReservation(d,t,s) {
	var date = new Date(d*1000);
	var time = new Date(t*1000);
	var mode = $('reserve_mode').value;

	if (mode == 'user' && $('reserve_user_id') == null) {
		alert('You must first specify the account for whom you are making a reservation.');
		return false;
	}
	else if (mode == 'other' && $('other_input').value == '') {
		alert('You must supply a label for the reservation.');
		return false;
	}

	var params = {
		action: 'ajax_make', date: d, time: t, schedule: s
	};
	if (mode == 'user') {
		var name = $('reserve_user_name').value;
		var user_id = $('reserve_user_id').value;
		var msg = 'Are you sure you want to make a reservation for '+name+
			' on '+date.format('fullDate')+' at '+time.format('shortTime')+'?';
		params.mode = 'user';
		params.user = user_id;
	}
	else { // "other" mode
		var label = $('other_input').value;
		var msg = 'Are you sure you want to block off time for "'+label+
			'" on '+date.format('fullDate')+' at '+time.format('shortTime')+'?';
		params.mode = 'other';
		params.label = label;
	}	
	
	if (confirm(msg)) {
		new Ajax.Updater('schedule_wrapper','reservation.php?'+Object.toQueryString(params));
		toggleScheduleControls();
	}
		
	return false;
}

function adminCancelReservation(d,t,s) {
	var date = new Date(d*1000);
	var time = new Date(t*1000);

	var yes = confirm('Are you sure you want to cancel the reservation for '+
		date.format('fullDate')+' at '+time.format('shortTime')+'?  This cannot be undone.');
	if (yes) {
		toggleScheduleControls();
		var params = {
			action: 'ajax_cancel', date: d, time: t, schedule: s
		};
		new Ajax.Updater('schedule_wrapper','reservation.php?'+Object.toQueryString(params));
	}
	
	return false;
}

// schedule management
function toggleScheduleDay(dayOfMonth) {
	var dayTd = $('day_'+dayOfMonth);
	var checked = $('open_'+dayOfMonth).checked;
	if (checked)
		dayTd.removeClassName('closed');
	else
		dayTd.addClassName('closed');
}
function toggleDefaultDay(dayOfWeek) {
	var dayTd = $('default_day_'+dayOfWeek);
	var checked = $('default_open_'+dayOfWeek).checked;
	if (checked)
		dayTd.removeClassName('closed');
	else
		dayTd.addClassName('closed');
}