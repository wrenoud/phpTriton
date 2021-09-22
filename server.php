<?php

Class TritonServer {
    var $client;

    // PHP 4 style constructor for backwards-compatibility.
    function TritonServer($client){
        $this::__construct($client);
    }

    function __construct($client)
    {
        $this->client = $client;
    }

    function GetPlayer(){
        return $this->client->serverRequest("init_player");
    }
    function GetOpenGames(){
        return $this->client->serverRequest("open_games");
    }
}
