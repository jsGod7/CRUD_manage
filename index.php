<?php
include 'config.php';

// --- XỬ LÝ TÌM KIẾM ---
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $sql = "SELECT * FROM students WHERE fullname LIKE '%$search%' OR email LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM students";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Sinh Viên</title>
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f0f2f5; /* Màu nền xám nhẹ dịu mắt */
            font-family: 'Poppins', sans-serif;
        }
        .main-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05); /* Đổ bóng nhẹ */
            overflow: hidden;
        }
        .card-header-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); /* Màu Gradient tím xanh */
            color: white;
            padding: 20px;
        }
        .table thead {
            background-color: #f8f9fa;
            color: #555;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
        }
        .table th, .table td {
            vertical-align: middle; /* Căn giữa nội dung theo chiều dọc */
            padding: 15px;
        }
        .avatar-initial {
            width: 40px;
            height: 40px;
            background-color: #e9ecef;
            color: #495057;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 10px;
        }
        .btn-action {
            border-radius: 20px;
            padding: 5px 15px;
            font-size: 0.85rem;
            transition: all 0.3s;
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .search-box {
            border-radius: 50px;
            padding-left: 20px;
            border: 1px solid #ddd;
        }
        .search-btn {
            border-radius: 50px;
            padding: 8px 25px;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container py-4">
        <div class="card main-card">
            
            <!-- Header Giao Diện -->
            <div class="card-header-custom d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h3 class="mb-0 fw-bold"><i class="fa-solid fa-user-graduate me-2"></i> Quản Lý Sinh Viên</h3>
                    <small class="opacity-75">Hệ thống quản lý hồ sơ sinh viên</small>
                </div>
                <a href="create.php" class="btn btn-light text-primary fw-bold shadow-sm" style="border-radius: 30px;">
                    <i class="fa-solid fa-plus-circle me-1"></i> Thêm Mới
                </a>
            </div>

            <div class="card-body p-4">
                
                <!-- Thanh Tìm Kiếm -->
                <form action="" method="GET" class="row g-3 mb-4 justify-content-end">
                    <div class="col-md-5 position-relative">
                        <input type="text" name="search" class="form-control search-box" 
                               placeholder="Tìm kiếm theo tên hoặc email..." 
                               value="<?php echo $search; ?>">
                        <button type="submit" class="btn btn-primary search-btn position-absolute top-0 end-0 h-100">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                    <?php if(!empty($search)) { ?>
                        <div class="col-auto">
                            <a href="index.php" class="btn btn-outline-secondary" style="border-radius: 50px;">
                                <i class="fa-solid fa-rotate-left"></i> Đặt lại
                            </a>
                        </div>
                    <?php } ?>
                </form>

                <!-- Bảng Dữ Liệu -->
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center" width="5%">ID</th>
                                <th width="25%">Sinh Viên</th>
                                <th width="25%">Liên Hệ</th>
                                <th width="20%">Ngành Học</th>
                                <th class="text-center" width="20%">Tác Vụ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    // Lấy chữ cái đầu của tên để làm Avatar
                                    $initial = strtoupper(substr($row['fullname'], 0, 1));
                                    ?>
                                    <tr>
                                        <td class="text-center text-muted">#<?php echo $row['id']; ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-initial bg-primary text-white bg-opacity-75">
                                                    <?php echo $initial; ?>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark"><?php echo $row['fullname']; ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="small text-muted"><i class="fa-solid fa-envelope me-1"></i> <?php echo $row['email']; ?></div>
                                            <div class="small text-muted"><i class="fa-solid fa-phone me-1"></i> <?php echo $row['phone']; ?></div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info bg-opacity-10 text-info border border-info rounded-pill px-3">
                                                <?php echo $row['major']; ?>
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-warning btn-action btn-sm me-1" title="Sửa">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </a>
                                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-danger btn-action btn-sm" 
                                               onclick="return confirm('Bạn có chắc chắn muốn xóa sinh viên này không?');" title="Xóa">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center py-5 text-muted'>
                                        <i class='fa-solid fa-box-open fa-3x mb-3 opacity-50'></i><br>
                                        Không tìm thấy dữ liệu nào phù hợp!
                                      </td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
                
            </div>
        </div>
    </div>

</body>
</html>