<?php
include '../../../config.php';


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['userid']) && isset($_POST['field']) && isset($_POST['value'])) {
    $userid = $_POST['userid'];
    $field = $_POST['field'];
    $value = $_POST['value'];

    $stmt = $con->prepare("UPDATE subject_tb SET $field = ? WHERE subject_id = ?");
    $stmt->bind_param("si", $value, $userid);
    
    try {
        if ($stmt->execute()) {
            echo 'success';
        } else {
            echo 'error: ' . $stmt->error;
        }
    } catch (mysqli_sql_exception $e) {
        // Catch the specific trigger error
        if ($e->getCode() == 1644) { // Error code for user-defined exception (SIGNAL SQLSTATE '45000')
            echo 'Update prevented: A row with the same values already exists.';
        } else {
            echo 'error: ' . $e->getMessage();
        }
    }

    $stmt->close();
    exit;
}