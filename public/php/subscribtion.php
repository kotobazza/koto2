<?php
    require_once('../../vendor/users/check_session.php');
    require_once("../../vendor/database.php");

    $user_id = $_SESSION['user_id'];
    $user_login = $_SESSION['user_login'];

    $query = "
        SELECT 
            u.id,
            u.login,
            CASE 
                WHEN s.subscriber IS NOT NULL THEN 1
                ELSE 0
            END AS is_subscribed
        FROM 
            users u
        LEFT JOIN 
            (SELECT subscribed_to, subscriber 
            FROM subscriptions
            WHERE subscriber = $user_id) s
        ON 
            u.id = s.subscribed_to
        ORDER BY u.id DESC;
    ";

    $result = mysqli_query($connect, $query);
    if(!$result){
        header("Location: page404.php");
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../styles/main.css" rel='stylesheet'>
    <link href="../styles/popup.css" rel='stylesheet'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="../scripts/subscriptions.js" defer></script>
    <script src="../scripts/popup.js" defer></script>
    <title>Подписки</title>
</head>
<body>
    <?php
        require_once("header.php");
       
    ?>

    <div class = "container">
        <div class = "users_list">


            <?php while($row = mysqli_fetch_assoc($result)){
                if ($row['id'] == $user_id){
                    continue;
                }
            ?>
                <div class = "user">
                    <div class = "user_login"><a href = "main_page.php?id=<?php echo $row['id'];?>">@<?php echo $row['login'];?></a></div>
                    <?php
                            if($row["is_subscribed"] == "1"){
                    ?>
                        <div class = subber>
                            <div class = "status">Подписан</div>
                            <form class="unsubscribe">
                                <button type="submit" data-id = "<?php echo $row['id']?>">Отписаться</button>
                            </form>
                        </div>

                    <?php } else {?>
                        <div class = subber>
                            <div class = "status">Не подписан</div>
                            <form class="subscribe">
                                <button type="submit" data-id = "<?php echo $row['id']?>">Подписаться</button>
                            </form>
                        </div>
                    <?php }?>
                </div>
            <?php }?>
        </div>

        <?php require_once('navigation.php');?>
    </div>
    </div>

    <?php
        require_once("footer.php");
    ?>
    
</body>
</html>