<?php
include('../config/database.php');
$slug = $_GET['slug'] ?? '';
$page = $_GET['page'] ?? 1;

// Kiểm tra nếu slug không rỗng
if ($slug) {
    $api_url = "https://otruyenapi.com/v1/api/the-loai/{$slug}?page={$page}";

    // Sử dụng cURL để gọi API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $response = curl_exec($ch);
    curl_close($ch);

    // Chuyển dữ liệu JSON thành mảng PHP
    $data = json_decode($response, true);

    // Kiểm tra dữ liệu trả về
    if (isset($data['data']) && !empty($data['data']['items'])) {
        $truyenList = $data['data']['items'];
        $totalPages = isset($data['data']['total_pages']) ? $data['data']['total_pages'] : 0;
    } else {
        $truyenList = [];
        $totalPages = 0;
    }
} else {
    $truyenList = [];
    $totalPages = 0;
}

// Hàm định dạng thời gian
function formatDate($dateString) {
    if (empty($dateString) || $dateString === null) {
        return 'N/A';
    }
    try {
        $date = new DateTime($dateString);
        return $date->format('d/m/Y H:i');
    } catch (Exception $e) {
        return 'N/A';
    }
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
    <title>Danh Sách Truyện Thể Loại <?= htmlspecialchars($slug) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/main.css">
</head>
<body>
    <?php include '../includes/header.php' ?>
    <div class="container mt-4">
        <div class="section-wrapper">
            <h4 class="section-title"><i class="fas fa-list"></i> Danh Sách Truyện Thể Loại: <?= htmlspecialchars($slug) ?></h4>
            <div class="row g-3">
                <?php if (!empty($truyenList)): ?>
                    <?php foreach ($truyenList as $truyen): ?>
                        <div class="col-6 col-md-4 col-lg-2 mb-4">
                            <div class="manga-card">
                                <a href="truyen-detail.php?slug=<?= urlencode($truyen['slug']) ?>" class="text-decoration-none position-relative">
                                    <img src="https://img.otruyenapi.com/uploads/comics/<?= htmlspecialchars($truyen['thumb_url']) ?>" 
                                         class="card-img-top manga-cover" 
                                         alt="<?= htmlspecialchars($truyen['name']) ?>" 
                                         loading="lazy">
                                    <?php 
                                    // Kiểm tra thể loại để hiển thị tag "18+"
                                    if (isset($truyen['category']) && is_array($truyen['category'])) {
                                        foreach ($truyen['category'] as $cat) {
                                            if (in_array($cat['name'], ['Adult', '16+', 'Ecchi', 'Smut'])) {
                                                echo '<span class="badge-18plus">18+</span>';
                                                break;
                                            }
                                        }
                                    }
                                    ?>
                                </a>
                                <div class="card-body p-2">
                                    <h5 class="manga-title" title="<?= htmlspecialchars($truyen['name']) ?>"><?= htmlspecialchars($truyen['name']) ?></h5>
                                    <div class="text-muted small d-flex justify-content-between align-items-center mt-1">
                                        <span><i class="fas fa-bookmark"></i> <?= htmlspecialchars($truyen['chaptersLatest'][0]['chapter_name'] ?? 'N/A') ?></span>
                                        <span><i class="fas fa-clock"></i> <?= formatDate($truyen['updatedAt'] ?? null) ?></span>
                                    </div>
                                    <div class="text-muted small mt-1">
                                        <i class="fas fa-eye"></i> <?= getViews($truyen['slug']) ?> lượt xem
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center">Không có truyện thuộc thể loại này.</p>
                <?php endif; ?>
            </div>

            <!-- Thanh chuyển trang -->
            <div class="pagination justify-content-center mt-4">
                <ul class="pagination">
                    <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?slug=<?= htmlspecialchars($slug) ?>&page=<?= ($page - 1) ?>">Trước</a>
                    </li>
                    <?php for ($i = max(1, $page - 2); $i <= min($page + 2, $totalPages); $i++): ?>
                        <li class="page-item">
                            <a class="page-link <?= ($i == $page) ? 'active-page' : '' ?>" href="?slug=<?= htmlspecialchars($slug) ?>&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= ($page >= $totalPages) ? 'disabled' : '' ?>">
                        <a class="page-link" href="?slug=<?= htmlspecialchars($slug) ?>&page=<?= ($page + 1) ?>">Tiếp</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>