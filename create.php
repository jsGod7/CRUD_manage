<?php
include 'config.php';

if (isset($_POST['submit'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $major = $_POST['major'];

    $sql = "INSERT INTO students (fullname, email, phone, major) VALUES ('$fullname', '$email', '$phone', '$major')";

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Lỗi: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Thêm Sinh Viên</h2>
        <form method="POST">
            <div class="mb-3"><label>Họ tên</label><input type="text" name="fullname" class="form-control" required></div>
            <div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
            <div class="mb-3"><label>SĐT</label><input type="text" name="phone" class="form-control" required></div>
            <div class="mb-3"><label>Ngành</label><input type="text" name="major" class="form-control" required></div>
            <button type="submit" name="submit" class="btn btn-success">Lưu lại</button>
            <a href="index.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</body>
</html>