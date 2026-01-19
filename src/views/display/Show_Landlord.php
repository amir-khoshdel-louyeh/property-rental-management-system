<?php
    include("Header.html");
    include("Database_Manager.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landlord info</title>
</head>
<body>
    <h2>Landlord informations: <br></h2>
</body>
</html>

<?php
    $sql = "SELECT * FROM Landlord";
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

<?php
    include("Footer.html");
    mysqli_close($conn);
?>