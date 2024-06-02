<?php

session_start();
if (isset($_SESSION['sd_id'])) {
    unset($_SESSION['sd_id']);
} elseif (isset($_SESSION['teacher_id'])) {
    unset($_SESSION['teacher_id']);
}
?>

<script>
    window.location.href = 'index.html';
</script>