<?php
    function set_new_message($type, $message){
        if(isset($_SESSION['message'])){
            $_SESSION['message'][] = array(
                'type' => $type,
                'text' => $message
            );
        }
        else{
            $_SESSION['message'] = array(array(
                'type' => $type,
                'text' => $message
            ));
        }
    }


    function set_session($userid, $login){
        session_start();
        $_SESSION['user_id'] = $userid;
        $_SESSION['user_login'] = $login;
    }
    
    function destoroy_session(){
        session_start();
        session_destroy();
    }


?>