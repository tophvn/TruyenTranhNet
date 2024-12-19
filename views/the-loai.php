<?php
// Gọi API để lấy danh sách thể loại truyện
$api_url = "https://otruyenapi.com/v1/api/the-loai";

// Sử dụng cURL để gọi API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$response = curl_exec($ch);
curl_close($ch);

// Chuyển đổi dữ liệu JSON thành mảng PHP
$data = json_decode($response, true);

// Kiểm tra dữ liệu trả về có hợp lệ không
if (isset($data['data']) && !empty($data['data'])) {
    $categories = $data['data']['items']; // Lấy danh sách thể loại truyện
} else {
    $categories = []; // Nếu không có dữ liệu, trả về mảng trống
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../img/logo.png" rel="icon">
    <title>Danh Sách Thể Loại Truyện</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>

    <?php include '../includes/header.php' ?>
    <div class="container mt-4">
        <h4 class="section-title">Thể Loại Truyện</h4>
        <div class="row">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <div class="col-md-3 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($category['name']); ?></h5>
                                <a href="truyen-theo-the-loai.php?slug=<?php echo $category['slug']; ?>" class="btn btn-primary">Xem Truyện</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-center">Không có dữ liệu thể loại để hiển thị.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php include '../includes/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
