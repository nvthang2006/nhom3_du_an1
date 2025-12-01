<?php
// app/Views/admin/tours/create.php
?>
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Tạo Tour Mới</h1>
            <p class="text-muted mb-0 small">Điền thông tin để thêm tour du lịch mới vào hệ thống</p>
        </div>
        <a href="<?= BASE_URL ?>?act=admin-tours" class="btn btn-outline-secondary shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
        </a>
    </div>

    <form method="post" action="<?= BASE_URL ?>?act=admin-tours-store" class="needs-validation" enctype="multipart/form-data">
        <input type="hidden" name="created_by" value="<?= $_SESSION['user_id'] ?? 1 ?>">

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-info-circle me-2"></i>Thông tin cơ bản</h6>
                    </div>

                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tên Tour <span class="text-danger">*</span></label>
                            <input type="text" name="tour_name" required class="form-control form-control-lg" placeholder="Ví dụ: Tour Hà Nội - Sapa 3 ngày 2 đêm">
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Mã/Loại Tour</label>
                                <select name="tour_type" class="form-select">
                                    <option value="Trong nước">Trong nước</option>
                                    <option value="Quốc tế">Quốc tế</option>
                                    <option value="Theo yêu cầu">Theo yêu cầu</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Thời lượng (Ngày) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="bi bi-clock"></i></span>
                                    <input type="number" name="duration_days" min="1" value="1" class="form-control">
                                </div>
                            </div>

                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mô tả chi tiết</label>
                            <textarea name="description" class="form-control" rows="5" placeholder="Nhập mô tả hấp dẫn về tour..."></textarea>
                            <div class="form-text">Mô tả ngắn gọn các điểm nổi bật của tour.</div>
                        </div>
                    </div>  
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ảnh đại diện (Thumbnail)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Thư viện ảnh (Gallery)</label>
                        <input type="file" name="gallery[]" class="form-control" multiple accept="image/*">
                        <div class="form-text">Giữ Ctrl để chọn nhiều ảnh.</div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-file-earmark-text me-2"></i>Chính sách & Điều khoản</h6>
                    </div>
                    <div class="card-body">
                        <textarea name="policy" class="form-control" rows="4" placeholder="Nhập chính sách hủy tour, bao gồm, không bao gồm..."></textarea>
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
                        </div>
                        <div class="text-center mt-3 text-muted small" id="empty-msg">
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
                            <input type="number" name="price" required class="form-control form-control-lg fw-bold text-primary" placeholder="0">
                            <span class="input-group-text">₫</span>
                        </div>
                        <div class="alert alert-light border small text-muted">
                            <i class="bi bi-lightbulb me-1"></i> Giá này sẽ được hiển thị công khai trên trang chủ.
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
                            <option value="Hoạt động" selected>🟢 Đang hoạt động</option>
                            <option value="Ngừng">🔴 Ngừng hoạt động</option>
                        </select>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-bold">Số khách tối đa</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-people"></i></span>
                                <input type="number" name="max_people" min="1" value="20" class="form-control">
                            </div>
                        </div>
                        <hr>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-save me-2"></i>Lưu Tour Mới
                            </button>
                            <button type="reset" class="btn btn-light">Làm lại</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

</div>

<script>
    let scheduleCount = 0;

    function addScheduleRow() {
        scheduleCount++;
        // Ẩn thông báo trống
        const emptyMsg = document.getElementById('empty-msg');
        if (emptyMsg) emptyMsg.style.display = 'none';

        const container = document.getElementById('schedule-container');
        const html = `
            <div class="card mb-3 border shadow-none schedule-item" id="schedule-row-${scheduleCount}">
                <div class="card-body p-3 bg-light rounded">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-bold text-primary">Ngày ${scheduleCount}</span>
                        <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeScheduleRow(this)">
                            <i class="bi bi-x-lg"></i> Xóa
                        </button>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label small fw-bold">Ngày thứ</label>
                            <input type="number" name="schedules[${scheduleCount}][day_number]" class="form-control" value="${scheduleCount}" required>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Địa điểm</label>
                            <input type="text" name="schedules[${scheduleCount}][location]" class="form-control" placeholder="VD: Vịnh Hạ Long" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Hình ảnh (Tùy chọn)</label>
                            <input type="file" name="schedules_image_${scheduleCount}" class="form-control" accept="image/*">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-bold">Mô tả hoạt động</label>
                            <textarea name="schedules[${scheduleCount}][description]" class="form-control" rows="2" placeholder="Mô tả chi tiết hoạt động trong ngày..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
    }

    function removeScheduleRow(btn) {
        btn.closest('.schedule-item').remove();
        // Kiểm tra nếu không còn dòng nào thì hiện lại thông báo
        const container = document.getElementById('schedule-container');
        if (container.children.length === 0) {
            document.getElementById('empty-msg').style.display = 'block';
            scheduleCount = 0; // Reset đếm nếu muốn hoặc giữ nguyên để tránh trùng ID
        }
    }
</script>