<?php
include('../config/database.php');
$slug = $_GET['slug'] ?? ''; 
$page = $_GET['page'] ?? 1;

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

    $data = json_decode($response, true);

    // Kiểm tra dữ liệu trả về
    if (isset($data['data']) && !empty($data['data']['items'])) {
        $truyenList = $data['data']['items'];
        $totalPages = $data['data']['total_pages'] ?? 0;
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
    $date = new DateTime($dateString);
    return $date->format('d/m/Y H:i');
}

// Hàm chuyển đổi trạng thái
function translateStatus($status) {
    $statusMap = [
        'ongoing' => 'Update',
        'completed' => 'Hoàn Thành',
        'coming_soon' => 'Sắp Ra Mắt'
    ];
    return $statusMap[$status] ?? 'Không Xác Định';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../img/logo.png" rel="icon">
    <title>Danh Sách Truyện Thể Loại <?php echo htmlspecialchars($slug); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .status1 {
            background-color: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .badge-18 {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: rgba(255, 0, 0, 0.7);
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    <div class="container mt-4">
        <h4 class="section-title text-center mb-4">Truyện Thể Loại: <?php echo htmlspecialchars($slug); ?></h4>
        <div class="row g-4">
            <?php if (!empty($truyenList)): ?>
                <?php foreach ($truyenList as $truyen): ?>
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="card position-relative">
                            <a href="truyen-detail.php?slug=<?php echo urlencode($truyen['slug']); ?>">
                                <img src="https://img.otruyenapi.com/uploads/comics/<?php echo htmlspecialchars($truyen['thumb_url']); ?>" 
                                    class="card-img-top manga-cover" 
                                    alt="<?php echo htmlspecialchars($truyen['name']); ?>" 
                                    loading="lazy">
                            </a>
                            <p class="card-text status1 position-absolute top-0 end-0 m-2">
                                <?php echo translateStatus(htmlspecialchars($truyen['status'])); ?>
                            </p>
                            <?php 
                            if (isset($truyen['category']) && is_array($truyen['category'])) {
                                foreach ($truyen['category'] as $cat) {
                                    if (in_array($cat['name'], ['Adult', '16+', 'Ecchi', 'Smut', 'Đam Mỹ'])) {
                                        echo '<span class="badge-18 position-absolute">18+</span>';
                                        break;
                                    }
                                }
                            }
                            ?>
                            <div class="card-body px-0 pt-2 pb-0">
                                <h5 class="manga-title text-truncate text-dark fw-bold mb-2">
                                    <?php echo htmlspecialchars($truyen['name']); ?>
                                </h5>
                                <div class="d-flex align-items-center small text-secondary">
                                    <span class="me-2">👁️ <?php echo getViews($truyen['slug']); ?></span>
                                    <span>🔖 <?php echo htmlspecialchars($truyen['chaptersLatest'][0]['chapter_name'] ?? 'N/A'); ?></span>
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
                    <p class="text-center">Không có truyện thuộc thể loại này.</p>
                </div>
            <?php endif; ?>
        </div>
        <nav aria-label="Page navigation example" class="mt-4">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="danh-sach-truyen-theo-the-loai.php?slug=<?php echo $slug; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    </div>
    <?php include '../includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"></script>
</body>
</html>
