<?php
// Start PHP code

// Variables
$title = "My First PHP Page";
$name = "Guest";

// Check if a name is provided via URL (e.g., ?name=John)
if (isset($_GET['name'])) {
    $name = htmlspecialchars($_GET['name']);
}
?>

