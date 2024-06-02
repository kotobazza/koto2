<?php
    require_once("../database.php");
    require_once("../functools.php");


    if (!($_SERVER['REQUEST_METHOD'] === 'POST')){

        http_response_code(405); 
        $response = array('status' => 'error', 'message' => 'Неправильный формат запроса');
        echo json_encode($response);
        exit();
    }

    $login = isset($_POST['login']) ? $_POST['login'] : null;
    $password = isset($_POST['password']) ? $_POST['password'] : null;
    $confirmPassword = isset($_POST['confirm']) ? $_POST['confirm'] : null;

    if( empty($login) || empty($password) || empty($confirmPassword)){
        $response = array('status' => 'error', 'message' => 'wrong data '.$login." -".$password."-".$confirmPassword);
        echo json_encode($response);
        exit();
    }

    if (!($password ===$confirmPassword)){
        $response = array('status' => 'error', 'message' => 'Пароль и подтверждение не совпадают');
        echo json_encode($response);
        exit();
    }

    $query = "SELECT * FROM users WHERE login= '$login' AND password = '$password'";

    $check_result = mysqli_query($connect, $query);
    if (mysqli_num_rows($check_result) > 0){
        $response = array('status' => 'error', 'message' => 'Пользователь с данным логином уже существует');
        echo json_encode($response);
        exit();
    }

    $insert_query = "INSERT INTO users VALUES (NULL, '$login', '$password')";
    $insert_result = mysqli_query($connect, $insert_query);

    if ($insert_result) {
        $query = "SELECT * FROM users WHERE login='$login' AND password = '$password'";
        $result = mysqli_query($connect, $query);
        $user = mysqli_fetch_assoc($result);

        $id = $user['id'] ? $user['id'] : null;
        set_session($id, $login);


        $response = array('status' => 'success', 'message' => 'Пользователь создан', 'id' => $id);
        echo json_encode($response);
    } else {
        $response = array('status' => 'error', 'message' => 'Ошибка базы данных');
        echo json_encode($response);
    }


 
?>
