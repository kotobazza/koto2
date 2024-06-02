<?php
    $connect = mysqli_connect('127.0.0.1', 'root', '', 'koto_project2');

    if(!$connect){
        die("error, no database");
    }
?>
