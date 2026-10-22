<?php
session_start();
require_once '../Connection/database.php'; // Ensure your database connection file is included

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $UID = $_POST['UID'];
    $username = trim($_POST['username']);
    $confirmUsername = trim($_POST['confirmusername']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmpassword']);

    // Check for empty fields
    if (empty($username) || empty($confirmUsername) || empty($password) || empty($confirmPassword)) {
        $_SESSION['error'] = "All fields are required.";
        header("Location: ../Pages/adminprofile.php");
        exit();
    }

    // Check if username matches confirmation
    if ($username !== $confirmUsername) {
        $_SESSION['error'] = "Usernames do not match.";
        header("Location: ../Pages/adminprofile.php");
        exit();
    }

    // Check if password matches confirmation
    if ($password !== $confirmPassword) {
        $_SESSION['error'] = "Passwords do not match.";
        header("Location: ../Pages/adminprofile.php");
        exit();
    }

    // Hash the new password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if username already exists (excluding current user)
    $checkStmt = $conn->prepare("SELECT UID FROM users WHERE username = ? AND UID != ?");
    $checkStmt->bind_param("ss", $username, $UID);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $_SESSION['error'] = "Username already taken. Please choose another one.";
        header("Location: ../Pages/adminprofile.php");
        exit();
    }
    $checkStmt->close();

    // Update user details in the database
    $updateStmt = $conn->prepare("UPDATE users SET username = ?, password = ? WHERE UID = ?");
    $updateStmt->bind_param("sss", $username, $hashedPassword, $UID);

    if ($updateStmt->execute()) {
        $_SESSION['success'] = "Account updated successfully.";
    } else {
        $_SESSION['error'] = "Failed to update account. Please try again.";
    }
    $updateStmt->close();
    $conn->close();

    header("Location: ../Pages/adminprofile.php");
    exit();
} else {
    $_SESSION['error'] = "Invalid request.";
    header("Location: ../Pages/adminprofile.php");
    exit();
}
?>
