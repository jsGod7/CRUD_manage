<?php
include 'config.php';

// Xử lý Thêm Môn Học
if (isset($_POST['add_subject'])) {
    $code = $_POST['code'];
    $name = $_POST['name'];
    $credits = $_POST['credits'];
    $conn->query("INSERT INTO subjects (subject_code, name, credits) VALUES ('$code', '$name', '$credits')");
    header("Location: subjects.php");
}

// Xử lý Xóa
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM subjects WHERE id=$id");
    header("Location: subjects.php");
}

$result = $conn->query("SELECT * FROM subjects");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Môn Học</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>
    
    <div class="container">
        <div class="row">
            <!-- Form Thêm Môn -->
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white"><i class="fa-solid fa-book"></i> Thêm Môn Học</div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="mb-3"><label>Mã Môn</label><input type="text" name="code" class="form-control" required placeholder="VD: CS101"></div>
                            <div class="mb-3"><label>Tên Môn</label><input type="text" name="name" class="form-control" required></div>
                            <div class="mb-3"><label>Số Tín Chỉ</label><input type="number" name="credits" class="form-control" value="3" min="1" max="10"></div>
                            <button type="submit" name="add_subject" class="btn btn-primary w-100">Lưu Môn Học</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Danh Sách Môn -->
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white fw-bold">Danh Sách Môn Học</div>
                    <div class="card-body p-0">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Mã Môn</th>
                                    <th>Tên Môn</th>
                                    <th>Tín Chỉ</th>
                                    <th class="text-end">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><span class="badge bg-secondary"><?php echo $row['subject_code']; ?></span></td>
                                    <td><?php echo $row['name']; ?></td>
                                    <td><?php echo $row['credits']; ?></td>
                                    <td class="text-end">
                                        <a href="subjects.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa môn này sẽ xóa hết điểm liên quan?');"><i class="fa-solid fa-trash"></i></a>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>