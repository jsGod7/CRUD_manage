<?php
include 'config.php';
$id = $_GET['id'];
$sql = "DELETE FROM students WHERE id=$id";
$conn->query($sql);
header("Location: index.php");
?>