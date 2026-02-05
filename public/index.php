<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOME</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/tables.css">
    <link rel="stylesheet" href="css/forms.css">
    <link rel="stylesheet" href="css/utilities.css">
    <link rel="stylesheet" href="css/animations.css">
</head>
<body>
<?php
    include("../src/views/layouts/Header.html");
?>
    <h1>Welcome to my project</h1>
    <h1>in the Header you can access to each part of the site ⭡⭡ <br><br>
    or you can use the below links ⭣⭣ <br></h1>
    
    <h2>
        <a href="Path_Insert.php">Insert new data to the tables</a><br><br>
        <a href="Path_Delete.php">Delete data from tables</a><br><br>
        <a href="Path_View.php">View the database tables</a><br><br>
    </h2>


</body>
<?php
    include("../src/views/layouts/Footer.html");
?>
</html>
