<?php

$con = mysqli_connect("localhost", "root", "", "aclc_room_scheduling");

if(!$con){
    die("Connection Failed: " .mysqli_connect_error());
}