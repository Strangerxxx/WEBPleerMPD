<?php
	require_once '../config.php';
	require_once 'mpd.class.php';
	require_once 'functions.php';
	$mpd = new mpd(MPD_HOST,MPD_PORT,MPD_PWD);
	if($mpd->connected == false) die('Could not connected to mpd-server');
	if(!empty($_GET['action'])){
		$action = $_GET['action'];
		$return = array();
		switch ($action) {
			case 'queue':
				$list = showQueue($mpd);
				break;
			default:
				break;
		}
		$return['status'] = true;
		$return['list'] = $list;
		print_r(json_encode($return));
	}
