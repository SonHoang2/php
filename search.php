<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Tìm kiếm sản phẩm</title>
</head>

<body>
    <?php
    session_start();

    // Kết nối database
    $DB_HOST = "localhost";
    $DB_USERNAME = "limitedAccount";
    $DB_PASSWORD = "123456";
    $DB_NAME = "testphp";

    // tạo kết nối
    $conn = new mysqli($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);

    // kiểm tra kết nối
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // kiểm tra người dùng đã đăng nhập chưa
    if (!$_SESSION["email"]) {
        echo "Bạn cần đăng nhập để sử dụng chức năng này";
        header("Location: login.php");
        exit;
    }

    // Chỉ cho admin truy cập chức năng này
    if ($_SESSION["role"] != "admin" || !isset($_SESSION["role"])) {
        echo "Bạn không có quyền truy cập chức năng này";
        header("Location: index.php");
        exit;
    }

    // check if submit
    if (isset($_GET['keyword'])) {
        $keyword = $_GET['keyword'];

        // kiểm tra xem keyword có dữ liệu hay không
        if (empty($keyword)) {
            echo "Vui lòng nhập từ khóa tìm kiếm";
        } else {
            // tìm kiếm sản phẩm theo tên
            $stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ?");
            $stmt->bind_param("s", $keyword);
            $stmt->execute();
            $result = $stmt->get_result();
        }
    } else {
        echo "form chưa được gửi đi";
    }
    ?>

    <h1>Tìm kiếm sản phẩm</h1>
    <form action="search.php" method="get">
        <label for="keyword">Tìm kiếm sản phẩm theo tên sản phẩm:</label>
        <input type="text" id="keyword" name="keyword" required>
        <input type="submit" value="Tìm kiếm">
        <!-- chỉ hiện ra nếu thỏa mãn điều kiện -->
        <!-- kiểm tra xem result có dữ liệu hay không -->
        <?php if (isset($result)): ?>
            <?php if ($result->num_rows > 0): ?>
                <h3>Danh sách tìm kiếm</h3>
                <table border="1">
                    <thead>
                        <td>ID</td>
                        <td>Tên sản phẩm</td>
                        <td>Giá</td>
                        <td>Mô Tả</td>
                        <td>Loại sản phẩm</td>
                        <td></td>
                        <td></td>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['price']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td><?php echo htmlspecialchars($row['category_id']); ?></td>
                                <td><a href="update.php?id=<?php echo htmlspecialchars($row['id']); ?>">Sửa</a></td>
                                <td><a href="delete.php?id=<?php echo htmlspecialchars($row['id']); ?>">Xóa</a></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <!-- nếu mà không có kết quả thì chạy cái này -->
            <?php else: ?>
                <h1>không có kết quả</h1>
            <?php endif; ?>
        <?php endif; ?>
    </form>
</body>

</html>