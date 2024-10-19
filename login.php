<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
</head>

<body>
    <h1>login page</h1>
    <form action="login.php" method="post">
        <label for="email">Email:</label>
        <br>
        <input type="text" id="email" name="email">
        <br>
        <label for="password">Password:</label>
        <br>
        <input type="password" id="password" name="password">
        <br>
        <label for="passwordConfirm">Password:</label>
        <br>
        <input type="password" id="passwordConfirm" name="passwordConfirm">
        <br>
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

    // check if form is submitted
    if (isset($_POST['submit'])) {
        // fake login
        $_SESSION['email']="2@gmail.com";

        // $_SESSION['role']="admin";
        $_SESSION['role']="stocker";

    } else {
        echo "Form not submitted";
    }

    ?>
</body>

</html>