<?php
include('config/database.php');

// Đặt URL của API từ Otruyen API
session_start();
$api_url = "https://otruyenapi.com/v1/api/home"; // API gốc để lấy danh sách truyện
$isIndexPage = basename($_SERVER['PHP_SELF']) === 'index.php';
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
    $truyenList = $data['data']['items']; // Lấy danh sách các truyện
} else {
    $truyenList = []; // Nếu không có dữ liệu, trả về mảng trống
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

// Lấy danh sách truyện được xem nhiều nhất
function getMostViewedTruyen() {
    global $conn;
    $query = "SELECT * FROM truyen ORDER BY views DESC LIMIT 12"; // Lấy 12 truyện được xem nhiều nhất
    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC); 
}

// Lấy danh sách người dùng hàng đầu
function getTopUsers() {
    global $conn;
    $query = "SELECT user_id, username, name, email, avatar, score FROM users ORDER BY score DESC LIMIT 10"; // Lấy 10 người dùng có điểm cao nhất
    $result = $conn->query($query);
    return $result->fetch_all(MYSQLI_ASSOC);
}
$topUsers = getTopUsers();
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
    <meta name="keywords" content="truyện tranh, manga, manhwa, manhua, đọc truyện miễn phí, TRUYENTRANHNET">
    <meta name="description" content="Đọc truyện tranh miễn phí tại TRUYENTRANHNET. Tìm kiếm manga, manhwa, manhua yêu thích và khám phá các bộ truyện mới nhất.">
    <meta name="description" content="Thế giới truyện tranh hoàn toàn miễn phí được cập nhật liên tục mỗi ngày, luôn hướng tới trải nghiệm người dùng nâng cao chất lượng web.">
    <meta property="og:title" content="TRUYENTRANHNET - ĐỌC TRUYỆN MIỄN PHÍ">
    <script type="application/ld+json">
        {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "TRUYENTRANHNET",
        "url": "https://www.truyentranhnet.free.nf",
        "description": "Đọc truyện tranh miễn phí tại TRUYENTRANHNET. Tìm kiếm manga, manhwa, manhua yêu thích và khám phá các bộ truyện mới nhất."
        }
    </script>
    <meta property="og:description" content="Đọc truyện tranh miễn phí tại TRUYENTRANHNET. Tìm kiếm manga, manhwa, manhua yêu thích và khám phá các bộ truyện mới nhất.">
    <meta property="og:image" content="https://www.truyentranhnet.com/img/logo.png">
    <meta property="og:url" content="https://www.truyentranhnet.com">
    <meta name="robots" content="index, follow">
    <link href="img/logo.png" rel="icon">
    <title>TRUYENTRANHNET - Đọc Truyện Miễn Ph</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <style>
            @keyframes lightning {
            0% {
                border-color: gold;
                box-shadow: 0 4px 10px rgba(255, 215, 0, 0.5);
            }
            50% {
                border-color: white;
                box-shadow: 0 0 20px rgba(255, 255, 255, 0.8);
            }
            100% {
                border-color: gold;
                box-shadow: 0 4px 10px rgba(255, 215, 0, 0.5);
            }
        }

        @keyframes fireworks {
            0% {
                opacity: 0;
                transform: scale(0.5);
            }
            50% {
                opacity: 1;
                transform: scale(1);
            }
            100% {
                opacity: 0;
                transform: scale(1.5);
            }
        }

        .vip-card {
            border: 2px solid gold; /* Gold border for VIP effect */
            box-shadow: 0 4px 10px rgba(255, 215, 0, 0.5); /* Gold shadow */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative; /* Position for absolute ranking icon */
            overflow: hidden; /* Ensure the fireworks stay within the card */
            animation: lightning 2s infinite; /* Add lightning effect */
        }

        .vip-card::before,
        .vip-card::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, rgba(255, 215, 0, 0.5), rgba(255, 215, 0, 0));
            border-radius: 50%;
            opacity: 0;
            animation: fireworks 2s infinite;
            z-index: 1; 
        }

        .vip-card::after {
            animation-delay: 1s;
        }

        .vip-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(255, 215, 0, 0.8);
        }

        .ranking-icon {
            position: absolute;
            top: 10px;
            left: 10px;
            font-size: 24px; /* Adjust size as needed */
            color: gold; /* Icon color */
            z-index: 10; /* Ensure icon is above other elements */
            background: rgba(0, 0, 0, 0.5); /* Background for better visibility */
            padding: 5px; /* Padding for better spacing */
            border-radius: 50%; /* Rounded for aesthetics */
            box-shadow: 0 0 10px rgba(255, 215, 0, 0.8); /* Glow effect */
            animation: pulse 2s infinite; /* Pulsating effect */
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
            }
        }
        .user-name {
            white-space: nowrap;
            overflow: hidden; 
            text-overflow: ellipsis; 
            max-width: 200px; 
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container my-4">
        <?php if ($isIndexPage): ?>
            <div class="row mb-4">
                <div class="col-12">
                    <div id="header-carousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="img/carousel-1.jpg" class="d-block w-100" alt="Hình ảnh 1">
                            </div>
                            <div class="carousel-item">
                                <img src="img/carousel-2.jpg" class="d-block w-100" alt="Hình ảnh 2">
                            </div>
                            <div class="carousel-item">
                                <img src="img/carousel-3.jpg" class="d-block w-100" alt="Hình ảnh 3">
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#header-carousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <h4 class="section-title text-center mb-4">TRUYỆN TRANH MỚI CẬP NHẬT</h4>
        <div class="row g-4">
            <?php if (!empty($truyenList)): ?>
                <?php foreach ($truyenList as $truyen): ?>
                   <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="card">
                            <!-- Link và hình ảnh -->
                            <a href="views/truyen-detail.php?slug=<?php echo urlencode($truyen['slug']); ?>" class="text-decoration-none">
                                <img src="https://img.otruyenapi.com/uploads/comics/<?php echo $truyen['thumb_url']; ?>" 
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
        <br>
        <br><hr>
        <h4 class="section-title text-center mb-4">TOP TRUYỆN ĐƯỢC XEM NHIỀU NHẤT</h4>
        <br>
        <div class="row g-4">
            <?php
            $mostViewedTruyen = getMostViewedTruyen(); 
            if (!empty($mostViewedTruyen)): ?>
                <?php foreach ($mostViewedTruyen as $index => $truyen): ?>
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="card <?php echo ($index < 3) ? 'vip-card' : ''; ?>">
                            <!-- Ranking Icon -->
                            <?php if ($index === 0): ?>
                                <div class="ranking-icon top1">🥇</div>
                            <?php elseif ($index === 1): ?>
                                <div class="ranking-icon top2">🥈</div>
                            <?php elseif ($index === 2): ?>
                                <div class="ranking-icon top3">🥉</div>
                            <?php endif; ?>

                            <!-- Link và hình ảnh -->
                            <a href="views/truyen-detail.php?slug=<?php echo urlencode($truyen['slug']); ?>" class="text-decoration-none">
                                <img src="https://img.otruyenapi.com/uploads/comics/<?php echo $truyen['thumb_url']; ?>" 
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
                    <p class="text-center">Không có truyện được xem nhiều nhất để hiển thị.</p>
                </div>
            <?php endif; ?>
        </div>

        <br>
        <hr>
        <h4 class="section-title text-center mb-4">TOP THÀNH VIÊN</h4>
        <br>
        <div class="row g-4">
            <?php if (!empty($topUsers)): ?>
                <?php foreach ($topUsers as $user): ?>
                    <div class="col-6 col-sm-4 col-md-3 col-lg-2">
                        <div class="card">
                            <a href="#" class="text-decoration-none">
                                <img src="<?php echo htmlspecialchars($user['avatar'] ?? 'default-avatar.png'); ?>" 
                                    class="card-img-top rounded-circle" 
                                    alt="<?php echo htmlspecialchars($user['name']); ?>"
                                    loading="lazy">
                            </a>
                            <div class="card-body text-center">
                                <h5 class="user-name text-dark fw-bold mb-2" title="<?php echo htmlspecialchars($user['name'] ?? $user['username']); ?>">
                                    <?php 
                                        $displayName = htmlspecialchars($user['name'] ?? $user['username']);
                                        echo (strlen($displayName) > 20) ? substr($displayName, 0, 20) . '...' : $displayName; 
                                    ?>
                                </h5>
                                <p class="small text-secondary">
                                    <!--<?php echo htmlspecialchars($user['email']); ?>-->
                                </p>
                                <p class="small text-primary">
                                    Điểm: <?php echo htmlspecialchars(number_format($user['score'])); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <p class="text-center">Không có người dùng nào để hiển thị.</p>
                </div>
            <?php endif; ?>
        </div>
        </div>
    

    <?php include 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>