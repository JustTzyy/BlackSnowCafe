<?php
include "../authentication/authenticated.php";
include("../Connection/database.php"); // Include the database connection

$user_name = $_SESSION['username'];

// Query to select the logged-in user’s record
$sql = "SELECT * FROM employee_details WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_name);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // If needed, split the fullname for display purposes
    $nameParts = explode(' ', $row['fullname']);
    $firstName = isset($nameParts[0]) ? $nameParts[0] : '';
    $middleName = isset($nameParts[1]) ? $nameParts[1] : '';
    $lastName = isset($nameParts[2]) ? $nameParts[2] : '';
} else {
    echo "No data found.";
    exit();
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css"
        integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js"
        integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous">
        </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
        </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../designs/styles.css">
    <title>Document</title>
    <style>
        /* Hide all sections by default */
        .content-section {
            display: none;
        }

        /* Show the active section */
        .content-section.active {
            display: block;
        }

        /* Disabled input styling */
        input:read-only {
            background-color: #f9f9f9;
            cursor: not-allowed;
        }
    </style>
</head>

<body>
    <div class="container-fluid mx-0">
        <div class="profile-layout">
            <div class="side-bar">
                <div class="user-profile">
                    <div class="avatar">
                        <?php if (!empty($row['profile'])): ?>
                            <img style="height: 80px; width: 80px; border-radius: 50%;"
                                src="data:image/jpeg;base64,<?php echo base64_encode($row['profile']); ?>"
                                alt="User Profile Picture">
                        <?php else: ?>
                            <i class="bi bi-person-fill" style="font-size: 80px;"></i>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="info text-center">
                    <h3><?php echo $row['fullname']; ?></h3>
                    <small class="text-muted"><?php echo $row['email']; ?></small>
                </div>
                <div class="edit d-flex justify-content-center">
                    <button id="edit-profile-btn" class="btn btn-outline-secondary w-75 mt-3">Edit Profile</button>
                </div>
                <hr>
                <div class="side-nav">
                    <small class="text-muted px-3">Navigations</small>
                    <ul>
                        <li><button class="nav-item active btn" data-section="personal-information"><span
                                    class="icon">👤</span>&nbsp;Personal Information</button></li>
                        <li><button class="nav-item btn" data-section="security-settings"><span
                                    class="icon">🔒</span>&nbsp;Security Settings</button></li>
                    </ul>
                </div>
                <hr>
                <div class="side-footer px-4">

                    <a href="../handlers/logout_handler.php" class="btn btn-outline-danger form-control">Log out
                        &nbsp;<i class="bi bi-box-arrow-in-left"></i></a>

                    <a href="AdminDashboard.php"><button class="btn btn-outline-secondary form-control mt-3">Back
                            &nbsp;<i class="bi bi-back"></i></button></a>
                </div>
            </div>
            <div class="main-content">
                <div class="contents">

                    <!-- Personal Information Section -->
                    <div class="content-section personal-information p-md-2 mt-4 active">
                        <div class="personal-info">
                            <h3>Personal Information</h3>
                            <div class="form-group">
                                <form action="../handlers/updateAdmin_handler.php" method="POST">


                                    <div class="row">


                                        <div class="col-md-4 my-md-4">
                                            <input type="hidden" name="EID" value="<?php echo $row['EID']; ?>">
                                            <label for="first-name">First Name</label>
                                            <input type="text" class="form-control" value="<?php echo $firstName; ?>"
                                                id="first-name" name="first-name" placeholder="First Name" readonly>
                                        </div>
                                        <div class="col-md-4 my-md-4">
                                            <label for="last-name">Last Name</label>
                                            <input type="text" class="form-control" value="<?php echo $lastName; ?>"
                                                id="last-name" name="last-name" placeholder="Last Name" readonly>
                                        </div>
                                        <div class="col-md-4 my-md-4">
                                            <label for="middle-name">Middle Name</label>
                                            <input type="text" class="form-control" value="<?php echo $middleName; ?>"
                                                id="middle-name" name="middle-name" placeholder="Middle Name" readonly>
                                        </div>
                                        <div class="col-md-6 my-md-4">
                                            <label for="phone-number">Phone Number</label>
                                            <input type="text" class="form-control" id="phone-number"
                                                value="<?php echo $row['phone_num']; ?>" name="phone-number"
                                                placeholder="Phone Number" readonly>
                                        </div>
                                        <div class="col-md-6 my-md-4">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                placeholder="Email" value="<?php echo $row['email']; ?>" readonly>
                                        </div>
                                        <div class="col-md-6 my-md-4">
                                            <label for="age">Age</label>
                                            <input type="text" class="form-control" value="<?php echo $row['age']; ?>"
                                                id="age" name="age" placeholder="Age" readonly>
                                        </div>
                                        <div class="col-md-6 my-md-4">
                                            <label for="gender">Gender</label>
                                            <input type="text" class="form-control" id="gender" name="gender"
                                                value="<?php echo $row['gender']; ?>" placeholder="Gender" readonly>
                                        </div>
                                        <div class="col my-md-3 my-md-4">
                                            <label for="address">Street</label>
                                            <input type="text" class="form-control" id="address" name="street"
                                                placeholder="Street" value="<?php echo $row['street']; ?>" readonly>
                                        </div>
                                        <div class="col-md-3 my-md-4">
                                            <label for="city">City</label>
                                            <input type="text" class="form-control" id="city" name="city"
                                                placeholder="City" value="<?php echo $row['city']; ?>" readonly>
                                        </div>
                                        <div class="col-md-3 my-md-4">
                                            <label for="province">Province</label>
                                            <input type="text" class="form-control" id="province" name="province"
                                                placeholder="Province" value="<?php echo $row['province']; ?>" readonly>
                                        </div>
                                        <div class="col-md-3 my-md-4">
                                            <label for="postal-code">Postal Code</label>
                                            <input type="text" class="form-control" id="postal-code" name="zipcode"
                                                value="<?php echo $row['zipcode']; ?>" placeholder="Postal Code"
                                                readonly>
                                        </div>
                                        <div class="col-md-12 my-md-4 d-flex justify-content-center">
                                            <button type="submit" style="width: 16rem;" class="btn btn-primary">Save
                                                Changes</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Security Settings Section -->
                    <div class="content-section security-settings p-md-2 mt-4">
                        <div class="security-info">
                            <h3>Security Settings</h3>
                            <div class="form-group">
                                <form action="../handlers/updateUserAdmin_hander.php" method="POST">
                                    <input type="hidden" name="UID" value="<?php echo $_SESSION['UID']; ?>">
                                    <div class="row justify-content-center align-items-center">
                                        <div class="col-md-4 my-md-4">
                                            <label for="username">Username</label>
                                            <input type="text" class="form-control" id="username" name="username"
                                                required>
                                        </div>
                                        <div class="col-md-4 my-md-4">
                                            <label for="confirmusername">Confirm Username</label>
                                            <input type="text" class="form-control" id="confirmusername"
                                                name="confirmusername" required>
                                        </div>
                                    </div>
                                    <div class="row d-flex justify-content-center align-items-center">
                                        <div class="col-md-4 my-md-4">
                                            <label for="password">New Password</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                required>
                                        </div>
                                        <div class="col-md-4 my-md-4">
                                            <label for="confirmpassword">Confirm Password</label>
                                            <input type="password" class="form-control" id="confirmpassword"
                                                name="confirmpassword" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12 my-md-4 d-flex justify-content-center">
                                        <button type="submit" style="width: 16rem;" class="btn btn-primary">Save
                                            Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                // Function to handle section changes
                function handleSectionChange(sectionId) {
                    // Hide all sections
                    document.querySelectorAll(".content-section").forEach(section => {
                        section.classList.remove("active");
                    });

                    // Show the selected section
                    document.querySelector(`.${sectionId}`).classList.add("active");

                    // Remove "active" from all buttons
                    document.querySelectorAll(".nav-item").forEach(btn => {
                        btn.classList.remove("active");
                    });

                    // Add "active" to the clicked button
                    document.querySelector(`[data-section="${sectionId}"]`).classList.add("active");
                }

                // Add event listeners to navigation buttons
                document.querySelectorAll(".nav-item").forEach(item => {
                    item.addEventListener("click", function () {
                        const sectionId = this.getAttribute("data-section");
                        handleSectionChange(sectionId);
                    });
                });

                // Show the default section (Personal Information) on page load
                handleSectionChange("personal-information");

                // Toggle edit mode
                const editProfileBtn = document.getElementById("edit-profile-btn");
                const inputs = document.querySelectorAll("input");

                editProfileBtn.addEventListener("click", function () {
                    inputs.forEach(input => {
                        input.readOnly = !input.readOnly;
                    });

                    // Change button text
                    if (editProfileBtn.textContent === "Edit Profile") {
                        editProfileBtn.textContent = "Cancel Edit";
                        editProfileBtn.classList.remove("btn-outline-secondary");
                        editProfileBtn.classList.add("btn-outline-danger");
                    } else {
                        editProfileBtn.textContent = "Edit Profile";
                        editProfileBtn.classList.remove("btn-outline-danger");
                        editProfileBtn.classList.add("btn-outline-secondary");
                    }
                });
            });


        </script>
</body>

</html>