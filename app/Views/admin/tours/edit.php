<?php
// app/Views/admin/tours/edit.php
// Biến $schedules được truyền từ Controller
?>
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Chỉnh sửa Tour</h1>
            <p class="text-muted mb-0 small">Cập nhật thông tin tour ID: #<?= $tour['tour_id'] ?></p>
        </div>
        <a href="<?= BASE_URL ?>?act=admin-tours" class="btn btn-outline-secondary shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
        </a>
    </div>

    <form method="post" action="<?= BASE_URL ?>?act=admin-tours-update" class="needs-validation" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $tour['tour_id'] ?>">

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-info-circle me-2"></i>Thông tin cơ bản</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên Tour <span class="text-danger">*</span></label>
                            <input type="text" name="tour_name" value="<?= htmlspecialchars($tour['tour_name']) ?>" required class="form-control form-control-lg">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Mã/Loại Tour</label>
                                <select name="tour_type" class="form-select">
                                    <?php
                                    $types = ['Trong nước', 'Quốc tế', 'Theo yêu cầu'];
                                    foreach ($types as $type) {
                                        $selected = ($tour['tour_type'] === $type) ? 'selected' : '';
                                        echo '<option value="' . $type . '" ' . $selected . '>' . $type . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Thời lượng (Ngày) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-clock"></i></span>
                                    <input type="number" name="duration_days" min="1" value="<?= $tour['duration_days'] ?>" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mô tả chi tiết</label>
                            <textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($tour['description']) ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-file-earmark-text me-2"></i>Chính sách & Điều khoản</h6>
                    </div>
                    <div class="card-body">
                        <textarea name="policy" class="form-control" rows="4"><?= htmlspecialchars($tour['policy']) ?></textarea>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-list-check me-2"></i>Lịch trình Tour (Tour Schedule)</h6>
                        <button type="button" class="btn btn-sm btn-success" onclick="addScheduleRow()">
                            <i class="bi bi-plus-circle"></i> Thêm ngày
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="schedule-container">
                            <?php if (!empty($schedules)): ?>
                                <?php foreach ($schedules as $index => $sch): ?>
                                    <div class="card mb-3 border shadow-none schedule-item" id="schedule-row-existing-<?= $index ?>">
                                        <div class="card-body p-3 bg-light rounded">
                                            <input type="hidden" name="schedules[<?= $index ?>][id]" value="<?= $sch['schedule_id'] ?>">
                                            <input type="hidden" name="schedules[<?= $index ?>][old_image]" value="<?= $sch['image'] ?>">

                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="fw-bold text-primary">Ngày <?= $sch['day_number'] ?> (Đang có)</span>
                                                <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeScheduleRow(this)">
                                                    <i class="bi bi-x-lg"></i> Xóa
                                                </button>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-md-2">
                                                    <label class="form-label small fw-bold">Ngày thứ</label>
                                                    <input type="number" name="schedules[<?= $index ?>][day_number]" class="form-control" value="<?= $sch['day_number'] ?>" required>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label small fw-bold">Địa điểm</label>
                                                    <input type="text" name="schedules[<?= $index ?>][location]" class="form-control" value="<?= htmlspecialchars($sch['location']) ?>" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-bold">Hình ảnh (Chọn để thay thế)</label>
                                                    <?php if (!empty($sch['image'])): ?>
                                                        <div class="mb-2">
                                                            <img src="<?= BASE_URL . $sch['image'] ?>" height="50" class="rounded">
                                                            <span class="small text-muted fst-italic ms-2">Ảnh hiện tại</span>
                                                        </div>
                                                    <?php endif; ?>
                                                    <input type="file" name="schedules_image_<?= $index ?>" class="form-control" accept="image/*">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label small fw-bold">Mô tả hoạt động</label>
                                                    <textarea name="schedules[<?= $index ?>][description]" class="form-control" rows="2"><?= htmlspecialchars($sch['description']) ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <div class="text-center mt-3 text-muted small" id="empty-msg" style="<?= !empty($schedules) ? 'display:none' : '' ?>">
                            Chưa có lịch trình nào. Bấm "Thêm ngày" để bắt đầu.
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-tag me-2"></i>Định giá</h6>
                    </div>
                    <div class="card-body">
                        <label class="form-label fw-bold">Giá niêm yết (VNĐ) <span class="text-danger">*</span></label>
                        <div class="input-group mb-3">
                            <input type="number" name="price" value="<?= $tour['price'] ?>" required class="form-control form-control-lg fw-bold text-primary">
                            <span class="input-group-text">₫</span>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-gear me-2"></i>Thiết lập</h6>
                    </div>
                    <div class="card-body">
                        <label class="form-label fw-bold">Trạng thái hiển thị</label>
                        <select name="status" class="form-select mb-3">
                            <option value="Hoạt động" <?= ($tour['status'] === 'Hoạt động') ? 'selected' : '' ?>>🟢 Đang hoạt động</option>
                            <option value="Ngừng" <?= ($tour['status'] === 'Ngừng') ? 'selected' : '' ?>>🔴 Ngừng hoạt động</option>
                        </select>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Số khách tối đa</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-people"></i></span>
                                <input type="number" name="max_people" min="1" value="<?= $tour['max_people'] ?? 20 ?>" class="form-control">
                            </div>
                        </div>
                        <hr>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning btn-lg">
                                <i class="bi bi-save me-2"></i>Cập nhật Tour
                            </button>
                            <a href="<?= BASE_URL ?>?act=admin-tours" class="btn btn-light">Hủy bỏ</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Khởi tạo biến đếm dựa trên số lượng lịch trình ĐANG CÓ
    let scheduleCount = <?= !empty($schedules) ? count($schedules) : 0 ?>;

    function addScheduleRow() {
        const currentIndex = scheduleCount++;

        document.getElementById('empty-msg').style.display = 'none';
        const container = document.getElementById('schedule-container');

        const html = `
            <div class="card mb-3 border shadow-none schedule-item border-success" id="schedule-row-new-${currentIndex}">
                <div class="card-body p-3 bg-light rounded">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-bold text-success">Lịch trình Mới (Sắp thêm)</span>
                        <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeScheduleRow(this)">
                            <i class="bi bi-x-lg"></i> Xóa
                        </button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label small fw-bold">Ngày thứ</label>
                            <input type="number" name="schedules[${currentIndex}][day_number]" class="form-control" value="${currentIndex + 1}" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Địa điểm</label>
                            <input type="text" name="schedules[${currentIndex}][location]" class="form-control" placeholder="VD: Vịnh Hạ Long" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Hình ảnh (Tùy chọn)</label>
                            <input type="file" name="schedules_image_${currentIndex}" class="form-control" accept="image/*">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold">Mô tả hoạt động</label>
                            <textarea name="schedules[${currentIndex}][description]" class="form-control" rows="2" placeholder="Mô tả chi tiết hoạt động..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }

    function removeScheduleRow(btn) {
        btn.closest('.schedule-item').remove();
        const container = document.getElementById('schedule-container');
        if (container.children.length === 0) {
            document.getElementById('empty-msg').style.display = 'block';
        }
    }
</script>