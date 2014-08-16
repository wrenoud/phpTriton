<?php

Class TritonServer {
    var $client;

    function TritonServer($client){
        $this::__constructor($client);
    }
    function __constructor($client)
    {
        $this->client = $client;
    }
    
    function GetPlayer(){
        return $this->client->serverRequest("init_player");
    }
    function GetOpenGames(){
        return $client->serverRequest("open_games");
    }
}