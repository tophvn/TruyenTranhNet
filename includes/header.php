<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . '/views';

// Đường dẫn API
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
if (isset($data['data']) && !empty($data['data']['items'])) {
    $categories = $data['data']['items'];
} else {
    $categories = [];
}

function getAvatarPath($avatar) {
    return ltrim($avatar, '../');
}
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand text-warning fw-bold" href="<?= $base_url; ?>/../">𝐓𝐑𝐔𝐘𝐄𝐍𝐓𝐑𝐀𝐍𝐇𝐍𝐄𝐓</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="d-flex ms-auto align-items-center gap-2">
                <!-- Thanh tìm kiếm -->
                <form class="d-flex me-auto search-box my-2 my-lg-0" onsubmit="return performSearch()">
                    <div class="input-group">
                        <input type="text" id="searchInput" class="form-control rounded-start" placeholder="Tìm kiếm truyện..." onkeyup="searchFunction(event)">
                        <button class="btn btn-warning rounded-end" type="button" onclick="performSearch()">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
                <?php if (isset($_SESSION['user'])): ?>
                    <div class="dropdown">
                        <a class="btn btn-warning dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="<?= !empty($_SESSION['user']['avatar']) ? htmlspecialchars($_SESSION['user']['avatar']) : $base_url . '/img/default-avatar.jpg'; ?>" alt="Avatar" class="rounded-circle" width="30" height="30">
                            <?= htmlspecialchars($_SESSION['user']['name']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
                            <li><a class="dropdown-item" href="<?= $base_url; ?>/tai-khoan">Tài khoản</a></li>
                            <li><a class="dropdown-item" href="<?= $base_url; ?>/yeu-thich">Yêu thích</a></li>
                            <li><a class="dropdown-item" href="<?= $base_url; ?>/lich-su-doc">Lịch sử đọc</a></li>
                            <li><a class="dropdown-item text-danger" href="<?= $base_url; ?>/logout">Đăng xuất</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a href="<?= $base_url; ?>/login" class="btn btn-outline-warning me-2">Đăng nhập</a>
                    <a href="<?= $base_url; ?>/register" class="btn btn-warning">Đăng ký</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<style>
.dropdown-menu {
    max-height: 400px; 
    overflow-y: auto; 
}
</style>

<div class="genre-nav py-2" style="margin-top: 70px;">
    <div class="container">
        <ul class="nav justify-content-center flex-wrap">
            <li class="nav-item">
                <a class="nav-link text-dark" href="<?= $base_url; ?>/truyen-moi">TRUYỆN MỚI</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="<?= $base_url; ?>/dang-phat-hanh">ĐANG PHÁT HÀNH</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="<?= $base_url; ?>/hoan-thanh">HOÀN THÀNH</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="<?= $base_url; ?>/sap-ra-mat">SẮP RA MẮT</a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle text-dark" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    THỂ LOẠI
                </a>
                <ul class="dropdown-menu p-3" aria-labelledby="navbarDropdown" style="max-height: 400px; overflow-y: auto;">
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <li><a class="dropdown-item" href="<?= $base_url; ?>/truyen-theo-the-loai?slug=<?= htmlspecialchars($category['slug']); ?>"><?= htmlspecialchars($category['name']); ?></a></li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li><a class="dropdown-item" href="#">Không có thể loại</a></li>
                    <?php endif; ?>
                </ul>
            </li>
        </ul>
        <marquee class="text-danger fw-bold" style="font-size: 1.2rem;">
            TRUYENTRANHNET BY TOPH - ĐỌC TRUYỆN MIỄN PHÍ KHÔNG QUẢNG CÁO - TRUYỆN TRANH CẬP NHẬT MỚI NHẤT 24/7.
        </marquee>
    </div>
</div>

<script>
function performSearch() {
    const keyword = document.getElementById('searchInput').value;
    if (keyword) {
        window.location.href = '<?= $base_url; ?>/tim-kiem?keyword=' + encodeURIComponent(keyword);
    }
    return false; 
}

function searchFunction(event) {
    if (event.key === 'Enter') {
        performSearch();
    }
}
</script>
