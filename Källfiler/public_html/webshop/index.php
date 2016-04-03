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
                    <!-- Allt detta är för huvudmenyn -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="index.php">Startsida</a>
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
            <div id="products">
                <?php
                    if(isset($_GET["category"])){
                        $products = getProductsByCategory($_GET["category"], 0);
                        if(sizeof($products) == 0){ // Om det inte fanns några prdukter
                            echo("Sorry, det finns inga produkter i denna kategorin ännu");
                        }
                        foreach($products as $product){ // Börja rita ut varenda produkt och dess egenskaper
                            echo("<div class='product'>
                            <a href='productpage.php?id={$product->id}'><img src='pics/{$product->imageurl}' alt='{$product->name}'/></a>
                            <p> $product->name </p>
                            <p>Pris: $product->price </p>
                            <p>Lager: $product->stock </p>
                            </div>");
                        }
                    }
                ?>
            </div>
        </div>
    </div>

</body>

</html>