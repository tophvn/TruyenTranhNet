<?php
// Kết nối đến cơ sở dữ liệu
include('../config/database.php');

// Lấy dữ liệu từ API
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
$apiUrl = "https://otruyenapi.com/v1/api/truyen-tranh/" . urlencode($slug);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

if ($response === false) {
    echo "Không thể kết nối tới API.";
    exit;
}

$comic = json_decode($response, true);
if (!$comic || !isset($comic['data']['item'])) {
    echo "Có lỗi xảy ra, vui lòng reload lại trang!";
    exit;
}

$comicData = $comic['data']['item'];

// Lưu thông tin truyện vào cơ sở dữ liệu
function saveComicToDatabase($comicData) {
    global $conn;

    $updatedAt = isset($comicData['updatedAt']) ? 
        date('Y-m-d H:i:s', strtotime($comicData['updatedAt'])) : null;

    $checkQuery = "SELECT id FROM truyen WHERE slug = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("s", $comicData['slug']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $insertQuery = "INSERT INTO truyen (name, slug, thumb_url, origin_name, status, updated_at, views) VALUES (?, ?, ?, ?, ?, ?, 0)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("ssssss", $comicData['name'], $comicData['slug'], $comicData['thumb_url'], $comicData['origin_name'][0], $comicData['status'], $updatedAt);
        $insertStmt->execute();
    }
}

saveComicToDatabase($comicData);

function incrementViews($slug) {
    global $conn;
    $updateQuery = "UPDATE truyen SET views = views + 1 WHERE slug = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("s", $slug);
    $stmt->execute();
}

incrementViews($comicData['slug']); 

function formatDate($dateString) {
    if ($dateString) {
        $date = new DateTime($dateString);
        return $date->format('d/m/Y H:i');
    }
    return 'N/A';
}

function addToFavorites($userId, $truyenId) {
    global $conn;

    if (!$userId || !$truyenId) return false;

    $checkQuery = "SELECT id FROM yeuthich WHERE user_id = ? AND truyen_id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $userId, $truyenId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $insertQuery = "INSERT INTO yeuthich (user_id, truyen_id) VALUES (?, ?)";
        $insertStmt = $conn->prepare($insertQuery);
        $insertStmt->bind_param("ii", $userId, $truyenId);
        return $insertStmt->execute(); 
    }
    return false; // Đã tồn tại
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_favorites'])) {
    session_start(); 
    $userId = $_SESSION['user_id']; 
    $truyenId = $comicData['id']; 
    $added = addToFavorites($userId, $truyenId);
    echo json_encode(['success' => $added]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commentContent'])) {
    session_start();
    $userId = $_SESSION['user_id']; 
    $comicId = $comicData['id'];
    $content = $_POST['commentContent'];
    $insertCommentQuery = "INSERT INTO comments (user_id, comic_id, content) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertCommentQuery);
    $stmt->bind_param("iis", $userId, $comicId, $content);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../img/logo.png" rel="icon">
    <title>Chi Tiết Truyện</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .manga-detail {
            display: flex;
            flex-direction: column;
            margin-top: 20px;
        }
        .manga-info {
            display: flex;
            align-items: center; 
        }
        .manga-cover {
            width: 200px;
            height: auto;
            margin-right: 20px;
            border-radius: 10px;
        }
        .manga-stats {
            flex-grow: 1;
        }
        .rating {
            margin-bottom: 10px;
        }
        .tags {
            margin-top: 10px;
        }
        .tag {
            background-color: #e0e0e0;
            border-radius: 5px;
            padding: 5px 10px;
            margin-right: 5px;
        }
        .chapter-list {
            margin-top: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            background-color: #f9f9f9;
        }
        .chapter-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
            cursor: pointer;
        }
        .btn-more {
            margin-top: 10px;
        }
        .favorite-button {
            background-color: #ffcc00;
            border: none;
            padding: 5px 8px;
            border-radius: 5px;
            cursor: pointer;
        }
        @media (max-width: 576px) {
            .manga-info {
                flex-direction: column;
                align-items: flex-start;
            }

            .manga-cover {
                width: 100%;
                height: auto;
                margin-right: 0;
                margin-bottom: 15px;
            }

            .manga-stats {
                text-align: left;
                width: 100%;
            }

            .tags .tag {
                display: inline-block;
                margin-bottom: 5px;
            }

            .chapter-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .chapter-item span.date {
                margin-top: 5px;
                font-size: 0.9rem;
                color: #666;
            }

            .btn-more {
                width: 100%;
            }

            .description h2 {
                font-size: 1.2rem;
            }

            .modal-body {
                padding: 15px;
            }

            .nav-chapter {
                flex-wrap: wrap;
                gap: 10px;
            }
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php' ?>
    
    <main class="container">
        <div class="manga-detail">
            <div class="manga-info">
                <img src="https://img.otruyenapi.com/uploads/comics/<?php echo htmlspecialchars($comicData['thumb_url'] ?? ''); ?>" class="manga-cover" alt="<?php echo htmlspecialchars($comicData['name'] ?? 'N/A'); ?>">
                <div class="manga-stats">
                    <h1><?php echo htmlspecialchars($comicData['name'] ?? 'N/A'); ?></h1>
                    <p><strong>Tên gốc:</strong> <?php echo htmlspecialchars($comicData['origin_name'][0] ?? 'N/A'); ?></p>
                    <p><strong>Trạng thái:</strong> <?php echo htmlspecialchars($comicData['status'] ?? 'N/A'); ?></p>
                    <p><strong>Cập nhật lần cuối:</strong> <?php echo htmlspecialchars(formatDate($comicData['updatedAt'] ?? '')); ?></p>
                    <div class="rating">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <span>5/5</span>
                    </div>
                    <div class="tags">
                        <?php 
                        if (isset($comicData['category']) && is_array($comicData['category'])) {
                            foreach ($comicData['category'] as $cat) {
                                echo '<span class="tag">' . htmlspecialchars($cat['name']) . '</span>';
                            }
                        }
                        ?>
                    </div><br>
                    <button class="favorite-button" title="Thêm vào yêu thích" id="addToFavorites">
                        <i class="fas fa-heart"></i> Yêu thích
                    </button>
                </div>
            </div>
            
            <div class="description mt-3">
            <hr>
                <h2>Mô Tả</h2>
                <p><?php echo htmlspecialchars(strip_tags($comicData['content'] ?? '')); ?></p>
            </div>

            <div class="chapter-list">
                <h2 class="section-title">
                    <i class="fas fa-star"></i>
                    DANH SÁCH CHƯƠNG
                </h2>
                <div class="mb-3 text-center">
                    <div class="d-flex justify-content-center gap-3">
                        <button id="readFirstChapter" class="btn btn-primary w-100 w-md-auto">Đọc Từ Đầu</button>
                        <button id="readLatestChapter" class="btn btn-secondary w-100 w-md-auto">Đọc từ Chapter Mới Nhất</button>
                    </div>
                </div>
                <div class="chapters">
                    <?php
                    $chapters = [];
                    if (isset($comicData['chapters']) && is_array($comicData['chapters'])) {
                        foreach ($comicData['chapters'] as $chapter) {
                            foreach ($chapter['server_data'] as $data) {
                                $chapterUrl = isset($data['chapter_api_data']) ? htmlspecialchars($data['chapter_api_data']) : '#';
                                $chapterName = isset($data['chapter_name']) ? htmlspecialchars($data['chapter_name']) : 'Chương mới';
                                $chapterDate = formatDate($data['updatedAt'] ?? '');
                                
                                // Lưu thông tin chương vào mảng
                                $chapters[] = [
                                    'name' => $chapterName,
                                    'url' => $chapterUrl,
                                    'date' => $chapterDate
                                ];
                            }
                        }
                    } else {
                        echo "<p>Không có chương nào để hiển thị.</p>";
                    }
                    usort($chapters, function($a, $b) {
                        return strtotime($a['date']) - strtotime($b['date']);
                    });

                    // Hiển thị danh sách chương
                    foreach ($chapters as $chapter) {
                        echo '<div class="chapter-item" data-chapter-url="' . $chapter['url'] . '">
                                <span>' . 'Chapter: ' . $chapter['name'] . '</span>
                                <span class="date">' . $chapter['date'] . '</span>
                              </div>';
                    }
                    ?>
                </div>
                <button class="btn btn-secondary btn-more">Xem thêm ▼</button>
            </div>
        </div>
    </main>

    <!-- Modal -->
    <div class="modal fade" id="chapterModal" tabindex="-1" role="dialog" aria-labelledby="chapterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document"> 
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="chapterModalLabel">Đọc Truyện</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <button id="toggleFullscreen" class="btn btn-link" aria-label="Toggle Fullscreen">
                        <i class="fas fa-expand" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="modal-body" id="modalBody" style="max-height: 80vh; overflow-y: auto;">
                    <div class="nav-chapter d-flex justify-content-between align-items-center">
                        <div class="content flex-grow-1 text-center" id="chapterContent">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../includes/footer.php' ?>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('toggleFullscreen').addEventListener('click', function() {
            const modalDialog = document.querySelector('.modal-dialog');
            if (modalDialog.classList.contains('modal-fullscreen')) {
                modalDialog.classList.remove('modal-fullscreen');
                this.innerHTML = '<i class="fas fa-expand" aria-hidden="true"></i>';
            } else {
                modalDialog.classList.add('modal-fullscreen');
                this.innerHTML = '<i class="fas fa-compress" aria-hidden="true"></i>';
            }
        });

        let currentChapterIndex = 0;
        let chapters = <?php echo json_encode($chapters); ?>; 

        $(document).ready(function() {
            $('.chapter-item').on('click', function() {
                currentChapterIndex = $(this).index();
                loadChapter(currentChapterIndex);
            });

            $('#readFirstChapter').on('click', function() {
                loadChapter(0); // Đọc từ Chapter 1
            });

            $('#readLatestChapter').on('click', function() {
                loadChapter(chapters.length - 1); // Đọc từ Chapter Mới Nhất
            });

            $('#addToFavorites').on('click', function() {
                $.post('', { add_to_favorites: true }, function(data) {
                    const response = JSON.parse(data);
                    if (response.success) {
                        alert('Đã thêm vào yêu thích!');
                    } else {
                        alert('Truyện đã có trong danh sách yêu thích!');
                    }
                }).fail(function() {
                    alert('Có lỗi xảy ra khi thêm vào yêu thích.');
                });
            });
        });

        function loadChapter(index) {
            const chapterUrl = chapters[index].url;
            $.ajax({
                url: 'chapter.php',
                method: 'POST',
                data: { chapter_url: chapterUrl },
                success: function(response) {
                    $('#chapterContent').html(response);
                    $('#chapterModal').modal('show');
                },
                error: function() {
                    $('#chapterContent').html('<p class="alert alert-danger">Không thể tải nội dung chương.</p>');
                    $('#chapterModal').modal('show');
                }
            });
        }





        // Hàm lưu chương vào localStorage
        function saveChapterToLocalStorage(filename, chapterName, chapterApiData, chapterTitle, storyName, storyLink, storyImage) {
            let readHistory = JSON.parse(localStorage.getItem('readHistory')) || [];

            // Kiểm tra xem chương này đã có trong lịch sử chưa
            let chapterExists = readHistory.some(chapter => chapter.filename === filename);
            if (!chapterExists) {
                readHistory.push({
                    filename: filename,
                    chapter_name: chapterName,
                    chapter_api_data: chapterApiData,
                    chapter_title: chapterTitle,
                    chapter_story_name: storyName,
                    chapter_link: storyLink,
                    chapter_image: storyImage
                });
                localStorage.setItem('readHistory', JSON.stringify(readHistory));
            }
        }

        // Lấy thông tin chương khi người dùng bấm vào một chương
        document.querySelectorAll('.chapter-item').forEach(function(chapterItem) {
            chapterItem.addEventListener('click', function() {
                let chapterUrl = chapterItem.getAttribute('data-chapter-url');
                let chapterName = chapterItem.querySelector('span').textContent;
                let filename = chapterName.replace('Chapter: ', '').trim();
                let chapterTitle = 'Tiêu đề của chương ' + filename; 
                let storyName = "<?php echo htmlspecialchars($comicData['name']); ?>"; // Tên truyện
                let storySlug = "<?php echo htmlspecialchars($comicData['slug']); ?>"; // Lấy slug từ dữ liệu
                let storyLink = "truyen-detail.php?slug=" + storySlug; 
                let storyImage = "https://img.otruyenapi.com/uploads/comics/<?php echo htmlspecialchars($comicData['thumb_url']); ?>"; // Hình ảnh truyện
                saveChapterToLocalStorage(filename, chapterName, chapterUrl, chapterTitle, storyName, storyLink, storyImage);
                showChapterInModal(chapterUrl);
            });
        });

        // Hiển thị nội dung chương trong modal
        function showChapterInModal(chapterUrl) {
            fetch(chapterUrl)
                .then(response => response.json())
                .then(data => {
                    let chapterContent = data.content || "Không có nội dung.";
                    document.getElementById('chapterContent').innerHTML = chapterContent;
                    let chapterModal = new bootstrap.Modal(document.getElementById('chapterModal'));
                    chapterModal.show();
                    document.getElementById('chapterModal').addEventListener('hidden.bs.modal', function () {
                        let modalBackdrop = document.querySelector('.modal-backdrop');
                        if (modalBackdrop) {
                            modalBackdrop.remove(); // Xóa lớp phủ khi modal đóng
                        }
                    });
                    setTimeout(() => {
                        chapterModal.hide(); // Đóng modal sau 5 giây
                    }, 5000); // 5000ms = 5 giây
                })
                .catch(error => {
                    console.error('Error fetching chapter:', error);
                });
        }
    </script>
</body>
</html>