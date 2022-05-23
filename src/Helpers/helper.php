<?php

if(!function_exists('random_string_generator')){
    function random_string_generator($length = 64){
        return substr(hash('sha256', hash('sha256', uniqid() . random_bytes(300))), 0, $length);
    }
}