<?php
    require_once("../users/check_session.php");
    require_once("../database.php");

    $user_id = $_SESSION['user_id'];

    if (!$_SERVER['REQUEST_METHOD'] === 'POST'){
        echo json_encode(["status" => "error", "message" => "Неправильный тип запроса"]);
        exit();
    }

    if(!(isset($_REQUEST['subscribe_to']) || isset($_REQUEST['unsubscribe_from']))){
        echo json_encode(['status'=>'error', 'message' => 'Неправильный формт запроса: пустые данные']);
        exit();
    }

    

    if(isset($_REQUEST['unsubscribe_from'])){
        $requested_id = $_REQUEST['unsubscribe_from'];
        $select_user = "SELECT * FROM users WHERE id = '$requested_id'";
        $result = mysqli_query($connect, $select_user);
        if(mysqli_num_rows($result)==0){
            echo json_encode(['status'=>'error', 'message' => 'Пользователя, от которого вы хотите отписаться, не существует', "id"=> $requested_id]);
            exit();
        }

        $subscription = mysqli_fetch_assoc($result);
        $subscription_login = $subscription['login'];
        $subscription_id = $subscription['id'];



        $select_query = "SELECT * FROM subscriptions WHERE subscriber = '$user_id' AND subscribed_to = '$subscription_id'";
        $result = mysqli_query($connect, $select_query);
        if(mysqli_num_rows($result)==0){
            echo json_encode(['status'=>'error', 'message' => 'Вы еще не подписаны на этого пользователя']);
            exit();
        }

        $delete_query = "DELETE FROM subscriptions WHERE subscriber = '$user_id' AND subscribed_to = '$subscription_id'";
        
        $result = mysqli_query($connect, $delete_query);
        if(!$result){
            echo json_encode(['status'=>'error', 'message' => 'Ошибка базы данных']);
            exit();
        }
        echo json_encode(['status'=>'success', 'message' => 'Вы отписались от пользователя', "login"=>$subscription_login]);


        
    }

    if(isset($_REQUEST['subscribe_to'])){
        $requested_id = $_REQUEST['subscribe_to'];
        $select_user = "SELECT * FROM users WHERE id = '$requested_id'";
        $result = mysqli_query($connect, $select_user);
        if(mysqli_num_rows($result)==0){
            echo json_encode(['status'=>'error', 'message' => 'Пользователя, на которого вы хотите подписаться, не существует']);
            exit();
        }

        $subscription = mysqli_fetch_assoc($result);
        $subscription_login = $subscription['login'];
        $subscription_id = $subscription['id'];


        $select_query = "SELECT * FROM subscriptions WHERE subscriber = '$user_id' AND subscribed_to = '$subscription_id'";
        $result = mysqli_query($connect, $select_query);
        if(mysqli_num_rows($result)>0){
            echo json_encode(['status'=>'error', 'message' => 'Вы уже подписались на этого пользователя']);
            exit();
        }

        $insert_query = "INSERT INTO subscriptions VALUES (NULL, '$subscription_id', '$user_id')";
        $result = mysqli_query($connect, $insert_query);
        if(!$result){
            echo json_encode(['status'=>'error', 'message' => 'Ошибка базы данных']);
            exit();
        }
        echo json_encode(['status'=>'success', 'message' => 'Вы подписались на пользователя', "login"=>$subscription_login]);

    }
    
?>