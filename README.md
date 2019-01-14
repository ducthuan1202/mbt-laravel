# MBT - phase 1
- chỉnh sửa bảng user, cần update lại database hoặc migrate lại
- link php-excel: https://phpspreadsheet.readthedocs.io/en/develop/

## Các lệnh thao tác trên SERVER

- Tạo mới 1 file: `touch demo.txt`;
- Mở 1 file: `nano demo.txt`
- Luu và thoát: `Ctrl + O` => `Enter` => `Ctrl + X`

- Xem danh sách thư mục, tập tin: `ls` 
- Xem danh sách thư mục, tập tin theo quyền: `ls -l`

- Tạo thư mục: `mkdir picture`
- Xóa thư mục trống: `rmdir picture`
- Xóa file: `rm demo.txt`
- Xóa thư mục có chứa dữ liệu: `rm -rf picture` (cần chắc chắn để vì có thể mất hết dữ liệu)

- set quyền cho thư mục: `chmode 777 -R storage` (storage là tên thư mục)

## Cron job
- Hiện tại, dự án đang chạy 1 lệnh cronjob ở file crontab (với quyền root) để cập nhật trạng thái cho lịch trình thanh toán
