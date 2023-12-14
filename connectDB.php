<?php

    $dsn = 'mysql:host=localhost;dbname=job_zone'; // Data Source Name
    $user = 'root';
    $password = '';
    $option = array(

        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' , 
    );

    try {

        $connect = new PDO($dsn , $user , $password , $option);
        $connect->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
    }

    catch(PDOException $error){

        echo "Faild To Coonect" . $error->getMessage();
    }