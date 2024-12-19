-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: sql101.infinityfree.com
-- Thời gian đã tạo: Th12 19, 2024 lúc 06:27 AM
-- Phiên bản máy phục vụ: 10.6.19-MariaDB
-- Phiên bản PHP: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `if0_37931850_truyentranhnet`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comic_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lichsudoc`
--

CREATE TABLE `lichsudoc` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `truyen_id` int(11) NOT NULL,
  `read_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `replies`
--

CREATE TABLE `replies` (
  `reply_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reply_text` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `truyen`
--

CREATE TABLE `truyen` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `thumb_url` varchar(255) NOT NULL,
  `origin_name` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `views` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `truyen`
--

INSERT INTO `truyen` (`id`, `name`, `slug`, `thumb_url`, `origin_name`, `status`, `updated_at`, `views`, `created_at`) VALUES
(1, 'Wind Breaker', 'wind-breaker', 'wind-breaker-thumb.jpg', '', 'ongoing', '2024-12-16 04:18:20', 0, '2024-12-17 02:44:53'),
(2, 'Zannen Jokanbu Black General-san', 'zannen-jokanbu-black-general-san', 'zannen-jokanbu-black-general-san-thumb.jpg', '', 'ongoing', '2024-12-16 04:19:01', 0, '2024-12-17 02:45:26'),
(3, 'Xác sống cuối cùng', 'xac-song-cuoi-cung', 'xac-song-cuoi-cung-thumb.jpg', '', 'ongoing', '2024-12-16 04:18:42', 0, '2024-12-17 02:45:38'),
(4, 'Trụ Vương Tái Sinh Không Muốn Làm Đại Phản Diện', 'tru-vuong-tai-sinh-khong-muon-lam-dai-phan-dien', 'tru-vuong-tai-sinh-khong-muon-lam-dai-phan-dien-thumb.jpg', '', 'ongoing', '2024-12-16 04:15:55', 0, '2024-12-17 02:45:56'),
(5, 'Thiên Quỷ Huyệt Đạo', 'thien-quy-huyet-dao', 'thien-quy-huyet-dao-thumb.jpg', '', 'ongoing', '2024-12-16 04:14:12', 0, '2024-12-17 02:49:52'),
(6, 'Worst Ấn Bản Mới', 'worst-an-ban-moi', 'worst-an-ban-moi-thumb.jpg', '', 'ongoing', '2024-12-16 04:18:32', 33, '2024-12-17 02:57:03'),
(7, 'Vương Quốc Huyết Mạch', 'vuong-quoc-huyet-mach', 'vuong-quoc-huyet-mach-thumb.jpg', '', 'ongoing', '2024-12-16 04:17:22', 0, '2024-12-17 03:00:28'),
(8, 'We-On: Be The Shield', 'we-on-be-the-shield', 'we-on-be-the-shield-thumb.jpg', '', 'ongoing', '2024-12-16 04:17:57', 0, '2024-12-17 03:09:54'),
(9, 'Vạn Tra Triêu Hoàng', 'van-tra-trieu-hoang', 'van-tra-trieu-hoang-thumb.jpg', '', 'ongoing', '2024-12-16 04:16:39', 0, '2024-12-17 03:48:30'),
(10, 'Xuyên Nhanh Ký Chủ Cô Ấy Một Lòng Muốn Chết', 'xuyen-nhanh-ky-chu-co-ay-mot-long-muon-chet', 'xuyen-nhanh-ky-chu-co-ay-mot-long-muon-chet-thumb.jpg', 'Xuyên Nhanh: Kí Chủ Muốn Chết', 'ongoing', '2024-12-17 09:06:28', 0, '2024-12-17 09:59:05'),
(11, 'Tuyệt Mỹ Bạch Liên Online Dạy Học', 'tuyet-my-bach-lien-online-day-hoc', 'tuyet-my-bach-lien-online-day-hoc-thumb.jpg', '', 'ongoing', '2024-12-17 09:05:27', 0, '2024-12-17 10:04:40'),
(12, 'The Kurosagi corpse delivery service', 'the-kurosagi-corpse-delivery-service', 'the-kurosagi-corpse-delivery-service-thumb.jpg', '', 'ongoing', '2024-12-17 09:03:11', 0, '2024-12-17 10:06:34'),
(13, 'Unmei No Makimodoshi', 'unmei-no-makimodoshi', 'unmei-no-makimodoshi-thumb.jpg', '', 'ongoing', '2024-12-17 09:05:44', 0, '2024-12-17 10:09:35'),
(14, 'TÃ´i Chiáº¿n Äáº¥u Má»™t MÃ¬nh', 'toi-chien-dau-mot-minh', 'toi-chien-dau-mot-minh-thumb.jpg', '', 'ongoing', '2024-12-17 04:04:36', 0, '2024-12-17 13:55:57'),
(15, 'Thá»‰nh CÃ¹ng Ta Äá»“ng MiÃªn-Xin HÃ£y Ngá»§ CÃ¹ng Ta', 'thinh-cung-ta-dong-mien-xin-hay-ngu-cung-ta', 'thinh-cung-ta-dong-mien-xin-hay-ngu-cung-ta-thumb.jpg', '', 'ongoing', '2024-12-17 04:03:45', 0, '2024-12-17 14:11:11'),
(16, 'Sát Thủ Peter', 'sat-thu-peter', 'sat-thu-peter-thumb.jpg', 'SÁT THỦ PETER 1', 'ongoing', '2024-12-17 04:01:49', 0, '2024-12-17 15:14:06'),
(17, 'Tóm Lại Là Em Dễ Thương Được Chưa ?', 'tom-lai-la-em-de-thuong-duoc-chua', 'tom-lai-la-em-de-thuong-duoc-chua-thumb.jpg', '', 'coming_soon', '2024-12-17 04:05:17', 1, '2024-12-17 15:21:19'),
(18, 'Solo Leveling Arise: Nguồn Gốc Của Thợ Săn', 'solo-leveling-arise-nguon-goc-cua-tho-san', 'solo-leveling-arise-nguon-goc-cua-tho-san-thumb.jpg', '', 'ongoing', '2024-12-05 22:24:10', 112, '2024-12-17 15:42:34'),
(19, 'Xin Chào! Bác Sĩ Thú Y', 'xin-chao-bac-si-thu-y', 'xin-chao-bac-si-thu-y-thumb.jpg', '', 'ongoing', '2024-12-17 04:06:15', 0, '2024-12-18 01:53:07'),
(20, 'Ane no yuujin', 'ane-no-yuujin', 'ane-no-yuujin-thumb.jpg', 'Bạn của chị gái tôi', 'completed', '2024-10-22 00:14:14', 1, '2024-12-18 01:55:43'),
(21, 'No. 5', 'no-5', 'no-5-thumb.jpg', '', 'ongoing', '2023-10-11 13:38:38', 0, '2024-12-18 01:58:27'),
(22, 'Rosen Garten Saga', 'rosen-garten-saga', 'rosen-garten-saga-thumb.jpg', '', 'ongoing', '2024-07-22 07:04:24', 0, '2024-12-18 02:00:22'),
(23, 'Em Cho Cô Mượn Chút Lửa Nhé?', 'em-cho-co-muon-chut-lua-nhe', 'em-cho-co-muon-chut-lua-nhe-thumb.jpg', '', 'ongoing', '2024-05-10 03:14:52', 0, '2024-12-18 02:01:07'),
(24, 'Đại Chiến Người Khổng Lồ', 'dai-chien-nguoi-khong-lo', 'dai-chien-nguoi-khong-lo-thumb.jpg', '', 'completed', '2023-12-10 03:49:57', 0, '2024-12-18 02:40:56'),
(25, 'Nhà Tôi Có Một Con Chuột', 'nha-toi-co-mot-con-chuot', 'nha-toi-co-mot-con-chuot-thumb.jpg', '', 'ongoing', '2024-04-27 01:38:38', 1, '2024-12-18 02:44:55'),
(26, 'The Fragrant Flower Blooms With Dignity - Kaoru Hana Wa Rin To Saku', 'the-fragrant-flower-blooms-with-dignity-kaoru-hana-wa-rin-to-saku', 'the-fragrant-flower-blooms-with-dignity-kaoru-hana-wa-rin-to-saku-thumb.jpg', 'Những đóa hoa thơm nở diễm kiều', 'coming_soon', '2024-12-04 21:48:43', 0, '2024-12-18 04:38:45'),
(27, 'Tôi Là Vị Hôn Thê Của Nam Phụ Phản Diện', 'toi-la-vi-hon-the-cua-nam-phu-phan-dien', 'toi-la-vi-hon-the-cua-nam-phu-phan-dien-thumb.jpg', '', 'ongoing', '2024-12-17 04:05:01', 0, '2024-12-18 04:44:02'),
(28, 'Thứ mà đôi ta mong muốn', 'thu-ma-doi-ta-mong-muon', 'thu-ma-doi-ta-mong-muon-thumb.jpg', 'Fechippuru ~ bokura no junsuina koi', 'ongoing', '2024-12-17 04:04:00', 0, '2024-12-18 04:44:33'),
(29, 'Tôi Bị Hiểu Lầm Là Diễn Viên Thiên Tài Quái Vật', 'toi-bi-hieu-lam-la-dien-vien-thien-tai-quai-vat', 'toi-bi-hieu-lam-la-dien-vien-thien-tai-quai-vat-thumb.jpg', '', 'ongoing', '2024-12-17 04:04:25', 0, '2024-12-18 06:19:15'),
(30, 'Thì Ra Thư Ký Chu Là Người Như Vậy', 'thi-ra-thu-ky-chu-la-nguoi-nhu-vay', 'thi-ra-thu-ky-chu-la-nguoi-nhu-vay-thumb.jpg', '', 'ongoing', '2024-12-17 04:03:23', 0, '2024-12-18 06:20:59'),
(31, 'Fantasy Bishoujo Juniku Ojisan To', 'fantasy-bishoujo-juniku-ojisan-to', 'fantasy-bishoujo-juniku-ojisan-to-thumb.jpg', '', 'ongoing', '2024-12-18 02:17:31', 0, '2024-12-18 07:19:09'),
(32, 'Solo Leveling Ragnarok', 'solo-leveling-ragnarok', 'solo-leveling-ragnarok-thumb.jpg', '', 'ongoing', '2024-12-15 23:09:29', 292, '2024-12-18 07:48:01'),
(33, 'Solo Leveling SS3', 'solo-leveling-ss3', 'solo-leveling-ss3-thumb.jpg', 'Tôi Thăng Cấp Một Mình SS3', 'ongoing', '2024-01-17 00:59:11', 240, '2024-12-18 07:48:09'),
(34, 'Sống Chung Chỉ Là Để Chinh Phục Em', 'song-chung-chi-la-de-chinh-phuc-em', 'song-chung-chi-la-de-chinh-phuc-em-thumb.jpg', '', 'ongoing', '2024-12-18 02:28:45', 1, '2024-12-18 08:15:31'),
(35, 'Vết Trăng', 'vet-trang', 'vet-trang-thumb.jpg', '', 'ongoing', '2024-12-18 02:33:07', 7, '2024-12-18 08:21:55'),
(36, 'Thống Lĩnh Học Viện Chỉ Bằng Dao Sashimi', 'thong-linh-hoc-vien-chi-bang-dao-sashimi', 'thong-linh-hoc-vien-chi-bang-dao-sashimi-thumb.jpg', '', 'ongoing', '2024-12-18 02:30:20', 4, '2024-12-18 08:21:58'),
(37, 'Quỷ dị khôi phục ta có thể hóa thân thành đại yêu', 'quy-di-khoi-phuc-ta-co-the-hoa-than-thanh-dai-yeu', 'quy-di-khoi-phuc-ta-co-the-hoa-than-thanh-dai-yeu-thumb.jpg', '', 'ongoing', '2024-12-18 02:28:15', 1, '2024-12-18 08:23:53'),
(38, 'Vạn Cổ Tối Cường Tông', 'van-co-toi-cuong-tong', 'van-co-toi-cuong-tong-thumb.jpg', '', 'ongoing', '2024-12-18 02:32:53', 6, '2024-12-18 08:24:19'),
(39, 'Kiêm Chức Thần Tiên', 'kiem-chuc-than-tien', 'kiem-chuc-than-tien-thumb.jpg', '', 'ongoing', '2024-12-17 03:46:29', 19, '2024-12-18 08:36:53'),
(40, 'Sống Sót Như Một Hầu Gái Trong Trò Chơi Kinh Dị', 'song-sot-nhu-mot-hau-gai-trong-tro-choi-kinh-di', 'song-sot-nhu-mot-hau-gai-trong-tro-choi-kinh-di-thumb.jpg', 'Tồn Tại Với Tư Cách Hầu Gái Trong Game Kinh Dị', 'coming_soon', '2024-12-17 04:02:13', 1, '2024-12-18 08:44:06'),
(41, 'Bên bếp lửa nhà Alice-san', 'ben-bep-lua-nha-alice-san', 'ben-bep-lua-nha-alice-san-thumb.jpg', 'Alice', 'ongoing', '2024-11-17 22:26:34', 1, '2024-12-18 08:53:22'),
(42, 'Yasei No Last Boss Ga Arawareta', 'yasei-no-last-boss-ga-arawareta', 'yasei-no-last-boss-ga-arawareta-thumb.jpg', 'A Wild Last Boss Appeared', 'ongoing', '2024-12-18 02:33:30', 51, '2024-12-18 09:31:06'),
(43, 'Thực Ra Tôi Mới Là Thật', 'thuc-ra-toi-moi-la-that', 'thuc-ra-toi-moi-la-that-thumb.jpg', 'Tôi Là Minh Chứng Của Sự Thật', 'coming_soon', '2024-12-18 02:30:59', 1, '2024-12-18 09:50:53'),
(44, 'Trở Thành Thiên Tài Tốc Biến Của Học Viện Ma Pháp', 'tro-thanh-thien-tai-toc-bien-cua-hoc-vien-ma-phap', 'tro-thanh-thien-tai-toc-bien-cua-hoc-vien-ma-phap-thumb.jpg', '', 'ongoing', '2024-12-18 02:32:41', 9, '2024-12-18 09:55:30'),
(45, 'Tôi Trở Thành Vợ Nam Chính', 'toi-tro-thanh-vo-nam-chinh', 'toi-tro-thanh-vo-nam-chinh-thumb.jpg', '', 'ongoing', '2024-12-18 02:32:26', 65, '2024-12-18 10:08:17'),
(46, 'Thanh Gươm Diệt Quỷ', 'thanh-guom-diet-quy', 'thanh-guom-diet-quy-thumb.jpg', 'Kimetsu no Yaiba', 'completed', '2024-05-15 01:24:07', 362, '2024-12-18 10:14:31'),
(47, 'Thiên Hạ Đệ Nhất Đại Sư Huynh', 'thien-ha-de-nhat-dai-su-huynh', 'thien-ha-de-nhat-dai-su-huynh-thumb.jpg', 'Thiên Hạ Đệ Nhất Đại Huynh', 'ongoing', '2024-12-18 02:29:56', 3, '2024-12-18 10:57:56'),
(48, 'Phương Pháp Che Giấu Đứa Con Của Hoàng Đế', 'phuong-phap-che-giau-dua-con-cua-hoang-de', 'phuong-phap-che-giau-dua-con-cua-hoang-de-thumb.jpg', 'Cách Che Giấu Đứa Con Của Hoàng Đế', 'ongoing', '2024-12-18 02:27:55', 1, '2024-12-18 11:40:29'),
(49, 'Senryuu Shoujo', 'senryuu-shoujo', 'senryuu-shoujo-thumb.jpg', 'Cô nàng làm thơ', 'ongoing', '2024-12-18 02:28:29', 1, '2024-12-18 11:42:23'),
(50, 'Trở Thành Cô Cháu Gái Bị Khinh Miệt Của Gia Tộc Võ Lâm', 'tro-thanh-co-chau-gai-bi-khinh-miet-cua-gia-toc-vo-lam', 'tro-thanh-co-chau-gai-bi-khinh-miet-cua-gia-toc-vo-lam-thumb.jpg', 'Trở thành cô cháu gái bị khinh miệt của nhà quyền quý', 'completed', '2024-11-06 22:27:35', 2, '2024-12-18 11:50:11'),
(51, 'Sống Trong Ngôi Nhà Cấp 4', 'song-trong-ngoi-nha-cap-4', 'song-trong-ngoi-nha-cap-4-thumb.jpg', 'Hirayasumi', 'ongoing', '2024-12-18 02:28:56', 1, '2024-12-18 12:41:39'),
(52, 'Cylcia = Code', 'cylcia-code-123', 'cylcia--code-123-thumb.jpg', '', 'ongoing', '2024-01-17 22:42:46', 1, '2024-12-18 13:06:11'),
(53, 'Chú Tôi Ở Dị Giới', 'chu-toi-o-di-gioi', 'chu-toi-o-di-gioi-thumb.jpg', '', 'ongoing', '2024-12-18 07:48:53', 2, '2024-12-18 13:13:25'),
(54, 'Có Nhỏ Vợ Cũ Hồi Xuân Trong Lớp Tôi', 'co-nho-vo-cu-hoi-xuan-trong-lop-toi', 'co-nho-vo-cu-hoi-xuan-trong-lop-toi-thumb.jpg', 'Ore no Kurasu ni Wakagaetta Moto Yome ga Iru', 'ongoing', '2024-12-03 03:43:38', 1, '2024-12-18 13:45:01'),
(55, 'Trở Lại Ngày Tận Thế', 'tro-lai-ngay-tan-the', 'tro-lai-ngay-tan-the-thumb.jpg', '', 'ongoing', '2024-01-12 00:52:05', 1, '2024-12-18 13:46:16'),
(56, 'Thế Hệ Bất Hảo', 'the-he-bat-hao', 'the-he-bat-hao-thumb.jpg', 'Thế Hệ Bất Hảo', 'ongoing', '2024-12-18 07:48:49', 3, '2024-12-18 13:49:14'),
(57, 'Đại Pháp Sư Thần Thoại Tái Lâm', 'dai-phap-su-than-thoai-tai-lam', 'dai-phap-su-than-thoai-tai-lam-thumb.jpg', 'Đại Pháp Sư Thần Thoại Tái Lâm', 'ongoing', '2024-12-18 07:47:20', 1, '2024-12-18 13:51:44'),
(58, 'Hầu Gái Trong Trò Chơi Harem Ngược Muốn Nghỉ Việc', 'hau-gai-trong-tro-choi-harem-nguoc-muon-nghi-viec', 'hau-gai-trong-tro-choi-harem-nguoc-muon-nghi-viec-thumb.jpg', '', 'ongoing', '2024-12-18 07:48:45', 20, '2024-12-18 13:52:07'),
(59, 'Kiếm Thần: Thần Chi Tử', 'kiem-than-than-chi-tu', 'kiem-than-than-chi-tu-thumb.jpg', '', 'ongoing', '2024-12-18 07:48:57', 1, '2024-12-18 13:52:14'),
(60, 'Trợ lí pháp sư vô dụng bắt đầu cuộc sống mới', 'tro-li-phap-su-vo-dung-bat-dau-cuoc-song-moi', 'tro-li-phap-su-vo-dung-bat-dau-cuoc-song-moi-thumb.jpg', 'Ore Igai Dare mo Saishu Dekinai Sozai na no ni \"Sozai Saishuritsu ga Hikui\" to Pawahara suru Osananajimi Renkinjutsushi to Zetsuen shita Senzoku Madoushi', 'ongoing', '2024-07-14 02:56:54', 2, '2024-12-18 13:52:27'),
(61, 'Cô Dâu Hiến Tế Của Thủy Thần', 'co-dau-hien-te-cua-thuy-than', 'co-dau-hien-te-cua-thuy-than-thumb.jpg', '', 'ongoing', '2024-12-18 07:47:29', 1, '2024-12-18 13:55:11'),
(62, 'Lượng Mana Đáy Xã Hội! Ta Vô Địch Nhờ Kỹ Năng Của Mình', 'luong-mana-day-xa-hoi-ta-vo-dich-nho-ky-nang-cua-minh', 'luong-mana-day-xa-hoi-ta-vo-dich-nho-ky-nang-cua-minh-thumb.jpg', 'Lượng Mana Đáy Xã Hội! Ta Vô Địch Nhờ Kỹ Năng Của Mình', 'ongoing', '2024-12-18 07:48:14', 2, '2024-12-18 13:55:31'),
(63, 'Khi Điện Thoại Đổ Chuông', 'khi-dien-thoai-do-chuong', 'khi-dien-thoai-do-chuong-thumb.jpg', 'Khi Điện Thoại Đổ Chuông', 'ongoing', '2024-12-18 07:49:01', 1, '2024-12-19 02:38:43'),
(64, 'Nữ Hiệp Sĩ Goblin', 'nu-hiep-si-goblin', 'nu-hiep-si-goblin-thumb.jpg', 'Felmale Knight Gobin', 'ongoing', '2024-05-24 08:27:40', 1, '2024-12-19 05:29:37'),
(65, 'Album Natural Wallpapers', 'album-natural-wallpapers', 'album-natural-wallpapers-thumb.jpg', '', 'ongoing', '2023-12-16 22:51:18', 1, '2024-12-19 05:29:57'),
(66, 'Cả Nhà Bạo Quân Đều Dựa Vào Việc Đọc Tiếng Lòng Của Cô Ấy Để Giữ Mạng', 'ca-nha-bao-quan-deu-dua-vao-viec-doc-tieng-long-cua-co-ay-de-giu-mang', 'ca-nha-bao-quan-deu-dua-vao-viec-doc-tieng-long-cua-co-ay-de-giu-mang-thumb.jpg', '', 'ongoing', '2024-12-18 07:48:24', 2, '2024-12-19 05:33:38'),
(67, 'The Ride On King', 'the-ride-on-king', 'the-ride-on-king-thumb.jpg', 'Hành Trình Của Đại Đế', 'ongoing', '2024-12-18 07:48:05', 1, '2024-12-19 05:34:11'),
(68, 'Tình Yêu Màu Lam Nhà Wakaba', 'tinh-yeu-mau-lam-nha-wakaba', 'tinh-yeu-mau-lam-nha-wakaba-thumb.jpg', 'Tình Yêu Màu Lam Nhà Wakaba', 'ongoing', '2024-12-18 07:48:41', 1, '2024-12-19 05:40:45'),
(69, 'Coffee wo Shizuka ni', 'coffee-wo-shizuka-ni', 'coffee-wo-shizuka-ni-thumb.jpg', '', 'completed', '2024-10-13 05:02:16', 1, '2024-12-19 07:43:55'),
(70, 'Ánh Trăng Vì Tôi Mà Đến', 'anh-trang-vi-toi-ma-den', 'anh-trang-vi-toi-ma-den-thumb.jpg', '', 'ongoing', '2024-12-19 05:48:05', 2, '2024-12-19 10:53:51');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `reset_token` varchar(100) DEFAULT NULL,
  `roles` enum('admin','user') NOT NULL DEFAULT 'user',
  `google_auth_secret` varchar(32) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `score` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `yeuthich`
--

CREATE TABLE `yeuthich` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `truyen_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `comic_id` (`comic_id`);

--
-- Chỉ mục cho bảng `lichsudoc`
--
ALTER TABLE `lichsudoc`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `truyen_id` (`truyen_id`);

--
-- Chỉ mục cho bảng `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`reply_id`),
  ADD KEY `comment_id` (`comment_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `truyen`
--
ALTER TABLE `truyen`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username_unique` (`username`),
  ADD UNIQUE KEY `email_unique` (`email`);

--
-- Chỉ mục cho bảng `yeuthich`
--
ALTER TABLE `yeuthich`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `lichsudoc`
--
ALTER TABLE `lichsudoc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `replies`
--
ALTER TABLE `replies`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `truyen`
--
ALTER TABLE `truyen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `yeuthich`
--
ALTER TABLE `yeuthich`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`comic_id`) REFERENCES `truyen` (`id`);

--
-- Các ràng buộc cho bảng `lichsudoc`
--
ALTER TABLE `lichsudoc`
  ADD CONSTRAINT `lichsudoc_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `lichsudoc_ibfk_2` FOREIGN KEY (`truyen_id`) REFERENCES `truyen` (`id`);

--
-- Các ràng buộc cho bảng `replies`
--
ALTER TABLE `replies`
  ADD CONSTRAINT `replies_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`),
  ADD CONSTRAINT `replies_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Các ràng buộc cho bảng `yeuthich`
--
ALTER TABLE `yeuthich`
  ADD CONSTRAINT `yeuthich_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
