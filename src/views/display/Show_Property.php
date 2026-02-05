<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property info</title>
    <link rel="stylesheet" href="../../public/css/style.css">
    <link rel="stylesheet" href="../../public/css/header.css">
    <link rel="stylesheet" href="../../public/css/tables.css">
    <link rel="stylesheet" href="../../public/css/forms.css">
    <link rel="stylesheet" href="../../public/css/utilities.css">
    <link rel="stylesheet" href="../../public/css/animations.css">
</head>
<body>
<?php
    include("Header.html");
    include("Database_Manager.php");
?>
    <h2>Property informations: <br></h2>

<?php
    $sql = "SELECT * FROM Property";
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
    include("Footer.html");
    mysqli_close($conn);
?>
</html>