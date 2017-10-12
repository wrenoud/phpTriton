<?php

require_once __DIR__ . "/util.php";
require_once __DIR__ . "/game.php";
require_once __DIR__ . "/server.php";

class TritonClient {
    var $version;
    var $auth_cookie;
    var $logged_in;
    var $alias;
    var $password;
    var $url;

    function TritonClient($alias, $password, $version = 7){
        $this::__constructor($alias, $password, $version);
    }

    function __constructor($alias, $password, $version = 7)
    {
        $this->version = $version;
        $this->auth_cookie = "";
        $this->alias = $alias;
        $this->password = $password;
        $this->logged_in = false;
        $this->url = "https://np.ironhelmet.com";
    }

    function authenticate(){
        $url = $this->url . "/arequest/login";
        $fields = array(
            "type" => "login",
            "alias" => $this->alias,
            "password" => $this->password
        );
        $result = curl_post($url, $fields, array(CURLOPT_HEADER => 1));
        $res = http_parse($result);

        $res_body = json_decode($res['body'], true);

        if($res_body[0] == "meta:login_success"){
            $this->auth_cookie = $res['headers']['Set-Cookie']['auth']['value'];
            $this->logged_in = true;
            return true;
        }else{
            $err_msg = $res_body[1];
            $this->logged_in = false;
            switch($err_msg){
                case "account_not_found":
                    print "Login Error: unknown alias";
                    break;
                case "login_wrong_password":
                    print "Login Error: wrong passord";
                    break;
                default:
                    print "Unknown Error: " . $err_msg;
                    break;
            }
            return false;
        }
    }

    function GetGame($game_id){
        return new TritonGame($this, $game_id);
    }

    function GetServer(){
        return new TritonServer($this);
    }

    function serverRequest($type){
        if($this->logged_in){
            $url = $this->url . '/mrequest/' . $type;
            $fields = array(
                'type' => $type,
            );

            $result = curl_post($url, $fields, array(CURLOPT_HEADER => 1, CURLOPT_COOKIE => "auth={$this->auth_cookie};"));
            $res = http_parse($result);

            if($res['body'] != ''){
                $body_json = json_decode($res['body'], true);

                if($body_json[0] == "meta:" . $type){
                    $this->auth_cookie = $res['headers']['Set-Cookie']['auth']['value'];
                    return $body_json[1];
                }else{
                    $err_msg = $body_json[0];

                    switch($err_msg){
                        case "meta:login_required":
                            print "Error: not logged in";
                            break;
                        default:
                            print "Unknown Error: " . $err_msg;
                            break;
                    }
                }
            }else{
                print "Error: unknown request '$type'";
            }
        }else{
            print "Error: not logged in";
        }
        return false;
    }

    function gameRequest($type, $game_id, $options = array()){
        if($this->logged_in){
            $url = $this->url . '/grequest/' . $type;
            $fields = array(
                'type' => $type,
                'version' => $this->version,
                'game_number' => $game_id
            );

            $fields = array_merge($fields, $options);

            $result = curl_post($url, $fields, array(CURLOPT_HEADER => 1, CURLOPT_COOKIE => "auth={$this->auth_cookie};"));
            $res = http_parse($result);

            if($res['body'] != ''){
                $body_json = json_decode($res['body'], true);

                if($body_json["event"] != $type . ":error" && $body_json["event"] != "None"){
                    $this->auth_cookie = $res['headers']['Set-Cookie']['auth']['value'];
                    return $body_json["report"];
                }else{
                    $err_msg =  $body_json["report"];

                    switch($err_msg){
                        case "must_be_logged_in":
                            $this->logged_in = false;
                            print "Error: not logged in";
                            break;
                        case "client_on_wrong_version":
                            print "Error: using wrong api version";
                            break;
                        case "None":
                            print "Error: game not found";
                            break;
                        default:
                            print "Unknown Error: " . $err_msg;
                            break;
                    }
                }
            }else{
                print "Error: unknown request '$type'";
            }
        }else{
            print "Error: not logged in";
        }
        return false;
    }
}
