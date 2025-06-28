<?php
// --- CẤU HÌNH KẾT NỐI RDS MySQL ---
$dbHost     = 'database-1.cqekk570fptu.us-east-1.rds.amazonaws.com';
$dbUsername = 'admin';
$dbPassword = 'minhchi123';
$dbName     = 'myDB';

// Thông báo hiển thị
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        $message = 'Vui lòng nhập đầy đủ tên và mật khẩu.';
    } else {
        // Kết nối
        $mysqli = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
        if ($mysqli->connect_errno) {
            $message = 'Lỗi kết nối: ' . $mysqli->connect_error;
        } else {
            // Prepared statement
            $stmt = $mysqli->prepare(
                "SELECT `password` FROM `users` WHERE `name` = ?"
            );
            $stmt->bind_param('s', $username);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 0) {
                $message = 'Tên đăng nhập không tồn tại.';
            } else {
                $stmt->bind_result($storedPwd);
                $stmt->fetch();
                if ($password === $storedPwd) {
                    $message = "Đăng nhập thành công! Chào <strong>"
                             . htmlspecialchars($username)
                             . "</strong>.";
                } else {
                    $message = 'Mật khẩu không đúng.';
                }
            }

            $stmt->close();
            $mysqli->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Đăng nhập RDS MySQL</title>
  <style>
    body { font-family: Arial; padding: 20px; }
    form { max-width: 320px; margin: auto; }
    input, button { width: 100%; margin: 8px 0; padding: 8px; }
    .msg { text-align:center; margin-bottom:16px; }
    .ok { color: green; } .err { color: red; }
  </style>
</head>
<body>

  <h2 style="text-align:center;">Đăng nhập</h2>

  <?php if ($message): ?>
    <div class="msg <?= strpos($message,'thành công')!==false?'ok':'err' ?>">
      <?= $message ?>
    </div>
  <?php endif; ?>

  <form method="post" action="">
    <input type="text"     name="username" placeholder="Tên đăng nhập" required>
    <input type="password" name="password" placeholder="Mật khẩu"      required>
    <button type="submit">Đăng nhập</button>
  </form>

</body>
</html>
