<?php

$con = mysqli_connect("localhost", "root", "", "aclc_room_scheduling2");

if(!$con){
    die("Connection Failed: " .mysqli_connect_error());
}