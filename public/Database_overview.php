<?php
    include("layouts/Header.php");
    include("Database_Manager.php");
?>


<?php
    echo "<br><br><br>Inspection Table:<br><br><br>";

    $sql = "SELECT * FROM Inspection";
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
    echo "<br><br><br>Landlord Table:<br><br><br>";

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
    echo "<br><br><br>Payment Table:<br><br><br>";

    $sql = "SELECT * FROM Payment";
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
    echo "<br><br><br>Property Table:<br><br><br>";

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


<?php
    echo "<br><br><br>Property Services Table:<br><br><br>";

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


<?php
    echo "<br><br><br>Rental Table:<br><br><br>";

    $sql = "SELECT * FROM Rental";
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
    echo "<br><br><br>Renter Table:<br><br><br>";

    $sql = "SELECT * FROM Renter";
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
    echo "<br><br><br>Services Table:<br><br><br>";

    $sql = "SELECT * FROM Services";
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
    include("layouts/Footer.html");
    mysqli_close($conn);
?>