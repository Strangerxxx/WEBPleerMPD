<?php
	require_once '../config.php';
	require_once 'mpd.class.php';
	require_once 'pleer.class.php';
	require_once 'functions.php';
	$mpd = new mpd(MPD_HOST,MPD_PORT,MPD_PWD);
	$pleer = new Pleer(PLEER_USER,PLEER_PASSWORD);
	print_r($pleer->tracks_search('qwe', '1'));
	if(!$mpd->connected) die('Could not connected to mpd-server');
	if(!$pleer->auth) die('Pleer error: '.$pleer->error.' : '.$pleer->error_description);
	if(!empty($_GET['action'])){
		$action = $_GET['action'];
		$return = array();
		switch ($action) {
			case 'queue':
				$list = showQueue($mpd);
				break;
			case 'search':
				$query = $_GET['query'];
				$page = $_GET['page'];
				$list = showSearch($query,$page,$pleer);
				break;
			default:
				break;
		}
		$return['status'] = true;
		$return['list'] = $list;
		print_r(json_encode($return));
	}
