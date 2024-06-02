<?
    require_once('../../vendor/users/check_session.php');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../styles/main.css" rel='stylesheet'>
    <link href="../styles/popup.css" rel='stylesheet'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../scripts/create.js" defer></script>
    <script src="../scripts/popup.js" defer></script>
    <title>Создать</title>
</head>
<body>
    <?php
        require_once("header.php");
    ?>
    <form id = create_form>
        <div class = publication>
            <div class = publication_title>
                <label for=publication_title>Напишите название публикации</label><br/>
                <input type=text name =publication_title>
            </div>
            <div class = publication_text>
                <label for=publication_text>Напишите основной текст публикации</label><br/>
                <textarea name=publication_text></textarea>
            </div>
            <div class = publication_image>
                <label for=publication_image>По желанию, вставьте изображение</label><br/>
                <input type=file name =publication_image>
            </div>

            <input class = "create_button" type=submit value = "Создать">
        </div>
    </form>


    <?php
        require_once("footer.php");
    ?>
    
</body>
</html>