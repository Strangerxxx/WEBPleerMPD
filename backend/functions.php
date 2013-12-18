<?php
	function showQueue($mpd)
	{
		$list = array();
		$playlist = $mpd->playlist;
		if(!is_null($playlist)){
			foreach ($playlist as $id => $track) {
				$status = ( $id == $mpd->current_track_id ? 'playing' : '' );
				array_push($list, array(
					'track_id'	=>	$track['Id'],
					'status'	=>	$status,
					'artist'	=>	$track['Artist'],
					'name'		=>	$track['Title'],
					'duration'	=>	$track['Time']
					)
				);
			}
			return (object) $list;
		}
	}
	function showSearch($query, $page, $pleer){
		$list = array();
		$searchlist = $pleer->tracks_search($query, $page);
		foreach ($searchlist as $id => $track) {
			array_push($list, array(
				'track_id'	=>	$track['track_id'],
				'status'	=>	'',
				'artist'	=>	$track['artist'],
				'name'		=>	$track['track'],
				'duration'	=>	$track['lenght']
				)
			);
		}
		return (object) $list;
	}
