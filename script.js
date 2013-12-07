var listUrl = "list.json";
var this.url = "";
// shortcuts
function addTrack(var){ getList({action: 'addTrack', track_id: var}, this.url); }
function update(){ getList({action: 'queue'}, this.url) }
function search(var){ getList({action: 'addTrack', track_id: var}, this.url); }
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
		case default:
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
