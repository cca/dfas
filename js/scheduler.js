var monthNames = ['January','February','March','April','May','June','July',
	'August','September','October','November','December'];
var dayNames = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];

function makeReservation(link) {
	var params = link.href.toQueryParams();
	var date = new Date(params.date*1000);
	var time = new Date(params.time*1000);
	var dateString = dayNames[date.getDay()]+', '+monthNames[date.getMonth()]+' '+date.getDate()+
		', '+date.getFullYear();
	var getHours = time.getHours();
	var getMinutes = time.getMinutes();
	var timeString = (getHours > 12 ? getHours-12 : getHours)+':'+
		(getMinutes < 10 ? '0'+getMinutes : getMinutes)+' '+
		(getHours < 12 ? 'AM' : 'PM');
	var yes = confirm('Are you sure you want to make a reservation for '+
		dateString+' at '+timeString+'?');
	if (yes) {
		window.location = link+'&confirm=true';
	}
}

function cancelReservation(link) {
	var params = link.href.toQueryParams();
	var date = new Date(params.date*1000);
	var time = new Date(params.time*1000);
	var dateString = dayNames[date.getDay()]+', '+monthNames[date.getMonth()]+' '+date.getDate()+
		', '+date.getFullYear();
	var getHours = time.getHours();
	var getMinutes = time.getMinutes();
	var timeString = (getHours > 12 ? getHours-12 : getHours)+':'+
		(getMinutes < 10 ? '0'+getMinutes : getMinutes)+' '+
		(getHours < 12 ? 'AM' : 'PM');
	var yes = confirm('Are you sure you want to cancel your reservation for '+
		dateString+' at '+timeString+'?  This cannot be undone.');
	if (yes) {
		window.location = link+'&confirm=true';
	}
}