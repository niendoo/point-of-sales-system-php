<?php
//connection to mysql database
try{
    $connection = new PDO('mysql:host=localhost; dbname=pos_database','root', '');
    //echo 'Connection Successful ';
}

catch(PDOException $f){
    echo $f->getMessage();
}




?>