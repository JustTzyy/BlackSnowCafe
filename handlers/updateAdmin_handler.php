<?php
session_start();
include("../Connection/database.php");

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    // Retrieve and sanitize POST data
    $EID        = trim($_POST['EID'] ?? '');
    $firstName  = trim($_POST['first-name'] ?? '');
    $lastName   = trim($_POST['last-name'] ?? '');
    $middleName = trim($_POST['middle-name'] ?? '');
    $phone      = trim($_POST['phone-number'] ?? '');
    $email      = trim($_POST['email'] ?? '');
    $age        = trim($_POST['age'] ?? '');
    $gender     = trim($_POST['gender'] ?? '');
    $street     = trim($_POST['street'] ?? '');
    $city       = trim($_POST['city'] ?? '');
    $province   = trim($_POST['province'] ?? '');
    $zipcode    = trim($_POST['zipcode'] ?? '');

    // Debugging: Check if POST data is received
    if (empty($EID)) {
        die("Error: Missing EID. Form not submitting properly.");
    }

    // Update employees table
    $sql = "UPDATE employees 
            SET fn = ?, ln = ?, mid = ?, phone_num = ?, email = ?, age = ?, gender = ?
            WHERE EID = ?";
    
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssssiss", $firstName, $lastName, $middleName, $phone, $email, $age, $gender, $EID);

    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }
    $stmt->close();

    // Retrieve AID (Address ID) using EID
    $stmt = $conn->prepare("SELECT AID FROM address WHERE EID = ? LIMIT 1");
    $stmt->bind_param("s", $EID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $AID = $row['AID'] ?? null;
    $stmt->close();

    if (!$AID) {
        header("Location: ../Pages/adminprofile.php?update=fail&error=no_address_found");
        exit();
    }

    // Update Address Information
    $stmt = $conn->prepare("
        UPDATE address 
        SET street = ?, city = ?, province = ?, zipcode = ? 
        WHERE AID = ?
    ");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssss", $street, $city, $province, $zipcode, $AID);

    if ($stmt->execute()) {
        header("Location: ../Pages/adminprofile.php?update=success");
    } else {
        header("Location: ../Pages/adminprofile.php?update=fail");
    }

    $stmt->close();
    $conn->close();
}
?>
