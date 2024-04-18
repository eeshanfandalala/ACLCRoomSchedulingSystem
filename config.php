<?php

$con = mysqli_connect("localhost", "root", "", "users");

if(!$con){
    die("Connection Failed: " .mysqli_connect_error());
}