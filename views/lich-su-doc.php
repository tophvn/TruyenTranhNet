<?php
session_start();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../img/logo.png" rel="icon">
    <title>Lịch Sử Đọc</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        #historyContainer {
            max-height: 500px; 
            overflow-y: auto;  
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <main class="container mt-5">
        <h1>Lịch Sử Đọc</h1>
        <div id="historyContainer">
        </div>
        <br>
    </main>
    <?php include '../includes/footer.php'; ?>

    <script>
        // Lấy lịch sử đọc từ localStorage
        let readHistory = JSON.parse(localStorage.getItem('readHistory')) || [];
        // Hàm xóa chương khỏi lịch sử
        function removeChapterFromHistory(filename) {
            readHistory = readHistory.filter(chapter => chapter.filename !== filename);
            localStorage.setItem('readHistory', JSON.stringify(readHistory));
            renderHistory();  
        }
        function renderHistory() {
            if (readHistory.length > 0) {
                // Sắp xếp lịch sử theo thứ tự truyện mới nhất
                readHistory.sort((a, b) => b.filename.localeCompare(a.filename));  
                let historyHtml = `
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Chapter</th>
                                <th scope="col">Tiêu Đề</th>
                                <th scope="col">Hình Ảnh</th>
                                <th scope="col">Tên Truyện</th>
                                <th scope="col">Liên Kết Truyện</th>
                                <th scope="col">Hành Động</th>
                            </tr>
                        </thead>
                        <tbody>
                `;
                readHistory.forEach(chapter => {
                    historyHtml += `
                        <tr>
                            <td>${chapter.chapter_name}</td>
                            <td>${chapter.chapter_title}</td>
                            <td><img src="${chapter.chapter_image}" alt="Hình ảnh truyện" width="50"></td>
                            <td>${chapter.chapter_story_name}</td>
                            <td><a href="${chapter.chapter_link}" target="_blank" class="btn btn-primary">Xem Truyện</a></td>
                            <td>
                                <button class="btn btn-danger" onclick="removeChapterFromHistory('${chapter.filename}')">Xóa</button>
                            </td>
                        </tr>
                    `;
                });
                historyHtml += `</tbody></table>`;
                document.getElementById('historyContainer').innerHTML = historyHtml;
            } else {
                document.getElementById('historyContainer').innerHTML = `
                    <div class="alert alert-warning" role="alert">
                        Bạn chưa đọc chương truyện nào.
                    </div>
                `;
            }
        }
        // Gọi hàm render
        renderHistory();
    </script>
</body>
</html>
