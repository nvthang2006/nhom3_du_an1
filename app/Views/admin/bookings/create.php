<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">
                <?= ($step == 1) ? 'Đặt Tour Mới (Bước 1)' : 'Nhập Danh Sách Đoàn (Bước 2)' ?>
            </h1>
            <p class="text-muted mb-0 small">Hỗ trợ đặt tour cho khách lẻ và khách đoàn</p>
        </div>
        <a href="<?= BASE_URL ?>?act=admin-bookings" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Hủy bỏ
        </a>
    </div>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger shadow-sm">
            <i class="bi bi-exclamation-circle-fill me-2"></i> <?= $_SESSION['error'];
                                                                unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <?php if ($step == 1): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 fw-bold text-primary">Bước 1: Chọn Lịch Khởi Hành</h6>
        </div>
        <div class="card-body">
            
            <form method="GET" action="" id="selectTourForm" class="mb-4">
                <input type="hidden" name="act" value="admin-bookings-create">
                <label class="form-label fw-bold">Chọn Tour Du Lịch</label>
                <div class="input-group">
                    <select name="tour_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Chọn tour để xem lịch --</option>
                        <?php foreach ($tours as $t): ?>
                            <option value="<?= $t['tour_id'] ?>" <?= (isset($selectedTourId) && $selectedTourId == $t['tour_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($t['tour_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button class="btn btn-primary" type="submit">Xem lịch</button>
                </div>
            </form>

            <?php if (!empty($selectedTourId)): ?>
                <hr>
                <form method="post" action="<?= BASE_URL ?>?act=admin-bookings-prepare">
                    <input type="hidden" name="tour_id" value="<?= $selectedTourId ?>">

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Chọn Ngày Khởi Hành <span class="text-danger">*</span></label>
                            
                            <?php if (empty($departures)): ?>
                                <div class="alert alert-warning">Tour này hiện chưa có lịch khởi hành nào. Vui lòng vào Quản lý Tour để tạo lịch trước.</div>
                            <?php else: ?>
                                <select name="departure_id" class="form-select form-select-lg" required>
                                    <option value="">-- Chọn ngày đi --</option>
                                    <?php foreach ($departures as $d): 
                                        $av = $d['max_people'] - $d['booked_count'];
                                        $disabled = $av <= 0 ? 'disabled' : '';
                                        $text = date('d/m/Y', strtotime($d['start_date'])) . " - Giá: " . number_format($d['price']) . "đ (Còn $av chỗ)";
                                        if ($av <= 0) $text .= " [HẾT CHỖ]";
                                    ?>
                                        <option value="<?= $d['departure_id'] ?>" <?= $disabled ?>>
                                            <?= $text ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Số lượng khách <span class="text-danger">*</span></label>
                            <input type="number" name="total_people" class="form-control form-control-lg" required min="1" value="1">
                        </div>
                        
                        <div class="col-12">
                            <label class="form-label fw-bold">SĐT Liên hệ</label>
                            <input type="text" name="contact_phone" class="form-control" required placeholder="09xxx...">
                        </div>

                        <div class="col-12 text-end mt-4">
                            <button type="submit" class="btn btn-primary px-4 fw-bold" <?= empty($departures) ? 'disabled' : '' ?>>
                                Tiếp tục (Nhập tên khách) <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                </form>
            <?php endif; ?>

        </div>
    </div>
<?php elseif ($step == 2): ?>
        <form method="post" action="<?= BASE_URL ?>?act=admin-bookings-store">
            <input type="hidden" name="tour_id" value="<?= $preData['tour_id'] ?>">
            <input type="hidden" name="start_date" value="<?= $preData['start_date'] ?>">
            <input type="hidden" name="total_people" value="<?= $preData['total_people'] ?>">
            <input type="hidden" name="total_price" value="<?= $totalPrice ?>">

            <input type="hidden" name="contact_phone" value="<?= htmlspecialchars($preData['contact_phone']) ?>">

            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-primary text-white py-3">
                            <h6 class="m-0 fw-bold"><i class="bi bi-receipt me-2"></i>Thông tin Booking</h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush mb-3">
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <span class="text-muted">Tour:</span>
                                    <span class="fw-bold text-end" style="max-width:60%"><?= htmlspecialchars($tour['tour_name']) ?></span>
                                </li>
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <span class="text-muted">SĐT liên hệ:</span>
                                    <span class="fw-bold text-danger"><?= htmlspecialchars($preData['contact_phone']) ?></span>
                                </li>
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <span class="text-muted">Ngày đi:</span>
                                    <span class="fw-bold text-primary"><?= date('d/m/Y', strtotime($preData['start_date'])) ?></span>
                                </li>
                                <li class="list-group-item px-0 d-flex justify-content-between">
                                    <span class="text-muted">Số lượng:</span>
                                    <span class="fw-bold"><?= $preData['total_people'] ?> người</span>
                                </li>
                            </ul>
                            <div class="alert alert-light border text-center mb-0">
                                <small class="text-muted">Tổng tạm tính</small>
                                <div class="fs-4 fw-bold text-danger"><?= number_format($totalPrice) ?> ₫</div>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <label class="form-label fw-bold">Yêu cầu đặc biệt (Ghi chú chung)</label>
                            <textarea name="note" class="form-control" rows="4" placeholder="VD: Cần xe đưa đón, ăn chay cho 2 người..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 fw-bold text-success"><i class="bi bi-people-fill me-2"></i>Danh sách hành khách</h6>
                            <span class="badge bg-warning text-dark">Trạng thái: Tạm giữ chỗ</span>
                        </div>
                        <div class="card-body bg-light">
                            <?php for ($i = 0; $i < $preData['total_people']; $i++): ?>
                                <div class="card mb-3 border-0 shadow-sm">
                                    <div class="card-body">
                                        <div class="row align-items-center mb-2">
                                            <div class="col-auto">
                                                <span class="badge bg-secondary rounded-circle">#<?= $i + 1 ?></span>
                                            </div>
                                            <div class="col">
                                                <h6 class="fw-bold text-muted mb-0">Thông tin khách hàng <?= $i + 1 ?></h6>
                                                <?php if ($i == 0): ?>
                                                    <small class="text-success fst-italic">(SĐT "<?= htmlspecialchars($preData['contact_phone']) ?>" sẽ được lưu cho người này)</small>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                        <div class="row g-2">
                                            <div class="col-md-4">
                                                <label class="small text-muted">Họ và tên <span class="text-danger">*</span></label>
                                                <input type="text" name="passengers[<?= $i ?>][full_name]" class="form-control form-control-sm" required placeholder="Nhập tên khách">
                                            </div>
                                            <div class="col-md-2">
                                                <label class="small text-muted">Giới tính</label>
                                                <select name="passengers[<?= $i ?>][gender]" class="form-select form-select-sm">
                                                    <option value="Nam">Nam</option>
                                                    <option value="Nữ">Nữ</option>
                                                    <option value="Khác">Khác</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="small text-muted">Ngày sinh</label>
                                                <input type="date" name="passengers[<?= $i ?>][dob]" class="form-control form-control-sm">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="small text-muted">CMND</label>
                                                <input type="text" name="passengers[<?= $i ?>][passport_number]" class="form-control form-control-sm">
                                            </div>
                                            <?php if ($i > 0): ?>
                                                <div class="col-md-4">
                                                    <label class="small text-muted">SĐT riêng (nếu có)</label>
                                                    <input type="text" name="passengers[<?= $i ?>][phone]" class="form-control form-control-sm">
                                                </div>
                                            <?php endif; ?>

                                            <div class="col-12">
                                                <label class="small text-muted">Ghi chú riêng (nếu có)</label>
                                                <input type="text" name="passengers[<?= $i ?>][note]" class="form-control form-control-sm" placeholder="VD: Dị ứng tôm, trẻ em...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                        <div class="card-footer bg-white py-3 text-end">
                            <a href="<?= BASE_URL ?>?act=admin-bookings-create" class="btn btn-light me-2">Quay lại</a>
                            <button type="submit" class="btn btn-success px-4 fw-bold">
                                <i class="bi bi-check-circle-fill me-2"></i> Xác nhận & Lưu Booking
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    <?php endif; ?>
</div>