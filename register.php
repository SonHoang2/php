<!DOCTYPE html>
<html>

<head>
    <title>register</title>
</head>

<body>
    <h1>register</h1>
    <form action="register.php" method="post">
        <label for="username">Username:</label>
        <br>
        <input type="text" id="username" name="username" value="sonhoang">
        <br>
        <label for="email">Email:</label>
        <br>
        <input type="text" id="email" name="email" value="2@gmail.com">
        <br>
        <label for="phone">Phone:</label>
        <br>
        <input type="text" id="phone" name="phone" value="1231231234">
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
        $username = $_POST['username'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $passwordConfirm = $_POST['passwordConfirm'];

        // check if field is empty
        if (
            empty($username) || empty($email) ||
            empty($phone) || empty($password) ||
            empty($passwordConfirm)
        ) {
            echo "Please fill all fields";
            exit;
        }

        // check if password match
        if ($password != $passwordConfirm) {
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
            strlen($password) < 8 ||
            !preg_match('/[A-Z]/', $password) ||
            !preg_match('/[a-z]/', $password) ||
            !preg_match('/[0-9]/', $password) ||
            !specialChars($password)
        ) {
            echo "Password must be at least 8 characters long and contain at least 2 groups of characters (uppercase letters, lowercase letters, numbers and special characters)";
            exit;
        }

        // check email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email format";
            exit;
        }

        // check phone number format
        if (!preg_match('/^[0-9]{10}+$/', $phone)) {
            echo "Invalid phone number format";
            exit;
        }

        // check if email already exist
        $stmt = $conn->prepare("SELECT * FROM Users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Email already exist";
            exit;
        }

        // check if phone already exist
        $stmt = $conn->prepare("SELECT * FROM Users WHERE phone=?");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "phone already exist";
            exit;
        }

        // hash password
        // generate random salt
        $salt = rand(100, 900);

        // $password . $salt for combines the two variables into a single string
        $password = sha1($password . $salt);

        // insert data to database
        $stmt = $conn->prepare("INSERT INTO Users (username, email, phone, password, salt) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $email, $phone, $password, $salt);

        if ($stmt->execute() === TRUE) {
            echo "successful account registration!";
            // navigate to login page
            header('Location: login.php');
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Form not submitted";
    }
    ?>
</body>

</html>