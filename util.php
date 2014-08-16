<?php

function curl_post($url, array $post = NULL, array $options = array()) 
{ 
    $defaults = array( 
        CURLOPT_POST => 1, 
        CURLOPT_HEADER => 0, 
        CURLOPT_URL => $url, 
        CURLOPT_FRESH_CONNECT => 1, 
        CURLOPT_RETURNTRANSFER => 1, 
        CURLOPT_FORBID_REUSE => 1, 
        CURLOPT_TIMEOUT => 4, 
        CURLOPT_POSTFIELDS => http_build_query($post) 
    ); 

    $ch = curl_init(); 
    curl_setopt_array($ch, ($options + $defaults)); 
    if( ! $response = curl_exec($ch)) 
    { 
        trigger_error(curl_error($ch)); 
    } 
    //$request = curl_getinfo($ch);
    curl_close($ch);
    return $response; 
}

function http_parse($res){
    $parsed = array(
        "headers" => array(),
        "body" => ""
    );
    preg_match("/(.*)\r?\n\r?\n(.*)/s", $res, $matches);
    $headers = preg_split("/\r?\n|\r/", $matches[1]);
    foreach ($headers as $value) {
        $header = preg_split("/: /", $value);
        if(count($header) == 2){
            switch($header[0]){
                case "Set-Cookie":
                    if(!array_key_exists("Set-Cookie", $parsed['headers'])){
                        $parsed['headers']['Set-Cookie'] = array();
                    }
                    preg_match_all("/([^;\s]+)=([^;]*)/s", $header[1], $values);
                    $cookie_name = $values[1][0];
                    $values[1][0] = "value";
                    $parsed['headers']['Set-Cookie'][$cookie_name] = array_combine($values[1], $values[2]);
                    break;
                default:
                    $parsed['headers'][$header[0]] = $header[1];
            }
        }
    }
    $parsed['body'] = $matches[2];
    return $parsed;
}

