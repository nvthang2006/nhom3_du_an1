<div class="container py-4">

    <?php if (!$tour): ?>
        <div class="alert alert-danger">Không tìm thấy tour.</div>
    <?php else: ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Chi tiết Tour</h3>
            <a href="<?= BASE_URL ?>?act=admin-tours" class="btn btn-secondary btn-sm">← Quay lại</a>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">

                <div class="mb-3">
                    <label class="form-label fw-bold">Tên Tour</label>
                    <p class="form-control"><?= htmlspecialchars($tour['tour_name']) ?></p>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Loại Tour</label>
                    <p class="form-control"><?= htmlspecialchars($tour['tour_type']) ?></p>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Giá</label>
                    <p class="form-control"><?= number_format($tour['price']) ?> VND</p>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Số ngày</label>
                    <p class="form-control"><?= $tour['duration_days'] ?> ngày</p>
                </div>

                <div class="mt-4 d-flex gap-2">
                    <a href="<?= BASE_URL ?>?act=admin-tours-edit&id=<?= $tour['tour_id'] ?>" class="btn btn-primary">Sửa</a>

                    <form method="post" action="<?= BASE_URL ?>?act=admin-tours-delete" onsubmit="return confirm('Bạn chắc muốn xóa tour này?');">
                        <input type="hidden" name="id" value="<?= $tour['tour_id'] ?>">
                        <button class="btn btn-danger">Xóa</button>
                    </form>
                </div>

            </div>
        </div>

    <?php endif; ?>
</div>