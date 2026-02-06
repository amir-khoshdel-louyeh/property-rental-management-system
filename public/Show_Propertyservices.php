<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property services info</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/tables.css">
    <link rel="stylesheet" href="css/forms.css">
    <link rel="stylesheet" href="css/utilities.css">
    <link rel="stylesheet" href="css/animations.css">
</head>
<body>
<?php
    include("../layouts/Header.html");
    include("../config/Database_Manager.php");
?>
    <h2>Property services informations: <br></h2>

<?php
    $sql = "SELECT * FROM Propertyservices";
    $result = $conn->query($sql);

    if ($result === FALSE) {
        echo "Error querying database: " . $conn->error;
    }


    if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr>";
        $fields = $result->fetch_fields();
        foreach ($fields as $field) {
            echo "<th>" . $field->name . "</th>";
        }
        echo "</tr>";

    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>" . $value . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    } else {
    echo "0 results";
    }

?>
</body>
<?php
    include("../layouts/Footer.html");
    mysqli_close($conn);
?>
</html>