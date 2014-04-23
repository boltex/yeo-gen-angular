<?php
//silence is gold

function testfunction(){
    return "Hello from _includes/functions.php" ;
}

function ocx_connobjp(){
    global $conn, $db_database, $db_user, $db_password, $options ;
    if (function_exists('db2_pconnect')) { 
        //$options = array( 'i5_lib'=>'OBJP' );
        //$conn = db2_pconnect( $db_database, $db_user, $db_password, $options ); 
        $options = array( 'i5_naming'=>DB2_I5_NAMING_ON );
        $conn = db2_pconnect( $db_database, $db_user, $db_password, $options ); 
    }
}

function testsp0120(&$response){
    global $conn ;
    ocx_connobjp();
    /*
    $sql = "SELECT PERMALINK, ID, PAGE_ID, LANGUAGE_ID FROM CMS_PAGES_BY_LANGUAGE
                WHERE PAGE_ID != 43
                ORDER BY ID";
    */          
    $trackingNumber = "1312131";
    $equipmentNumber = "";
    $referenceNumber = "";
    $trackingNumber = str_pad($trackingNumber, 7, "0", STR_PAD_LEFT);
    $equipmentNumber =  str_pad($equipmentNumber, 7, " ", STR_PAD_RIGHT);
    $referenceNumber =   str_pad($referenceNumber, 25, " ", STR_PAD_RIGHT);
    $language = 1  ; 
    $sql = "CALL SP0120('" . $trackingNumber . "', '00', '".$equipmentNumber."', '".$referenceNumber."', '" . $language . "')";


    $params = array( );
    $stmt = db2_prepare($conn, $sql);

    $response = array_merge(
        $response,
        array('success' => true,
              'message' => 'sp0120 called'
            )
    ); 

    if ($stmt) { 
        $result = db2_execute($stmt, $params); 
        if ($result) { 
            // select worked 
            while ($row = db2_fetch_object($stmt)) {
                foreach($row as $key => $value) {
                    if (!is_null($value)) {
                        $row->$key = utf8_encode($value);
                    }
                }
                $resultset[]= $row ;
            }
            $response = array_merge(
                $response,
                array( 'resultset' => $resultset )
            );
        }else{
            $response = array_merge(
                $response,
                array( 'error' => 'in testsp0120 db2_execute, SQLSTATE value: '.db2_stmt_error($stmt).' Message: '.db2_stmt_errormsg($stmt) ) 
            ); 
        }
    }else{
        $response = array_merge(
            $response,
            array( 'error' => 'in testsp0120 db2_prepare, SQLSTATE value: '.db2_stmt_error().' Message: '.db2_stmt_errormsg() ) 
        ); 
    }  
}

?>