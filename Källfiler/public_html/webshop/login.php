<?php
require("database.php");
session_start();
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="utf8" />
    <title>Webbshop</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>

<div id="wrapper">
    <div id="header">
        <h1>Ardushop</h1>
        <?php echo("<p>Inloggad som: " . $_SESSION["user"]); ?>
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="index.php">Huvudmeny</a>
                </div>


                <ul class="nav navbar-nav">
                    <li><a href="#">Populära Produkter</a></li>
                    <li><a href="#">Kategorier</a></li>
                    <li><a href="#">Om Oss/Kontakt</a></li>
                    <?php
                    if(empty($_SESSION["user"])){
                        echo("<li><a href='createuser.php'>Skapa användare</a></li>");
                        echo("<li><a href='login.php'>Logga in</a></li>");
                    }
                    else{
                        if($_SESSION["user"] == "admin"){
                            echo("<li><a href='addproduct.php'>Lägg in produkt</a></li>");
                        }
                        echo("<li><a href='cartpage.php'>Kundvagn</a></li>");
                        echo("<li><a href='logout.php'>Logga ut</a></li>");
                    }
                    ?>
                </ul>
        </nav>
        <div id="main">
            <div id="categories">
                <ul class="list-group">

                    <?php
                    $categories = getCategories();
                    foreach($categories as $category){
                        echo("<li class='list-group-item'><a href='http://hampus.teknikprogrammet.org/webshop/index.php?category={$category}' >{$category}</a></li>");
                    }
                    ?>
                </ul>
            </div>
            <div id="login" class="btn-group" role="group" aria-label="...">
                <?php
                if(isset($_POST['username'])){
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    $sha_pass = sha1($password . $password[1]);

                    $sql = "SELECT password FROM users WHERE username='{$username}'";
                    $stmt = $dbh->prepare($sql);
                    $stmt->execute();
                    $dbpass = $stmt->fetch();
                    if($dbpass["password"] == $sha_pass){
                        $_SESSION["user"] = $username;
                        $_SESSION["cart"] = getUserCart($_SESSION["user"]);
                        echo("<p>Du är nu inloggad som: " . $_SESSION["user"] . "</p>");
                        header("Location: index.php");
                    }
                    else{
                        echo("<div class='alert alert-danger' role='alert'><p>Fel användarnamn eller lösenord!</p></div>");
                    }
                }


                ?>
                <form action="login.php" method="POST">
                    <p>Användarnamn: <input type="text" name="username" /></p>
                    <p>Lösenord: <input type="password" name="password" /></p>
                    <input type="submit" value="Login" class="btn btn-default" />
                    <a href="index.php" id="backbutton"><button class="btn btn-default">Tillbaka</button></a>
                </form>

            </div>
        </div>
    </div>

</body>

</html>

