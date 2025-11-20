<?php if (!$tour): ?>
    <div class="card p-4">
        <div class="text-muted">Not found</div>
    </div>
<?php else: ?>
    <div class="card p-4" style="max-width:900px;">
        <h4 class="mb-4">Chỉnh sửa Tour</h4>

        <form method="post" action="<?= BASE_URL ?>?act=admin-tours-update" class="row g-3">
            <input type="hidden" name="id" value="<?= htmlspecialchars($tour['tour_id']) ?>">

            <div class="col-12 col-md-8">
                <label class="form-label">Tên tour</label>
                <input name="tour_name" value="<?= htmlspecialchars($tour['tour_name']) ?>" required class="form-control">
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label">Giá</label>
                <input name="price" type="number" value="<?= htmlspecialchars($tour['price']) ?>" required class="form-control">
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label">Loại tour</label>
                <select name="tour_type" class="form-select">
                    <option <?= ($tour['tour_type'] ?? '') === 'Trong nước' ? 'selected' : '' ?>>Trong nước</option>
                    <option <?= ($tour['tour_type'] ?? '') === 'Quốc tế' ? 'selected' : '' ?>>Quốc tế</option>
                    <option <?= ($tour['tour_type'] ?? '') === 'Theo yêu cầu' ? 'selected' : '' ?>>Theo yêu cầu</option>
                </select>
            </div>

            <div class="col-12 col-md-6">
                <label class="form-label">Số ngày</label>
                <input name="duration_days" type="number" min="1" value="<?= (int)($tour['duration_days'] ?? 1) ?>" class="form-control">
            </div>

            <div class="col-12">
                <label class="form-label">Mô tả</label>
                <textarea name="description" rows="4" class="form-control"><?= htmlspecialchars($tour['description'] ?? '') ?></textarea>
            </div>

            <div class="col-12">
                <label class="form-label">Chính sách</label>
                <textarea name="policy" rows="3" class="form-control"><?= htmlspecialchars($tour['policy'] ?? '') ?></textarea>
            </div>

            <div class="col-12 col-md-4">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="Hoạt động" <?= ($tour['status'] ?? '') === 'Hoạt động' ? 'selected' : '' ?>>Hoạt động</option>
                    <option value="Tạm dừng" <?= ($tour['status'] ?? '') === 'Tạm dừng' ? 'selected' : '' ?>>Tạm dừng</option>
                    <option value="Ngừng bán" <?= ($tour['status'] ?? '') === 'Ngừng bán' ? 'selected' : '' ?>>Ngừng bán</option>
                </select>
            </div>

            <div class="col-12 d-flex gap-2">
                <button type="submit" class="btn btn-primary">Cập nhật</button>
                <a href="<?= $base ?>/admin/tours" class="btn btn-outline-secondary">Hủy</a>
            </div>
        </form>
    </div>
<?php endif; ?>