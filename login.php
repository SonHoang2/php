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
        <input type="text" id="email" name="email" value="2@gmail.com">
        <br>
        <label for="password">Password:</label>
        <br>
        <input type="password" id="password" name="password" value="Test1234@">
        <br>
        <label for="passwordConfirm">Password:</label>
        <br>
        <input type="password" id="passwordConfirm" name="passwordConfirm" value="Test1234@">
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
        $email = $_POST['email'];
        $password = $_POST['password'];
        $passwordConfirm = $_POST['passwordConfirm'];

        echo $email;

        $_SESSION['email']=$email;

    } else {
        echo "Form not submitted";
    }

    ?>
</body>

</html>