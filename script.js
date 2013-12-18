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
	var res;
	switch(action){
		case 'queue':
			req = sendRequest({action: 'queue'},url);
			req.success(function(data){return updateList(data.list);})
			break;
		case 'search':
			req = sendRequest({
				action: 'search',
				query: param.query,
				page: param.page
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
	var list_group = $("#list");
	list_group.empty();
	$.each(list, function(key,val){
		list_group.append("<li class='list-group-item item-"+key+"''></li>")
		.append("<span class='left'>"+key+".</span><span class='center'>"+val.artist+"&#8211;"+val.name+"</span><span class='right'></span>");
	});
	return true;
}
//
function sendRequest(data,url){
	return $.ajax({
		dataType: 'json',
		url: url,
		data: data,
	});
}
