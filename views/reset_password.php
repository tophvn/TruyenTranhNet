<?php
include('../config/database.php');
// Kiểm tra nếu có mã xác nhận từ URL (token)
if (!isset($_GET['token'])) {
    echo "Mã xác nhận không hợp lệ.";
    exit();
}

$token = $_GET['token'];
$query = "SELECT * FROM users WHERE reset_token = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result === false || $result->num_rows == 0) {
    echo "Mã xác nhận không hợp lệ hoặc đã hết hạn.";
    exit();
}

$row = $result->fetch_assoc();
$email = $row['email'];

$error = ""; 
$success_message = ""; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    // Kiểm tra điều kiện mật khẩu
    if (strlen($new_password) < 6) {
        $error = "Mật khẩu phải lớn hơn 6 ký tự.";
    } elseif (preg_match('/[^\w]/', $new_password)) {
        $error = "Mật khẩu không được chứa ký tự đặc biệt.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Mật khẩu xác nhận không khớp.";
    } else {
        $hashed_password = md5($new_password);
        $update_query = "UPDATE users SET password = ?, reset_token = NULL WHERE email = ?";
        $update_stmt = $conn->prepare($update_query); 
        $update_stmt->bind_param("ss", $hashed_password, $email);
        
        if ($update_stmt->execute()) {
            $success_message = "Mật khẩu của bạn đã được thay đổi thành công.";
        } else {
            $error = "Có lỗi xảy ra khi cập nhật mật khẩu.";
        }
    }
}

// Đóng kết nối khi không còn sử dụng
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="../img/logo.png" rel="icon">
    <title>Đặt lại Mật Khẩu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link rel="stylesheet" href="../css/css-login-register.css">
</head>
<body>
    <div class="site-wrap d-md-flex align-items-stretch">
        <div class="bg-img" style="background-image: url('../img/reset-1.jpg')"></div>
        <div class="form-wrap">
            <div class="form-inner">
                <h1 class="title">Đặt Lại Mật Khẩu</h1>
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger">
                        <p><?php echo $error; ?></p>
                    </div>
                <?php endif; ?>
                <?php if (!empty($success_message)): ?>
                    <div class="alert alert-success">
                        <p><?php echo $success_message; ?></p>
                    </div>
                    <div class="mb-4 text-center">
                        <a href="login.php">
                            <button type="button" class="btn btn-primary">Đăng Nhập</button>
                        </a>
                    </div>
                <?php else: ?>
                    <form action="" method="POST" class="pt-3">
                        <div class="form-floating">
                            <input type="password" class="form-control" name="new_password" id="new_password" placeholder="Mật khẩu mới" required>
                            <label for="new_password">Mật khẩu mới</label>
                        </div>
                        <div class="form-floating">
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Xác nhận mật khẩu" required>
                            <label for="confirm_password">Xác nhận mật khẩu</label>
                        </div>
                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary">Cập nhật mật khẩu</button>
                        </div>
                        <div class="mb-2">Quay lại <a href="login.php">Đăng Nhập</a></div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <a href="../index.php" class="btn" style="position: fixed; bottom: 20px; right: 20px; display: inline-flex; align-items: center; background-color: white; border: none; border-radius: 50%; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); width: 50px; height: 50px; justify-content: center; z-index: 1000;">
        <i class="uil uil-estate" style="font-size: 1.5rem; color: #007bff;"></i>
    </a>
</body>
</html>