    <div class="row g-4">

        <!-- LEFT: form -->
        <div class="col-12 col-lg-7">
            <div class="card p-4">
                <h4 class="mb-4">Tạo Tour mới</h4>

                <form method="post" action="<?= BASE_URL ?>?act=admin-tours-store">

                    <div class="mb-3">
                        <label class="form-label">Tên tour</label>
                        <input name="tour_name" required class="form-control" placeholder="Nhập tên tour">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Loại tour</label>
                        <select name="tour_type" class="form-select">
                            <option>Trong nước</option>
                            <option>Quốc tế</option>
                            <option>Theo yêu cầu</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Nhập mô tả tour"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Giá</label>
                        <input name="price" type="number" required class="form-control" placeholder="Nhập giá">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Số ngày</label>
                        <input name="duration_days" type="number" value="1" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Chính sách</label>
                        <textarea name="policy" class="form-control" rows="4" placeholder="Thêm chính sách tour..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option>Hoạt động</option>
                            <option>Tạm dừng</option>
                            <option>Ngừng bán</option>
                        </select>
                    </div>

                    <!-- created_by -->
                    <input type="hidden" name="created_by" value="<?= $_SESSION['user_id'] ?? 1 ?>">

                    <button class="btn btn-primary px-4">Tạo Tour</button>
                </form>

            </div>
        </div>

        <!-- RIGHT: existing tours -->
        <div class="col-12 col-lg-5">
            <div class="card p-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h5 class="mb-0">Danh sách Tour</h5>
                    <small class="small-muted"><?= isset($tours) ? count($tours) : 0 ?> items</small>
                </div>

                <?php if (empty($tours)): ?>
                    <div class="text-muted">Chưa có tour nào. Tạo tour mới để hiển thị ở đây.</div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($tours as $t): ?>
                            <div class="list-group-item d-flex align-items-start gap-3">
                                <!-- thumbnail / icon -->
                                <div style="width:64px;height:48px;border-radius:8px;background:#f1f5f9;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-weight:600;color:#0f172a;">
                                    <?= htmlspecialchars(substr($t['tour_name'] ?? 'T', 0, 1)) ?>
                                </div>

                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <div class="fw-semibold"><?= htmlspecialchars($t['tour_name'] ?? '—') ?></div>
                                            <div class="small-muted"><?= htmlspecialchars($t['tour_type'] ?? '—') ?> · <?= (int)($t['duration_days'] ?? 1) ?> ngày</div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-semibold"><?= isset($t['price']) ? number_format($t['price'], 0, ',', '.') . '₫' : '—' ?></div>
                                            <div class="small-muted"><?= htmlspecialchars($t['status'] ?? '') ?></div>
                                        </div>
                                    </div>

                                    <div class="mt-2 d-flex gap-2">
                                        <a href="<?= $base ?>/admin/tours/edit?id=<?= urlencode($t['id'] ?? '') ?>" class="btn btn-sm btn-outline-primary">Sửa</a>
                                        <form method="post" action="<?= $base ?>/admin/tours/delete" onsubmit="return confirm('Xác nhận xoá tour này?')" style="display:inline">
                                            <input type="hidden" name="id" value="<?= htmlspecialchars($t['id'] ?? '') ?>">
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Xoá</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- optional: link to full list -->
                <div class="mt-3 text-end">
                    <a href="<?= BASE_URL ?>?act=admin-tours" class="btn btn-sm btn-link">Xem tất cả</a>
                </div>
            </div>
        </div>

    </div>