<?php
    require_once("../users/check_session.php");
    require_once("../database.php");

    if (!$_SERVER['REQUEST_METHOD'] === 'POST'){
        echo json_encode(["status" => "error", "message" => "Неправильный тип запроса"]);
        exit();
    }

    if (!(isset($_REQUEST['publication_title']) && isset($_REQUEST['publication_text']))){
        echo json_encode(["status" => "error", "message" => "Неправильный формат запроса к серверу"]);
    }

    $upload_path = "";
    $upload_file = "";
    if (isset($_FILES['publication_image']) && $_FILES['publication_image']['error'] === UPLOAD_ERR_OK){
        $upload_file = $_FILES['publication_image']['name'];
        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
        $upload_path = $upload_dir . basename($_FILES['publication_image']['name']);
        if (move_uploaded_file($_FILES['publication_image']['tmp_name'], $upload_path)) {
            //echo json_encode(["status" => "success", "message" => "File successfully uploaded", 'newpath' => $uploadFile, 'document_root' => $_SERVER['DOCUMENT_ROOT']]);
        } else {
            echo json_encode(["status" => "error", "message" => "Ошибка сервера при работе с изображением", 'newpath' => $upload_path, 'document_root' => $_SERVER['DOCUMENT_ROOT']]);
            exit();
        }
        
    }
    $has_image = $upload_path == "" ? false : true;

    $title = $_REQUEST['publication_title'];
    $text = $_REQUEST['publication_text'];
    $user_id = $_SESSION['user_id'];

    $query = "INSERT INTO publications VALUES (NULL, '$title', '$text', '$user_id', '$upload_file')";

    $result = mysqli_query($connect, $query);
    $inserted_id = mysqli_insert_id($connect);

    

    if ($result){
        echo json_encode(["status" => "success", "message" => "Создана новая публикация", 'publication_id' => $inserted_id, 'publication_title' => $title, 'publication_text' => $text, 'got_iamge' => $has_image, "uploaded_image" => $upload_path]);
    }
    else{
        echo json_encode(["status" => "error", "message" => "Ошибка базы данных", 'publication_id' => $inserted_id, 'publication_title' => $title, 'publication_text' => $text, 'got_iamge' => $has_image, "uploaded_image" => $upload_path]);
    }




    



    


?>



