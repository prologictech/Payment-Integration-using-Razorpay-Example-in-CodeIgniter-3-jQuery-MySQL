<?php
/**
 * Author       - santosh
 * Description  - General hook file for use
*/
class General {

    function set_headers(){
       
    //setting header for response
       header("X-Frame-Options: SAMEORIGIN");
       header('X-Content-Type-Options: nosniff');
       header("Cache-Control:  no-cache, no-store, must-revalidate");
       header("Pragma: no-cache");
    }
}

