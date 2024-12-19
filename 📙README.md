Các phần cần chỉnh để hosting
1) config/database.php
- sửa lại thông tin database của các bạn
2) views/tai-khoan.php
- Dòng 42: $apiKey = ''; điền chuỗi api key tương ứng của Imgbb (https://api.imgbb.com/)
3) views/login.php
- Sửa recaptCha (nếu muốn dung của bạn)
Dùng reCAPTCHA v2: 
 + Dòng 23: $recaptchaSecretKey = ''; : Thay bằng secretkey của bạn.
 + Dòng 152: data-sitekey ="" : Thay bằng sitekey của bạn

- Sửa đăng nhập bằng google OAuth 2.0 (https://console.cloud.google.com/)
 + Dòng 8: $clientID = '';     //điền Client ID vào đây
 + Dòng 9: $clientSecret = '';     //điền Client secret vào đây

4) config/send_email.php 
- Sửa lại email gửi và mật khẩu ứng dung để gửi email đến người dung (OTP khi đăng ký, Khi quên mật khẩu)
+ $mail->Username: Email gửi
+ $mail->Password: Mật khẩu ứng dung (Bật xác thực 2 bước cho tài khoản rồi tìm đến mật khẩu ứng dung, tạo mật khẩu mới)
+ $mail->setFrom: Sửa lại tên email khi gửi đến người dùng 

Sửa cả 3 thông tin đó ở 2 hàm send_otp_email và send_password_reset_email
