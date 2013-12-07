<?php
	function showQueue($mpd)
	{
		$list = array();
		$playlist = $mpd->playlist;
		if(!is_null($playlist)){
			foreach ($playlist as $id => $track) {
				$status = ( $id == $mpd->current_track_id ? 'playing' : '' );
				array_push($list, array(
					'track_id'	=>	$id,
					'status'	=>	$status,
					'name'		=>	$track['Artist'].' - '.$track['Title'],
					'duration'	=>	'123'
					)
				);
			}
		}
	}