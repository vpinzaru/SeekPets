<?php

function get_conn()
{
 $dbhost = "localhost";
 $dbuser = "root";
 $dbpass = "";
 $db = "pawtaie";
 $conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);

 if ($conn->connect_error) {
    show_result("error","Failed to connect to the database",409);
    close_conn($conn);
    exit();
}
 
 return $conn;
 
};

function close_conn($conn)
{
    $conn -> close();
};


function generic_query($fieldValue, $fieldName, $table, $conn)
{
    $sql = "select * from ". $table . " where ". $fieldName . " = " . " '".$fieldValue."'";
    $result = $conn->query($sql);
    return $result;
};
?>
