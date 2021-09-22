<?php

Class TritonGame {
    var $client;
    var $id;

    // PHP 4 style constructor for backwards-compatibility.
    function TritonGame($client, $game_id){
        $this::__construct($client, $game_id);
    }

    function __construct($client, $game_id)
    {
        $this->client = $client;
        $this->id = $game_id;
    }

    function order($type, $order){
        return $this->client->gameRequest($type, $this->id, array('order'=>$order));
    }

    function GetFullUniverse(){
        return $this->order("order", "full_universe_report");
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
    function GetDiplomacyMessages($count, $offset = 0){
        return $this->GetMessages('game_diplomacy',$count, $offset);
    }
    function GetEventMessages($count, $offset = 0){
        return $this->GetMessages('game_event',$count, $offset);
    }

    function BuyEconomy($star, $price){
        return $this->order("batched_orders", "upgrade_economy,{$star},{$price}");
    }

    //function BuyIndustry($star, $price){
    //    return $this->order("batched_orders", "upgrade_industry,{$star},{$price}");
    //}
    //function BuyScience($star, $price){
    //    return $this->order("batched_orders", "upgrade_science,{$star},{$price}");
    //}

    /**
     * Admin Orders
     */
    function TogglePause(){
        return $this->order("order", "toggle_pause_game");
    }
}
