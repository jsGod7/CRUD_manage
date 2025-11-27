<?php
include 'config.php';
$id = $_GET['id'];
$sql = "SELECT * FROM students WHERE id=$id";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

if (isset($_POST['update'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $major = $_POST['major'];

    $sql = "UPDATE students SET fullname='$fullname', email='$email', phone='$phone', major='$major' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
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
        <h2>Sửa Sinh Viên</h2>
        <form method="POST">
            <div class="mb-3"><label>Họ tên</label><input type="text" name="fullname" value="<?php echo $row['fullname']; ?>" class="form-control"></div>
            <div class="mb-3"><label>Email</label><input type="email" name="email" value="<?php echo $row['email']; ?>" class="form-control"></div>
            <div class="mb-3"><label>SĐT</label><input type="text" name="phone" value="<?php echo $row['phone']; ?>" class="form-control"></div>
            <div class="mb-3"><label>Ngành</label><input type="text" name="major" value="<?php echo $row['major']; ?>" class="form-control"></div>
            <button type="submit" name="update" class="btn btn-warning">Cập nhật</button>
            <a href="index.php" class="btn btn-secondary">Hủy</a>
        </form>
    </div>
</body>
</html>