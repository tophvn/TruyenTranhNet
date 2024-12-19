<?php
include('../config/database.php');
$type = 'sap-ra-mat'; // Đặt loại truyện
$page = $_GET['page'] ?? 1; // Lấy số trang từ URL, mặc định là trang 1

// Đặt URL của API từ Otruyen API với tham số loại truyện và số trang
$api_url = "https://otruyenapi.com/v1/api/danh-sach/$type?page=$page";

// Sử dụng cURL để gọi API
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$response = curl_exec($ch);
curl_close($ch);

// Chuyển đổi dữ liệu JSON từ API thành mảng PHP
$data = json_decode($response, true);

// Kiểm tra dữ liệu trả về có hợp lệ không
if (isset($data['data']) && !empty($data['data'])) {
    $truyenList = $data['data']['items']; 
} else {
    $truyenList = [];
}

// Hàm định dạng thời gian
function formatDate($dateString) {
    $date = new DateTime($dateString);
    return $date->format('d/m/Y H:i');
}
// Lấy thông tin lượt xem từ cơ sở dữ liệu
function getViews($slug) {
    global $conn;
    $query = "SELECT views FROM truyen WHERE slug = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $slug);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc()['views'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../img/logo.png" rel="icon">
    <title>Danh Sách Truyện - Sắp Ra Mắt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .active-page {
            font-weight: bold;
            text-decoration: underline;
        }
        .manga-cover {
            transition: transform 0.3s ease;
        }
        .manga-cover:hover {
            transform: scale(1.1);
        }
    </style>
</head>
<body>

    <?php include '../includes/header.php'; ?>
    <div class="container mt-4">
        <h4 class="section-title text-center mb-4">Danh Sách Truyện - Truyện Sắp Ra Mắt</h4>
        <div class="row g-4">
            <?php if (!empty($truyenList)): ?>
                <?php foreach ($truyenList as $truyen): ?>
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="card">
                            <!-- Link và hình ảnh -->
                            <a href="truyen-detail.php?slug=<?php echo urlencode($truyen['slug']); ?>" class="text-decoration-none">
                                <img src="https://img.otruyenapi.com/uploads/comics/<?php echo htmlspecialchars($truyen['thumb_url']); ?>" 
                                    class="card-img-top rounded manga-cover" 
                                    alt="<?php echo htmlspecialchars($truyen['name']); ?>" 
                                    loading="lazy">
                            </a>
                            <!-- Phần nội dung -->
                            <div class="card-body px-0 pt-2 pb-0">
                                <h5 class="manga-title text-truncate text-dark fw-bold mb-2">
                                    <?php echo htmlspecialchars($truyen['name']); ?>
                                </h5>
                                <div class="d-flex align-items-center small text-secondary">
                                    <span class="me-2">
                                        👁️ <?php echo getViews($truyen['slug']); ?>
                                    </span>
                                    <span>
                                        🔖 <?php echo htmlspecialchars($truyen['chaptersLatest'][0]['chapter_name'] ?? 'N/A'); ?>
                                    </span>
                                </div>
                                <div class="text-secondary small mt-1">
                                    ⏰ <?php echo htmlspecialchars(formatDate($truyen['updatedAt']) ?? 'N/A'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <p class="text-center">Không có dữ liệu truyện để hiển thị.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Thanh chuyển trang -->
        <div class="pagination justify-content-center mt-4">
            <ul class="pagination">
                <li class="page-item <?php echo ($page == 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo ($page - 1); ?>">Trước</a>
                </li>
                <?php for ($i = $page; $i < $page + 5; $i++): ?>
                    <li class="page-item">
                        <a class="page-link <?php echo ($i == $page) ? 'active-page' : ''; ?>" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?php echo ($page + 1); ?>">Tiếp</a>
                </li>
            </ul>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>