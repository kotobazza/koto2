<?php
    require_once("../users/check_session.php");
    require_once("../database.php");

    if (!$_SERVER['REQUEST_METHOD'] === 'POST'){
        echo json_encode(["status" => "error", "message" => "Неправильный тип запроса"]);
        exit();
    }

    if (!(isset($_REQUEST['pending_to_delete']))){
        echo json_encode(["status" => "error", "message" => "Неправильный формат обращения к серверу"]);
        exit();
    }

    $publication_id = $_REQUEST['pending_to_delete'];
    $user_id = $_SESSION['user_id'];

    $check_query = "SELECT * FROM publications WHERE id = '$publication_id'";

    $check_result = mysqli_query($connect, $check_query);

    if (mysqli_num_rows($check_result) == 0){
        echo json_encode(['status' => "error", 'message' => "Этой публикации уже не существует"]);
        exit();
    }

    $publication = mysqli_fetch_assoc($check_result);

    if (!($publication['creator'] === $user_id)){
        echo json_encode(['status' => "error", 'message' => "У вас нет прав на удаление этой публикации"]);
        exit();
    }



    $publication_title = $publication['title'];
    $publication_text = $publication['text'];
    $publication_image = $publication['image_path'];


    $delete_query = "DELETE FROM publications WHERE id = '$publication_id'";
    $result = mysqli_query($connect, $delete_query);

    if(!$result){
        echo json_encode(['status' => "error", 'message' => "Ошибка базы данных"]);
        exit();
    }
    echo json_encode(['status' => "success", 'message' => "Публикация успешно удалена", 'publication_title' => $publication_title]);


?>