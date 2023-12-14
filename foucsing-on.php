<?php
    include 'initialize.php';
    
   $foucsing = $connect->prepare("SELECT * FROM focusing_on WHERE category_id = {$_POST["id"]}");

   $foucsing->execute();

   $getFoucsing = $foucsing->fetchAll();

   foreach($getFoucsing as $foucs){

        echo "<option value='" . $foucs['f_id'] . "'>" 
                . $foucs['f_name'] . 
             "</option>";
   }
?>