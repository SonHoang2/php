<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Tìm kiếm sản phẩm</title>
</head>

<body>
    <h1>Tìm kiếm sản phẩm</h1>
    <form action="search.php" method="get">
        <label for="keyword">Tìm kiếm sản phẩm theo tên sản phẩm:</label>
        <input type="text" id="keyword" name="keyword" required>
        <input type="submit" value="Tìm kiếm">
    </form>

    <?php
    session_start();

    echo $_SESSION['email'];


    // if (!$_SESSION["username"]) {
    //     echo "Bạn cần đăng nhập để sử dụng chức năng này";
    //     header("Location: login.php");
    // } else {
    //     echo $_SESSION["username"];
    // }

    ?>
</body>

</html>