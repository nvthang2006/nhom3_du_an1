<?php
?>
<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Cập nhật Booking #<?= $booking['booking_id'] ?></h1>
            <p class="text-muted mb-0 small">Tour: <strong><?= htmlspecialchars($booking['tour_name']) ?></strong> - Ngày đi: <?= date('d/m/Y', strtotime($booking['start_date'])) ?></p>
        </div>
        <a href="<?= BASE_URL ?>?act=admin-bookings" class="btn btn-outline-secondary shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'];
                                        unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>?act=admin-bookings-update">
        <input type="hidden" name="booking_id" value="<?= $booking['booking_id'] ?>">

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Trạng thái đơn hàng</h6>
                    </div>
                    <div class="card-body">
                        <label class="form-label fw-bold">Trạng thái hiện tại</label>
                        <select name="status" class="form-select form-select-lg mb-3">
                            <?php
                            $statuses = ['Chờ xác nhận', 'Đã cọc', 'Hoàn tất', 'Hủy'];
                            foreach ($statuses as $st) {
                                $sel = ($booking['status'] === $st) ? 'selected' : '';
                                echo "<option value='$st' $sel>$st</option>";
                            }
                            ?>
                        </select>

                        <div class="alert alert-light border small text-muted">
                            <strong>Note:</strong> <br>
                            - <strong>Đã cọc:</strong> Khách đã thanh toán một phần.<br>
                            - <strong>Hoàn tất:</strong> Đã thanh toán đủ và kết thúc tour.<br>
                            - <strong>Hủy:</strong> Đơn bị hủy, không tính vào doanh thu.
                        </div>

                        <hr>
                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2">
                            <i class="bi bi-save me-1"></i> Lưu thay đổi
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Thông tin khách hàng (<?= count($customers) ?> người)</h6>
                    </div>
                    <div class="card-body bg-light">
                        <?php foreach ($customers as $i => $cus): ?>
                            <div class="card mb-3 border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="badge bg-secondary">Khách #<?= $i + 1 ?></span>
                                    </div>
                                    <div class="row g-2">
                                        <div class="col-md-4">
                                            <label class="small text-muted">Họ tên</label>
                                            <input type="text" name="customers[<?= $cus['customer_id'] ?>][full_name]" class="form-control form-control-sm fw-bold" value="<?= htmlspecialchars($cus['full_name']) ?>">
                                        </div>
                                        <div class="col-md-2">
                                            <label class="small text-muted">Giới tính</label>
                                            <select name="customers[<?= $cus['customer_id'] ?>][gender]" class="form-select form-select-sm">
                                                <option value="Nam" <?= $cus['gender'] == 'Nam' ? 'selected' : '' ?>>Nam</option>
                                                <option value="Nữ" <?= $cus['gender'] == 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                                                <option value="Khác" <?= $cus['gender'] == 'Khác' ? 'selected' : '' ?> disabled>Khác</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted">Ngày sinh</label>
                                            <input type="date" name="customers[<?= $cus['customer_id'] ?>][dob]" class="form-control form-control-sm" value="<?= $cus['dob'] ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="small text-muted">SĐT</label>
                                            <input type="text" name="customers[<?= $cus['customer_id'] ?>][phone]" class="form-control form-control-sm" value="<?= htmlspecialchars($cus['phone'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="small text-muted">Passport/CCCD</label>
                                            <input type="text" name="customers[<?= $cus['customer_id'] ?>][passport_number]" class="form-control form-control-sm" value="<?= htmlspecialchars($cus['passport_number'] ?? '') ?>">
                                        </div>
                                        <div class="col-md-8">
                                            <label class="small text-muted">Ghi chú (Dị ứng, ăn chay...)</label>
                                            <input type="text" name="customers[<?= $cus['customer_id'] ?>][note]" class="form-control form-control-sm" value="<?= htmlspecialchars($cus['note'] ?? '') ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>