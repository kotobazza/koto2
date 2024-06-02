<?php
    require_once("../database.php");
    require_once("../functools.php");


    if (!($_SERVER['REQUEST_METHOD'] === 'POST')){

        http_response_code(405); 
        $response = array('status' => 'error', 'message' => 'Неправильный тип запроса');
        echo json_encode($response);
        exit();
    }

    $login = isset($_POST['login']) ? $_POST['login'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;

    if( empty($login) || empty($password) ){
        $response = array('status' => 'error', 'message' => 'Данные пришли пустыми: '.$login." -".$password);
        echo json_encode($response);
        exit();
    }

    $query = "SELECT * FROM users WHERE login= '$login' AND password = '$password'";

    $check_result = mysqli_query($connect, $query);
    if (mysqli_num_rows($check_result) > 0){
        $user = mysqli_fetch_assoc($check_result);
        set_session($user['id'], $login);
        $response = array('status' => 'success', 'message' => 'Вход выполнен успешно', 'id' => $user['id']);
        
        echo json_encode($response);
        
        exit();
    } 
    echo json_encode(["status"=> 'error', "message" => "Пользователь не найден"]);
?>
