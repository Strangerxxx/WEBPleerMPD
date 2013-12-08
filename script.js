//bindings
var url = "/backend"
$(document).ready(function(){
	$('.search input').bind('change', function(){ search($('.search input').val()) });
	$('.search i').bind('click', function(){ search($('.search input').val()) });
});
// shortcuts
var timer = setInterval(update, 10000);
function addTrack(var){ getList({action: 'addTrack', track_id: var}, url); timer = setInterval(update, 10000);}
function update(){ getList({action: 'queue'}, url) }
function search(var){ clearInterval(timer); getList({action: 'addTrack', q: var}, url); }
//
function getList(param,url){
	var action = param.action;
	var list;
	var req;
	switch(action){
		case 'queue':
			req = sendRequest({action: 'queue'},url);
			if(req.status) return updateList(req.list);
			break;
		case 'search':
			req = sendRequest({
				action: 'search',
				q: param.q,
			},url);
			if(req.status) return updateList(req.list);
			break;
		case 'addTrack':
			req = sendRequest({
				action: 'addTrack',
				track_id: param.track_id
			},url);
			if(req.status) return getList({action: 'queue'},url);
			break;
	}
}
//
function updateList(list){
	var select = $(".list select");
	select.empty();
	list.each(function(key,val){
		select.append("<option onclick='addTrack('"+val.track_id+"')' class='"+val.status+"'>"+key+" - "+val.name+" - "+val.duration+"</option>");
	});
	return true;
}
//
function sendRequest(data,url){
	$.ajax({
		dataType: 'json',
		url: url,
		data: data,
		success: function(data){return data;}
	});
}
