<?php
require("database.php");
require("functions.php");
session_start();
?>

<!DOCTYPE html>
<html lang="sv">
<head>
    <meta charset="utf8" />
    <title>Webbshop</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
    <script src="//cdn.ckeditor.com/4.4.7/basic/ckeditor.js"></script>
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
                if(isset($_POST["productname"])){
                    if(!empty($_POST["productname"])){
                        $productname = $_POST["productname"];
                        $price = $_POST["price"];
                        $stock = $_POST["stock"];
                        $category = $_POST["category"];
                        $description = $_POST["description"];
                        $filecheck = checkFile();
                        $new_file_name = $filecheck[1];
                        if($filecheck){ // Kolla om filen klarar testen och ladda upp den
                            if(addProduct($productname, $price, $stock, $category, $description, $new_file_name)){ // Lägg in den i databasen
                                echo("<div class='alert alert-success' role='alert'><p>Produkten finns nu i sortimentet</p></div>");
                            }
                        }
                    }
                }
                ?>

                <form action="addproduct.php" method="POST" enctype="multipart/form-data" autocomplete="off">

                    <p>Namn: <input type="text" name="productname"></p>
                    <p>Pris: <input type="text" name="price"></p>
                    <p>Lager: <input type="text" name="stock"></p>
                    <p>Kategori:<select name="category"></p>
                    <?php
                    $categories = getCategories();
                    foreach($categories as $category){
                        echo("<option value='{$category}'>{$category}</option>");
                    }
                    ?>
                    </select>
                    <textarea name="description" id="editor1" rows="10" cols="80">
                        Hello, write a good description here for the new product :)
                    </textarea>
                    <input type="file" name="image">
                    <script>
                        CKEDITOR.replace("editor1");
                    </script>
                    <input type="submit" value="Lägg in" class="btn btn-default" id="fileinput" />
                    <a href='index.php'><button class="btn btn-default" >Tillbaka</button></a>
                </form>

            </div>
        </div>
    </div>

</body>

</html>