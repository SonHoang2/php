<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm mới</title>
</head>

<body>
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

    // check if user is stocker
    if ($_SESSION['role'] != 'stocker' || !$_SESSION['role']) {
        echo "User is not stocker";
        header("Location: index.php");
        exit;
    }


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $productName = $_POST['product_name'];
        $productPrice = $_POST['product_price'];
        $productDescription = $_POST['product_description'];
        $categoryId = $_POST['category_id'];

        // check if any field is empty
        if (empty($productName) || empty($productPrice) || empty($productDescription) || empty($categoryId)) {
            echo "Please fill in all fields";
        } else {
            $stmt = $conn->prepare("INSERT INTO products (name, price, description, category_id) VALUES (?, ?, ?, ?)");
            // bind_param("sdsi") means 4 parameters: string, double, string, integer
            $stmt->bind_param("sdsi", $productName, $productPrice, $productDescription, $categoryId);
            $stmt->execute();

            echo "<script>alert('Successful!'); window.location.href = 'list.php';</script>";
        }
    }


    // not need prepare statement because no user input
    $categoriesResult = $conn->query("SELECT id, category_name FROM categories");
    ?>

    <h1>Thêm sản phẩm mới</h1>
    <form method="POST" action="">
        <label for="product_name">Tên sản phẩm:</label>
        <br>
        <input type="text" name="product_name" id="product_name">
        <br>

        <label for="product_price">Giá sản phẩm:</label><br>
        <input type="text" name="product_price" id="product_price">
        <br>

        <label for="product_description">Mô tả sản phẩm:</label><br>
        <textarea name="product_description" id="product_description"></textarea>
        <br>

        <label for="category_id">Danh mục sản phẩm:</label><br>
        <select name="category_id" id="category_id">
            <option value="">Chọn danh mục</option>
            <?php
            if ($categoriesResult->num_rows > 0) {
                while ($row = $categoriesResult->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['category_name'] . "</option>";
                }
            } else {
                echo "<option value=''>không có danh mục nào</option>";
            }
            ?>
        </select>
        <br>

        <input type="submit" value="Thêm sản phẩm">
    </form>


</body>

</html>