<?php

function checkFile(){
    $valid_file = true;
    $imageType = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION); // Kollar filtypen

    $new_file_name = strtolower($_FILES['image']['name']);

    if($imageType != "jpg" && $imageType != "png" && $imageType != "jpeg" && $imageType != "gif"){
        $valid_file = false;
        $message = "Filen är inte en giltig filtyp";
    }

    if($_FILES['image']['size'] > (2048000)) //Kolla så att bilden inte är större än 2MB
    {
        $valid_file = false;
        $message = 'Filens storlek var för stor, max 2 MB';
    }
    if(file_exists("pics/" . $new_file_name)){ // Kolla om filen redan finns
        $temparr = randomName($new_file_name); // Isåfall skapa ett slumpmässigt filnamn
        if($temparr[0] == true){
            $new_file_name = $temparr[1];
        }
        else{
            $valid_file = false;
            $message = $temparr[1];
        }
    }
    if($valid_file){ // Slutgiltiga testet att kolla om allt har gått rätt
        if(!move_uploaded_file($_FILES['image']['tmp_name'], $_SERVER["DOCUMENT_ROOT"] . "/webshop/pics/" . $new_file_name)){
            echo("<div class='alert alert-danger' role='alert'><p>Något gick fel...</p></div>");
            return false;
        }
    }
    else{ // Om filen misslyckades i något test
        echo("<div class='alert alert-danger' role='alert'><p>{$message}</p></div>"); // Ska skrivas ut i en alert
        return false;
    }
    return array(true, $new_file_name);
}

function randomName($oldfilename){ // Retunerar en array med anting false och ett felmeddelande eller true med ett filnamn
    require("RandDotOrg.php");

    $temparr = explode(".", $oldfilename);
    if(sizeof($temparr) != 2){ // Om filnamnet innehåller fler än 2 punkter så är något fel, kan tex vara "bild.jpg.php"
        return array(false, "Filnamnet innehåller fler eller mindre än en punkt");
    }
    $filetype = "." . $temparr[1]; // Få fram filtypen till en string
    $ran = new RandDotOrg();
    $filename = $ran->get_strings(1, 10, False) . $filetype;
    echo($filename);
    return array(true, $filename);
}
?>