var config = {
	url: "backend/"
}
//bindings
$(document).ready(function(){
	$('#search-input').bind('change', function(){ search($('#search-input').val()) });
	$('#search-button').bind('click', function(){ search($('#search-input').val()) });
});
// shortcuts
var timer = setInterval(update, 10000);
function addTrack(val){ getList({action: 'addTrack', track_id: val}, config.url); timer = setInterval(update, 10000);}
function update(){ getList({action: 'queue'}, config.url) }
function search(val){ clearInterval(timer); getList({action: 'addTrack', query: val}, config.url); }
//
function getList(param,url){
	var action = param.action;
	var list;
	var req;
	switch(action){
		case 'queue':
			req = sendRequest({action: 'queue'},url);
			req.success(function(){
				if(data.status) return updateList(data.list);
			});
			break;
		case 'search':
			req = sendRequest({
				action: 'search',
				query: param.query,
				page: param.page
			},url);
			req.success(function(){
				if(data.status) return updateList(data.list);
			});
			break;
		case 'addTrack':
			req = sendRequest({
				action: 'addTrack',
				track_id: param.track_id
			},url);
			req.success(function(){
				if(data.status) return getList({action: 'queue'},url);
			});
			break;
	}
}
//
function updateList(list){
	var list_group = $("#list");
	list_group.find(':not(.label)').remove();
	var i;
	var duration;
	$.each(list, function(key,val){
		i = parseInt(key)+1;
		duration = secsToTime(val.duration);
		list_group.append("<a class='list-group-item item-"+i+" "+val.status+"'><span class='left'>"+i+".</span><span class='center'>"+val.artist+"&#8211;"+val.name+"</span><span class='right'>"+duration.h+":"+duration.m+":"+duration.s+"</span></a>");
	});
	return true;
}
function secsToTime(secs){
	var hours = Math.floor(secs / 3600);
	secs = secs - hours * 3600;
	var minutes = Math.floor(secs / 60);
	var seconds = secs - minutes * 60;
	return {h: hours, m: minutes, s: seconds};
}
//
function sendRequest(data,url){
	return $.ajax({
		dataType: 'json',
		url: url,
		data: data,
	});
}
