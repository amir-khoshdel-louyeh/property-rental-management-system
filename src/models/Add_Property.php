<?php
    include("../../config/Database_Manager.php");
    include("../../config/Validation.php");
    include("../../src/views/layouts/Header.html");
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>new Property</title>
    </head>
    <body>
        
    <h2>Add a new Property</h2>
    <h3><< Please complete the form and press on Submit button >></h3><br>

        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 
            <h2>
            type of the property : 
            <select name="property_type" id="property_type">
            <option value="House">House</option>
            <option value="Apartment">Apartment</option>
            <option value="Garden">Garden</option>
            </select><br><br>
            
            situation of the property : 
            <select name="rent_sale" id="rent_sale">
            <option value="Rent">Rent</option>
            <option value="Sale">Sale</option>
            </select><br><br>

            address of the property : 
            <input type="text" name="addres">*<br><br>
            city : 
            <input type="text" name="city">*<br><br>
            zip code : 
            <input type="text" name="zip_code">*<br><br>
            area of the property : 
            <input type="text" name="area_property"><br><br>
            bedrooms number (if it is not a Garden) : 
            <input type="number" name="bedrooms"><br><br>
            price : 
            <input type="number" name="price"><br><br>
            ID of the landlord : 
            <input type="number" name="landlord_id">*<br><br>
            descriptions : 
            <input type="text" name="descriptions"><br><br><br>
            
            
            <button type="submit">Submit</button><br>    
            </h2>
        </form>
        <a href="index.php">Back</a>

    </body>
</html>

<?php

    if ($_SERVER['REQUEST_METHOD'] === 'POST') 
    {
        $property_type = sanitizeText($_POST['property_type'] ?? '');
        $rent_sale = sanitizeText($_POST['rent_sale'] ?? '');
        $addres = sanitizeText($_POST['addres'] ?? '');
        $city = sanitizeText($_POST['city'] ?? '');
        $area_property = sanitizeText($_POST['area_property'] ?? '');
        $bedrooms = $_POST['bedrooms'] ?? 0;
        $price = $_POST['price'] ?? 0;
        $landlord_id = $_POST['landlord_id'] ?? 0;
        $descriptions = sanitizeText($_POST['descriptions'] ?? '');
        $zip_code = sanitizeText($_POST['zip_code'] ?? '');

        // Validate required fields
        if (empty($addres) || empty($city) || empty($zip_code) || empty($landlord_id)) {
            echo "Please Enter ALL necessary information! <br>";
        } else if (!validateNumber($bedrooms) || !validateNumber($price) || !validateNumber($landlord_id)) {
            echo "Invalid numeric input! <br>";
        } else {
            $sql = "INSERT INTO Property (property_type, addres, city, area_property, bedrooms, descriptions, rent_sale, price, landlord_id, zip_code) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $result = executeQuery($conn, $sql, "ssssissssi", 
                [$property_type, $addres, $city, $area_property, intval($bedrooms), 
                 $descriptions, $rent_sale, floatval($price), intval($landlord_id), $zip_code]);
            
            if ($result['success']) {
                echo "Successful";
            } else {
                echo "Try again! " . htmlspecialchars($result['error']); 
            }
        }
        
    }
?>

<?php
    include("../../src/views/layouts/Footer.html");
    mysqli_close($conn);
?>