<?php

$con = mysqli_connect("localhost", "root", "", "__DB");

if(!$con){
    die("Connection Failed: " .mysqli_connect_error());
}