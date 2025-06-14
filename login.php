<?php
// Thiết lập thông tin kết nối đến database
$servername = "db-lab7.cqekk570fptu.us-east-1.rds.amazonaws.com";
$db_user   = "admin";
$db_pass   = "minhchi123";
$dbname    = "myDB";

// Tạo kết nối đến database
$conn = new mysqli($servername, $db_user, $db_pass, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối không thành công: " . $conn->connect_error);
}

// Kiểm tra nếu form đã submit
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Lấy giá trị từ form
    $user = $_POST["username"] ?? "";
    $pass = $_POST["password"] ?? "";

    // Prepared statement để tránh SQL Injection
    $stmt = $conn->prepare("SELECT * FROM `User` WHERE userName = ? AND password = ?");
    $stmt->bind_param("ss", $user, $pass);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // Đăng nhập thành công
        echo "<p style='color:green;'>Bạn đã đăng nhập thành công</p>";
        // Chuyển hướng hoặc hiển thị trang chào mừng ở đây
    } else {
        // Đăng nhập không thành công
        echo "<p style='color:red;'>Tên đăng nhập hoặc mật khẩu không đúng</p>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
</head>
<body>
    <h2>Đăng nhập</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Tên đăng nhập:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Mật khẩu:</label>
        <input type="password" id="password" name="password" required><br><br>

        <input type="submit" value="Đăng nhập">
    </form>
</body>
</html>
