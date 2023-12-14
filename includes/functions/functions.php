<?php

/******************************
        Number Of Functions = 6 
******************************/

/* 
    Function Get Title Page 
*/
        function getTitle(){

                global $pageTitle;

                if( isset($pageTitle) ){

                        echo 'JobZone / ' . $pageTitle;

                } else{

                        echo 'Default';
                }
        };

/* 
    Function Check Item In DataBase 
*/
        function checkItem($select , $from , $value){

                global $connect;

                $quary = $connect->prepare(" SELECT $select 
                                             FROM $from 
                                             WHERE $select = ? 
                                        "); // WHERE $select = $value

                $quary->execute(array($value));

                $count = $quary->rowCount();

                return $count;
        }

/* 
    Function Get Posted Date
*/
        function getDatePosted ( $dateOfPublication ){

                $dateOfPublication = explode("-" , $dateOfPublication);

                $year  = $dateOfPublication[0];
                $month = $dateOfPublication[1];
                $day   = $dateOfPublication[2];

                if( date('d') < $day ){

                        $useMonth  = date('m') - 1;
                        $resultDay =  ( date('d') + 30 ) - $day;
                
                        if( $useMonth < $month){
                
                                $useYear  = date('Y') - 1;
                                $useMonth = $useMonth + 12;
                
                                $resultMonth = $useMonth - $month;
                
                                if( $useYear == $year ){
                
                                        $resultYear = 0;
                
                                } elseif( $useYear > $year ){
                
                                        $resultYear = $useYear - $year;
                                }
                
                        } elseif( $useMonth > $month ){
                
                                $resultMonth = $useMonth - $month;
                
                                if( date('Y') == $year ){
                
                                        $resultYear = 0;
                
                                } elseif( date('Y') > $year ){
                
                                        $resultYear = date('Y') - $year;
                                }
                
                        } elseif ( $useMonth == $month ){
                
                                $resultMonth = 0;
                
                                if( date('Y') == $year ){
                
                                        $resultYear = 0;
                
                                } elseif( date('Y') > $year ){
                
                                        $resultYear = date('Y') - $year;
                                }
                
                        }
                

                } else if( date('d') > $day ){
                
                        $resultDay = date('d') - $day;
                
                        if( date('m') < $month ){
                
                                $useYear  = date('Y') - 1;
                                $useMonth = date('m') + 12;
                
                                $resultMonth = $useMonth - $month;
                
                                if( $useYear == $year ){
                
                                        $resultYear = 0;
                
                                } elseif( $useYear > $year ){
                
                                        $resultYear = $useYear - $year;
                                }
                
                
                        } elseif( date('m') > $month ){
                
                                $resultMonth = date('m') - $month;
                
                                if( date('Y') == $year ){
                
                                        $resultYear = 0;
                
                                } elseif( date('Y') > $year ){
                
                                        $resultYear = date('Y') - $year;
                                }
                
                        } elseif( date('m') == $month ){
                
                                $resultMonth = 0;
                
                                if( date('Y') == $year ){
                
                                        $resultYear = 0;
                
                                } elseif( date('Y') > $year ){
                
                                        $resultYear = date('Y') - $year;
                                }
                        }
                

                } else{
                
                        $resultDay = 0;
                
                        if( date('m') < $month ){
                
                                $useYear  = date('Y') - 1;
                                $useMonth = date('m') + 12;
                
                                $resultMonth = $useMonth - $month;
                
                                if( $useYear == $year ){
                
                                        $resultYear = 0;
                
                                } elseif( $useYear > $year ){
                
                                        $resultYear = $useYear - $year;
                                }
                
                        } elseif( date('m') > $month ){
                
                                $resultMonth = date('m') - $month;
                
                                if( date('Y') == $year ){
                
                                $resultYear = 0;
                
                                } elseif( date('Y') > $year ){
                
                                $resultYear = date('Y') - $year;
                                }
                
                        } elseif( date('m') == $month ){
                
                                $resultMonth = 0;
                
                                if( date('Y') == $year ){
                
                                $resultYear = 0;
                
                                } elseif( date('Y') > $year ){
                
                                $resultYear = date('Y') - $year;
                                }
                        }
                }
                

                if( $resultDay == 0 ){
                
                        $resultDay = "";
                
                } elseif( $resultDay == 1 ){
                
                        $resultDay = $resultDay . " day "; 

                } else{
                        $resultDay = $resultDay . " days ";
                }
                
                if( $resultMonth == 0 ){
                
                        $resultMonth = "";
                
                } elseif( $resultMonth == 1 ){
                
                        $resultMonth = $resultMonth . " month . "; 

                } else{
                        $resultMonth = $resultMonth . " months . "; 
                }
                
                if( $resultYear == 0 ){
                
                        $resultYear = "";
                
                } elseif( $resultYear == 1 ){
                
                        $resultYear = $resultYear . " year . "; 

                } else{
                        $resultYear = $resultYear . " years . "; 
                }
                
                if( $resultDay == 0 && $resultMonth == 0 && $resultYear == 0 ){
                
                        $resultDate = "Posted Today <span style='color:red;'> ( New ) <span>";
                
                } else{
                
                        $resultDate = 'Posted ' . $resultYear . $resultMonth . $resultDay . ' ago';
                }

                return $resultDate;
        }


/* 
    Function Get Item Count
*/
        function getItemCount($item , $table , $where = NULL , $and = NULL){

                global $connect;

                $quary = $connect->prepare(" SELECT COUNT($item) as count
                                             FROM $table
                                             $where $and
                                             LIMIT 1
                                        ");

                $quary->execute();

                $count = $quary->fetch();

                return $count["count"];
        }

/* 
    Function Get Item Count
*/
        function countAdvirtismentCategory_subcategory($item , $table , $category_subcategory , $id){

                global $connect;

                $quary = $connect->prepare(" SELECT COUNT($item) as count
                                        FROM $table
                                        WHERE $category_subcategory = $id
                                        LIMIT 1
                                        ");

                $quary->execute();

                $count = $quary->fetch();

                return $count["count"];
        }


/* 
    Function redirect page 
*/
        function redirectPage( $typeAlert , $paragraphAlert , $redirectLink = null , $seconds = null ){

                if( $typeAlert == 'success' ){

                        echo '<div class="alert alert-success">';
                                echo '<i class="fa fa-check"></i>';
                                echo '<p>'. $paragraphAlert .'</p>';
                        echo '</div>';

                } elseif( $typeAlert == 'danger' ){

                        echo '<div class="alert alert-danger">';
                                echo '<i class="fa fa-times"></i>';
                                echo '<p>'. $paragraphAlert .'</p>';
                        echo '</div>';

                } elseif( $typeAlert == 'info' ){

                        echo '<div class="alert alert-info">';
                                echo '<i class="fa fa-exclamation"></i>';
                                echo '<p>'. $paragraphAlert .'</p>';
                        echo '</div>';

                        header("refresh: $seconds; url=$redirectLink");
                        exit();
                }
        }