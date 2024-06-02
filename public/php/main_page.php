<?php
    require_once('../../vendor/users/check_session.php');
    require_once("../../vendor/database.php");

    $is_main_page = false;
    $id = null;

    if (!isset($_GET['id'])) {
        $is_main_page = true;
        $id = $_SESSION['user_id'];
    }
    else if ($_GET['id'] == $_SESSION['user_id']) { 
        $is_main_page = true;
        $id = $_SESSION['user_id'];
    }
    else {
        $is_main_page = false;
        $id = $_GET['id'];
    }

    $user = null;
    $query = "SELECT * FROM users WHERE id = '$id'";
    $result = mysqli_query($connect, $query);


    if (mysqli_num_rows($result) == 0){
        header("Location: page404.php");
    }

    $user = mysqli_fetch_assoc($result);
    $user_login = $_SESSION['user_login'];
    $user_id = $id;

    $query = "SELECT publications.*, users.login FROM ( SELECT * FROM publications WHERE creator IN ( SELECT subscribed_to FROM subscriptions WHERE subscriber = $id) UNION SELECT * FROM publications WHERE creator = $id) publications JOIN users ON publications.creator = users.id ORDER BY publications.id DESC;";
    /*
    получается такая таблица
    id title text creator login
    */
    $publications_results = mysqli_query($connect, $query);
    $publications = array();
    while($row = mysqli_fetch_assoc($publications_results)){
        $publications[] = $row;
    }


    $query = "SELECT COUNT(*) as number_of_posts FROM publications WHERE creator=$user_id";

    $tyapka = mysqli_query($connect, $query);

    $number_of_my_posts = mysqli_fetch_assoc($tyapka)["number_of_posts"];

    
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../styles/main.css" rel='stylesheet'>
    <link href="../styles/popup.css" rel='stylesheet'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../scripts/main_page.js" defer></script>
    <script src="../scripts/popup.js" defer></script>
    <title>Главная</title>
    <style>
        .hidden{
            display: none;
            
        }
        .number_of_posts{
            background: white;
            padding: 10px;
            border-radius: 15px;
        }
    </style>
</head>
<body>
    <?php
        require_once("header.php");
       
    ?>
    <div class = profile_head>
        <div>
            <p>Добро пожаловать, @<?php echo $user_login;?></p>
            <i>Вы находитесь на странице<br/>@<?php echo $user['login'];?></i>
        </div>

        <a href='../../vendor/users/signout.php'>Выйти из учетной записи @<?php echo $user_login;?></a>
    </div>

    

    <div class = container>
        <div class="publications">
            <div class = "hidden edit_form">
                Для изменения кликнуть по тексту или по области с картинкой
                <div class = "publication">
                    <form id="edit_form">
                        <p class = "publication_title editable">jyst_test</p>
                        <p class = "publication_text editable">jyst_test</p>
                        <div class = "publication_image editable">
                            <p>Измените изображение кликом по области</p>
                            <img id = destination_image width=400 />
                        </div>
                        <div class=actions>
                            <button type="submit" class = "send_editing">Редактировать</button>
                            <button type = "button" class = "stop_editing">Отменить</button>
                        </div>
                    </form>
            </div>
        </div>



            <?php
                if (count($publications)){
                    
                    for($i = 0; $i < count($publications); $i++){
                        $row = $publications[$i];
                        ?>
                        <div class = "publication">
                            <div class=publication_container>
                                <div class = publication_title><?php echo $row['title'];?></div>
                                <a href = 'main_page.php?id=<?php echo $row['creator'];?>'>@<?php echo $row['login'];?></a>
                            </div>
                            
                            <div class = 'publication_text'><?php echo $row['text']?></div>
                            <div class = 'publication_image'>
                                <?php
                                    if ($row['image_path'] != ""){ ?>
                                        <img src = "../../uploads/<?php echo $row['image_path']?>" alt = <?php echo $row['image_path']?>, width=400/>
                                <?php    
                                    }
                                ?>
                                
                            </div>
                            <div class = actions>
                                <?php
                                    if ($row['creator'] == $user_id){?>
                                        <button class = "drop" data-id = <?php echo $row['id']?>>Удалить</button>
                                        <button class = "edit" data-id = <?php echo $row['id']?>>Изменить</button>
                                <?php
                                    }
                                    else{ ?>
                                        <!-- <button class = "like" data-id = <?php echo $row['id']?>>Лайкнуть</button> -->
                                <?php
                                    }
                                
                                ?>
                            </div>

                        </div> 
                        <?php
                    }
                }
                
                else{
                    echo "Еще нет публикаций";
                }
            ?>
        </div>
            <div>

            <?php require_once('navigation.php');?>
            <div class = "number_of_posts">
                <h2>Вы имеете <p id="posts_counter"><? echo $number_of_my_posts;?></p> поста/ов </h2>
                <button class = "update_posts">Обновить</button>
            </div>
            </div>

        </div>
        </div>
    </div>
    </div>

    





    

    <?php
        require_once("footer.php");
    ?>

    <script>
        let formData = new FormData();
        $(".update_posts").click(function(){
            $.ajax({
            url: '../../vendor/publications/update_counter.php', 
            type: 'POST',
            data: formData, 
            processData: false, 
            contentType: false, 
            success: function(response) { 
                console.log("registerYAY");
                console.log(response)
                data = JSON.parse(response)
                let received = "";
                if(data['status'] == 'success'){
                    console.log("error in data")
                    $("#posts_counter").html(data["number_of_posts"])
                    received = "Вы обновили количесто постов<br/> Количество ваших постов: "+ data["number_of_posts"];
                }
                let status = data['status'];
                let message = data['message'];


                showPopup(status, message, received);
                console.log(data);
                
            },
            error: function(xhr, status, error) { 
                if (xhr.status === 400) {
                    showPopup(xhr.status, "Ошибка 400", "");
                } else {
                    showPopup(xhr.status, "Ошибка        })", String(error));
                    console.log(String(error));
                }
            }
        });
    });
    </script>
</body>
</html>
