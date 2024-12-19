<?php
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['PHP_SELF']), '/');
$in_views_folder = strpos($_SERVER['PHP_SELF'], '/views/') !== false;
$views_prefix = $in_views_folder ? '' : '/views';
?>
<footer class="bg-dark text-white py-3">
    <div class="container">
        <nav class="d-flex justify-content-center mb-3">
            <a href="<?= $base_url . $views_prefix; ?>/ve-chung-toi" class="text-white mx-2">Về Chúng Tôi</a> |
            <a href="<?= $base_url . $views_prefix; ?>/chinh-sach-bao-mat" class="text-white mx-2">Chính Sách Bảo Mật</a> |
            <a href="<?= $base_url . $views_prefix; ?>/lien-he" class="text-white mx-2">Liên Hệ</a>
        </nav>
        <p class="text-center mb-0">© 2024 TRUYENTRANHNET. Đọc truyện tranh miễn phí cập nhật nhanh nhất.</p>
    </div>
</footer>
