<!DOCTYPE html>
<html>

<head>
    <title>Change Password</title>
</head>

<body>
    <h1>Change Password</h1>
    <form action="changePassword.php" method="post">
        <label for="password">Current Password:</label>
        <input type="password" id="password" name="password" required>
        <label for="newPassword">New Password:</label>
        <input type="password" id="newPassword" name="newPassword" required>
        <label for="passwordConfirm">Password Confirm</label>
        <input type="password" id="passwordConfirm" name="passwordConfirm" required>
        <input type="submit" value="submit" name="submit">
    </form>

    <?php
    session_start();

    $DB_HOST = "localhost";
    $DB_USERNAME = "limitedAccount";
    $DB_PASSWORD = "123456";
    $DB_NAME = "testphp";

    // Create connection
    $conn = new mysqli($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // check if user login
    if (!isset($_SESSION['email'])) {
        echo "User is not logged in";
        header("Location: Login.php");
        exit;
    }

    // check if form is submitted
    if (isset($_POST['submit'])) {
        $password = $_POST['password'];
        $newPassword = $_POST['newPassword'];
        $passwordConfirm = $_POST['passwordConfirm'];

        // check if any field is empty
        if (empty($password) || empty($newPassword) || empty($passwordConfirm)) {
            echo "Please fill in all fields";
            exit;
        }

        // check password match
        if ($newPassword != $passwordConfirm) {
            echo "Passwords do not match";
            exit;
        }

        // function check special characters
        function specialChars($str)
        {
            return preg_match('/[^a-zA-Z0-9]/', $str) > 0;
        }

        // check password length > 8 and contain > 2 groups of characters 
        // (uppercase letters, lowercase letters, numbers and special characters)

        if (
            strlen($newPassword) < 8 ||
            !preg_match('/[A-Z]/', $newPassword) ||
            !preg_match('/[a-z]/', $newPassword) ||
            !preg_match('/[0-9]/', $newPassword) ||
            !specialChars($newPassword)
        ) {
            echo "Password must be at least 8 characters long and contain at least 2 groups of characters 
            (uppercase letters, lowercase letters, numbers and special characters)";
            exit;
        }

        // check if password is the same as the current password
        $stmt = $conn->prepare("SELECT password, salt FROM users WHERE email = ?");
        $stmt->bind_param("s", $_SESSION['email']);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // hash current password with salt in database
            $hashedCurrentPassword = sha1($password . $user['salt']);

            if ($hashedCurrentPassword !== $user['password']) {
                echo "Current password is incorrect";
                exit;
            }
        }

        // create salt and hash new password
        $salt = rand(100, 900);
        $newHashedPassword = sha1($newPassword . $salt);

        // change password
        $stmt = $conn->prepare("UPDATE users SET password = ?, salt = ? WHERE email = ?");
        $stmt->bind_param("sss", $newHashedPassword, $salt, $_SESSION['email']);

        if ($stmt->execute() === TRUE) {
            echo "Password changed successfully";
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Form not submitted";
    }
    ?>

</html>