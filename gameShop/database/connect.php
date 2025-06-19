<?php
$HOST = 'localhost';
$USER = 'root';
$PASSWORD = '';
$DBNAME = 'gameShop';
$conn = new mysqli($HOST, $USER, $PASSWORD, $DBNAME);
if($conn -> connect_error){
    echo "$conn->connect_error";
    die("Connection Failed : ".$conn->connect_error);
}