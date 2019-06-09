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

function update_persistence($id)
{
    $ip = $_SERVER['REMOTE_ADDR'];
    $conn = get_conn();
    
    $sql = "select id_pers from persistence where ip='".$ip."' and id_user=".$id;

    $result = $conn->query($sql);
    if($result->num_rows == 0)
    {
        $sql = "insert into persistence (id_user, timestamp, ip) values (".$id.", ".time().", '".$ip."')";
        $conn->query($sql);
        close_conn($conn);
        return 'added';
    }

    $id_pers = $result->fetch_assoc();
    $sql = "update persistence set timestamp=".time()." where id_pers = ".$id_pers['id_pers'];
    $conn->query($sql);
    close_conn($conn);
    return 'updated';
}

function check_persistence()
{
    $timeframe = 60; // 1 min for tests
    $ip = $_SERVER['REMOTE_ADDR'];
    $conn = get_conn();
    $sql = "select timestamp, id_user from persistence where ip='".$ip."'";

    $result = $conn->query($sql);
    if($result->num_rows == 0)
    {
        close_conn($conn);
        return 'not ok';
    }

    $time = $result->fetch_assoc();
    if(time() - $time['timestamp'] > $timeframe)
    {
        $sql = "delete from persistence where ip='".$ip."'";
        $conn->query($sql);
        close_conn($conn);
        return 'not ok';
    }
    close_conn($conn);
    return $time['id_user'];

}

?>
