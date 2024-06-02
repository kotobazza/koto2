<?php
    require_once("../users/check_session.php");
    require_once("../database.php");

    if (!$_SERVER['REQUEST_METHOD'] === 'POST'){
        echo json_encode(["status" => "error", "message" => "Неправильный тип запроса"]);
        exit();
    }

    if (!(isset($_REQUEST['publication_id']))){
        echo json_encode(["status" => "error", "message" => "Неправильный формат запроса к серверу"]);
        exit();
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
            echo json_encode(["status" => "error", "message" => "Ошибка с файлом на стороне сервера", 'newpath' => $upload_path, 'document_root' => $_SERVER['DOCUMENT_ROOT']]);
            exit();
        }
    }

    $id = $_REQUEST['publication_id'];

    $query = "SELECT * FROM publications WHERE id = '$id'";
    $result = mysqli_query($connect, $query);
    if(!($result)){
        echo json_encode(["status" => "error", "message" => "error in database"]);
    }
    $old_publication = mysqli_fetch_assoc($result);

    $publication_title = isset($_REQUEST['publication_title']) ? $_REQUEST['publication_title'] : $old_publication['title'];
    $publication_text = isset($_REQUEST['publication_text']) ? $_REQUEST['publication_text'] : $old_publication['text'];
    $publication_image = $upload_file != "" ? $upload_file : $old_publication['image_path'];
    $creator = $_SESSION['user_id']; //не меняется


    $query = "UPDATE publications SET title='$publication_title', text='$publication_text', image_path='$publication_image' WHERE id='$id'";
    $result = mysqli_query($connect, $query);

    if($result){
        echo json_encode(
            ["status" => "success", 
            "message" => "Публикация успешно изменена",
            "publication_id" => $id, 
            "title" => $publication_title, 
            'text' => $publication_text, 
            'image' => $publication_image, 
            'old_publication_title' => $old_publication['title'],
            'old_publication_text' => $old_publication['text'],
            'old_publication_image' => $old_publication['image_path'],
        ]);
    }
    else{
        echo json_encode(['status' => "error", 'message' => "error in db"]);
    }

    
?>