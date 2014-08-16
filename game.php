<?php

Class TritonGame {
	var $client;
	var $id;

	function TritonGame($client, $game_id){
		$this::__constructor($client, $game_id);
	}
	function __constructor($client, $game_id)
	{
		$this->client = $client;
		$this->id = $game_id;
	}

	function GetFullUniverse(){
		return $this->client->gameRequest("order",$this->id,array('order' => "full_universe_report"));
	}
	function GetIntel(){
		return $this->client->gameRequest("intel_data",$this->id);
	}
	function GetUnreadCount(){
		return $this->client->gameRequest("fetch_unread_count",$this->id);
	}
	function GetPlayerAchievements(){
		return $this->client->gameRequest("fetch_player_achievements",$this->id);
	}
	/**
	 * $msg_type - 'game_diplomacy' or 'game_event'
	 */
	function GetMessages($msg_type, $count, $offset = 0){
		$options = array("count"=>$count,"offset"=>$offset,"group"=>$msg_type);
		return $this->client->gameRequest("fetch_game_messages",$this->id,$options);
	}
}