<?php
include 'config.php';

// Lấy danh sách môn học để filter
$subjects = $conn->query("SELECT * FROM subjects");

// Mặc định chọn môn đầu tiên nếu chưa chọn
$selected_subject_id = isset($_GET['subject_id']) ? $_GET['subject_id'] : ($subjects->num_rows > 0 ? $subjects->fetch_assoc()['id'] : 0);
// Reset pointer môn học để dùng lại ở dưới dropdown
$subjects->data_seek(0); 

// Xử lý Cập nhật điểm
if (isset($_POST['update_grades'])) {
    $sub_id = $_POST['subject_id'];
    foreach ($_POST['grades'] as $stu_id => $g) {
        $att = $g['attendance'];
        $mid = $g['midterm'];
        $fin = $g['final'];
        
        // Kỹ thuật "UPSERT": Nếu chưa có thì Insert, có rồi thì Update (Đáp ứng yêu cầu Đăng ký môn học ngầm định)
        // Đây là cách xử lý thông minh để không cần làm chức năng Đăng ký riêng biệt
        $sql = "INSERT INTO grades (student_id, subject_id, attendance, midterm, final) 
                VALUES ($stu_id, $sub_id, $att, $mid, $fin)
                ON DUPLICATE KEY UPDATE attendance=$att, midterm=$mid, final=$fin";
        $conn->query($sql);
    }
    $message = "Đã cập nhật bảng điểm thành công!";
}

// Lấy danh sách sinh viên KÈM điểm của môn đã chọn (LEFT JOIN)
// Logic: Hiển thị TOÀN BỘ sinh viên, ai chưa học môn này thì điểm bằng 0 (hoặc trống)
if ($selected_subject_id) {
    $sql_students = "SELECT s.id, s.fullname, g.attendance, g.midterm, g.final 
                     FROM students s 
                     LEFT JOIN grades g ON s.id = g.student_id AND g.subject_id = $selected_subject_id
                     ORDER BY s.fullname ASC";
    $list_students = $conn->query($sql_students);
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Điểm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .score-input { width: 70px; text-align: center; border: 1px solid #dee2e6; border-radius: 4px; }
        .score-input:focus { border-color: #86b7fe; outline: 0; background: #f0f8ff; }
        .final-score { font-weight: bold; color: #d63384; }
    </style>
</head>
<body class="bg-light">
    <?php include 'navbar.php'; ?>

    <div class="container">
        <?php if(isset($message)) echo "<div class='alert alert-success'>$message</div>"; ?>

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <h4 class="mb-0 fw-bold text-primary">Sổ Điểm Điện Tử</h4>
                
                <!-- Bộ lọc môn học -->
                <form method="GET" class="d-flex align-items-center">
                    <label class="me-2 fw-bold">Chọn Môn:</label>
                    <select name="subject_id" class="form-select me-2" onchange="this.form.submit()">
                        <?php while($sub = $subjects->fetch_assoc()) { ?>
                            <option value="<?php echo $sub['id']; ?>" <?php if($sub['id'] == $selected_subject_id) echo 'selected'; ?>>
                                <?php echo $sub['name'] . " (" . $sub['subject_code'] . ")"; ?>
                            </option>
                        <?php } ?>
                    </select>
                </form>
            </div>
        </div>

        <?php if ($selected_subject_id && $list_students->num_rows > 0) { ?>
        <form method="POST">
            <input type="hidden" name="subject_id" value="<?php echo $selected_subject_id; ?>">
            <div class="card shadow-sm border-0">
                <table class="table table-bordered table-hover mb-0 align-middle">
                    <thead class="bg-primary text-white text-center">
                        <tr>
                            <th width="5%">#ID</th>
                            <th width="25%" class="text-start">Sinh Viên</th>
                            <th width="15%">Chuyên cần (10%)</th>
                            <th width="15%">Giữa kỳ (30%)</th>
                            <th width="15%">Cuối kỳ (60%)</th>
                            <th width="15%">Tổng Kết</th>
                            <th width="10%">Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($st = $list_students->fetch_assoc()) { 
                            // Xử lý giá trị null = 0 để hiển thị
                            $cc = $st['attendance'] ?? 0;
                            $gk = $st['midterm'] ?? 0;
                            $ck = $st['final'] ?? 0;
                            // Tính điểm tổng kết (Logic Item 3)
                            $total = ($cc * 0.1) + ($gk * 0.3) + ($ck * 0.6);
                            $total = round($total, 2);
                            $pass = $total >= 4.0; // Giả sử 4.0 là qua môn
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $st['id']; ?></td>
                            <td class="fw-bold"><?php echo $st['fullname']; ?></td>
                            <td class="text-center">
                                <input type="number" step="0.1" min="0" max="10" 
                                       name="grades[<?php echo $st['id']; ?>][attendance]" 
                                       value="<?php echo $cc; ?>" class="score-input">
                            </td>
                            <td class="text-center">
                                <input type="number" step="0.1" min="0" max="10" 
                                       name="grades[<?php echo $st['id']; ?>][midterm]" 
                                       value="<?php echo $gk; ?>" class="score-input">
                            </td>
                            <td class="text-center">
                                <input type="number" step="0.1" min="0" max="10" 
                                       name="grades[<?php echo $st['id']; ?>][final]" 
                                       value="<?php echo $ck; ?>" class="score-input">
                            </td>
                            <td class="text-center final-score fs-5">
                                <?php echo ($st['attendance'] !== null) ? $total : '-'; ?>
                            </td>
                            <td class="text-center">
                                <?php if($st['attendance'] !== null) { ?>
                                    <span class="badge <?php echo $pass ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo $pass ? 'Đạt' : 'Học lại'; ?>
                                    </span>
                                <?php } else { ?>
                                    <span class="badge bg-secondary">Chưa học</span>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <div class="card-footer text-end p-3">
                    <button type="submit" name="update_grades" class="btn btn-success fw-bold px-4">
                        <i class="fa-solid fa-save me-2"></i> Cập Nhật Bảng Điểm
                    </button>
                </div>
            </div>
        </form>
        <?php } else { echo "<div class='alert alert-warning'>Chưa có sinh viên hoặc chưa chọn môn học.</div>"; } ?>
    </div>
</body>
</html>