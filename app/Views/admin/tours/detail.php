<div class="container py-4">

    <?php if (empty($tour)): ?>
        <div class="alert alert-danger">Không tìm thấy tour.</div>
        <a href="<?= BASE_URL ?>?act=admin-tours" class="btn btn-secondary">← Quay lại</a>
    <?php else: ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0 text-gray-800">Chi tiết Tour: <?= htmlspecialchars($tour['tour_name']) ?></h3>
            <a href="<?= BASE_URL ?>?act=admin-tours" class="btn btn-outline-secondary shadow-sm">← Quay lại danh sách</a>
        </div>

        <div class="row">
            <div class="col-lg-8">

                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-white py-3">
                        <h5 class="m-0 font-weight-bold text-primary"><i class="bi bi-info-circle me-2"></i>Tổng quan</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="fw-bold text-muted small">Mã Tour / ID</label>
                                <p class="fw-bold">#<?= $tour['tour_id'] ?></p>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-bold text-muted small">Loại Tour</label>
                                <p><span class="badge bg-info text-dark"><?= htmlspecialchars($tour['tour_type']) ?></span></p>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="fw-bold text-muted small">Giá niêm yết</label>
                                <p class="fw-bold text-success fs-5"><?= number_format($tour['price']) ?> ₫</p>
                            </div>
                            <div class="col-md-6">
                                <label class="fw-bold text-muted small">Thời lượng</label>
                                <p><i class="bi bi-clock me-1"></i> <?= $tour['duration_days'] ?> ngày</p>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold text-muted small">Mô tả</label>
                            <div class="bg-light p-3 rounded">
                                <?= nl2br(htmlspecialchars($tour['description'])) ?>
                            </div>
                        </div>
                        <div class="mb-0">
                            <label class="fw-bold text-muted small">Chính sách</label>
                            <p class="small text-secondary fst-italic"><?= nl2br(htmlspecialchars($tour['policy'] ?? 'Chưa cập nhật')) ?></p>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="m-0 font-weight-bold text-primary"><i class="bi bi-map me-2"></i>Lịch trình chi tiết</h5>
                    </div>
                    <div class="card-body p-0">
                        <?php if (empty($schedules)): ?>
                            <div class="text-center py-4 text-muted">
                                Chưa có lịch trình nào được cập nhật cho tour này.
                            </div>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($schedules as $sch): ?>
                                    <div class="list-group-item p-4">
                                        <div class="d-flex">
                                            <div class="me-4 text-center" style="min-width: 80px;">
                                                <div class="bg-primary text-white rounded-top fw-bold py-1 small">NGÀY</div>
                                                <div class="bg-light border border-top-0 rounded-bottom fs-4 fw-bold text-primary py-2">
                                                    <?= $sch['day_number'] ?>
                                                </div>
                                            </div>

                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold text-dark mb-1">
                                                    <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                                    <?= htmlspecialchars($sch['location']) ?>
                                                </h6>
                                                <p class="text-muted mb-2">
                                                    <?= nl2br(htmlspecialchars($sch['description'])) ?>
                                                </p>

                                                <?php if (!empty($sch['image']) && file_exists($sch['image'])): ?>
                                                    <div class="mt-3">
                                                        <img src="<?= BASE_URL . $sch['image'] ?>" alt="Ảnh ngày <?= $sch['day_number'] ?>"
                                                            class="img-fluid rounded shadow-sm" style="max-height: 200px; object-fit: cover;">
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-secondary">Hành động</h6>
                    </div>
                    <div class="card-body d-grid gap-2">
                        <a href="<?= BASE_URL ?>?act=admin-tours-edit&id=<?= $tour['tour_id'] ?>" class="btn btn-warning">
                            <i class="bi bi-pencil-square me-1"></i> Chỉnh sửa Tour
                        </a>

                        <form method="post" action="<?= BASE_URL ?>?act=admin-tours-delete" onsubmit="return confirm('Bạn chắc muốn xóa tour này?');">
                            <input type="hidden" name="id" value="<?= $tour['tour_id'] ?>">
                            <button class="btn btn-danger w-100">
                                <i class="bi bi-trash me-1"></i> Xóa Tour
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm border-0 mt-3">
                    <div class="card-body">
                        <div class="small text-muted mb-2">Ngày tạo: <?= date('d/m/Y H:i', strtotime($tour['created_at'])) ?></div>
                        <div class="small text-muted">Trạng thái:
                            <span class="fw-bold <?= ($tour['status'] === 'Hoạt động') ? 'text-success' : 'text-danger' ?>">
                                <?= $tour['status'] ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php endif; ?>
</div>