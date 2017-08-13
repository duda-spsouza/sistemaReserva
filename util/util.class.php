<?php
class Util{

    public static function sqlDateToBr($date){
        return implode('/',array_reverse(explode('-',substr($date,0,10)))).' '.substr($date, -8);
    }

    public static function brDateToSql($date){
        return implode('-',array_reverse(explode('/',$date))).' '.substr($date, -8);
    }

    public static function createHash($toEncrypt){
    	return md5($toEncrypt);
    }

    public static function checkSessionState(){
    	session_start();
    	if(!isset($_SESSION["user"])||!isset($_SESSION["started"])){
    		unset($_SESSION["user"]);
    		unset($_SESSION["started"]);
    		session_destroy();
    	}

    }
}
?>
