<?php
include('../config/database.php');

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

// Hàm chuyển đổi trạng thái
function translateStatus($status) {
    switch ($status) {
        case 'ongoing':
            return 'Đang Phát Hành';
        case 'completed':
            return 'Hoàn Thành';
        case 'coming_soon':
            return 'Sắp Ra Mắt';
        default:
            return 'Không Xác Định';
    }
}
?>


<!--
=========================================
|| WEBSITE ĐƯỢC CODE BY DEV HAI        ||
|| SỬ DỤNG CHO MỤC ĐÍCH PHI LỢI NHUẬN  ||
=========================================
-->
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../img/logo.png" rel="icon">
    <title>Tìm Kiếm Truyện</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <style>
    .status1 {
            background-color: rgba(0, 0, 0, 0.6); /* Tạo nền mờ cho văn bản */
            color: white; /* Màu chữ trắng */
            padding: 5px 10px; /* Thêm khoảng cách xung quanh chữ */
            border-radius: 5px; /* Bo góc */
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="container mt-4">
        <h1 class="mb-4 text-center">Tìm Kiếm Truyện</h1>
        <form method="GET" action="tim-kiem.php" class="mb-4">
            <div class="input-group">
                <input type="text" name="keyword" class="form-control" placeholder="Nhập từ khóa tìm kiếm" required>
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">Tìm kiếm</button>
                </div>
            </div>
        </form>

        <?php
        if (isset($_GET['keyword'])) {
            $keyword = $_GET['keyword'];
            $apiUrl = "https://otruyenapi.com/v1/api/tim-kiem?keyword=" . urlencode($keyword);

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            if ($response === false) {
                echo "<p class='text-center'>Không thể kết nối tới Server.</p>";
                exit;
            }

            $result = json_decode($response, true);
            if (!$result || $result['status'] !== 'success' || !isset($result['data']['items'])) {
                echo "<p class='text-center'>Dữ liệu không hợp lệ hoặc không có kết quả tìm kiếm.</p>";
                exit;
            }

            $searchResults = $result['data']['items'];
        ?>
        <h2 class="mt-4 text-center">Kết Quả Tìm Kiếm Cho: "<?php echo htmlspecialchars($keyword); ?>"</h2>
        <br>
        <div class="row g-4">
            <?php if (!empty($searchResults)): ?>
                <?php foreach ($searchResults as $comic): ?>
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="card position-relative">
                            <a href="truyen-detail.php?slug=<?php echo urlencode($comic['slug']); ?>">
                                <img src="https://img.otruyenapi.com/uploads/comics/<?php echo htmlspecialchars($comic['thumb_url']); ?>" 
                                    class="card-img-top manga-cover" 
                                    alt="<?php echo htmlspecialchars($comic['name']); ?>" 
                                    loading="lazy">
                            </a>
                            <!-- Trạng thái truyện -->
                            <p class="card-text status1 position-absolute top-0 end-0 m-2">
                                <?php echo translateStatus(htmlspecialchars($comic['status'])); ?>
                            </p>

                            <!-- Thông tin truyện -->
                            <div class="card-body px-0 pt-2 pb-0">
                                <h5 class="manga-title text-truncate text-dark fw-bold mb-2">
                                    <?php echo htmlspecialchars($comic['name']); ?>
                                </h5>
                                <div class="d-flex align-items-center small text-secondary">
                                    <span class="me-2">
                                        👁️ <?php echo getViews($comic['slug']); ?>
                                    </span>
                                    <span>
                                        🔖 <?php echo htmlspecialchars($comic['chaptersLatest'][0]['chapter_name'] ?? 'N/A'); ?>
                                    </span>
                                </div>
                                <div class="text-secondary small mt-1">
                                    ⏰ <?php echo htmlspecialchars(formatDate($comic['updatedAt']) ?? 'N/A'); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <p class="text-center">Không có dữ liệu để hiển thị.</p>
                </div>
            <?php endif; ?>
        </div>
        
        <?php
        }
        ?>
    </div>
    <br>
    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>