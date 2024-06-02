<?php
    require_once("../users/check_session.php");
    require_once("../database.php");

    if (!$_SERVER['REQUEST_METHOD'] === 'POST'){
        echo json_encode(["status" => "error", "message" => "Неправильный тип запроса"]);
        exit();
    }

    $user_id = $_SESSION["user_id"];

    $query = "SELECT COUNT(*) as number_of_posts FROM publications WHERE creator=$user_id";

    $tyapka = mysqli_query($connect, $query);

    $number_of_my_posts = mysqli_fetch_assoc($tyapka)["number_of_posts"];


    echo json_encode(["status"=>"success", "number_of_posts"=>$number_of_my_posts ]);





?>