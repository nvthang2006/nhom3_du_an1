<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Danh sách đoàn: <?= htmlspecialchars($tour['tour_name']) ?></h1>
            <p class="text-muted mb-0 small">
                Khởi hành: <strong><?= date('d/m/Y', strtotime($departure['start_date'])) ?></strong> 
                | Tổng: <strong class="text-primary"><?= $stats['total'] ?> khách</strong> 
                (Nam: <?= $stats['male'] ?>, Nữ: <?= $stats['female'] ?>)
            </p>
        </div>
        <div>
            <a href="<?= BASE_URL ?>?act=admin-departures-print&id=<?= $departure['departure_id'] ?>" target="_blank" class="btn btn-warning shadow-sm me-2">
                <i class="bi bi-printer-fill me-1"></i> In danh sách
            </a>
            <a href="<?= BASE_URL ?>?act=admin-departures-manage&id=<?= $departure['departure_id'] ?>" class="btn btn-secondary shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert alert-success"><?= $_SESSION['flash']; unset($_SESSION['flash']); ?></div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>?act=admin-departures-update-passengers" method="POST">
        <input type="hidden" name="departure_id" value="<?= $departure['departure_id'] ?>">
        
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-uppercase small text-muted">
                            <tr>
                                <th class="ps-3" style="width: 50px;">STT</th>
                                <th>Họ và tên</th>
                                <th style="width: 100px;">Năm sinh</th>
                                <th style="width: 80px;">Giới tính</th>
                                <th>SĐT / Liên hệ</th>
                                <th>CMND / Passport</th>
                                <th style="width: 300px;" class="bg-warning-subtle">Ghi chú đặc biệt</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($passengers)): ?>
                                <tr><td colspan="7" class="text-center py-5">Chưa có hành khách nào.</td></tr>
                            <?php else: ?>
                                <?php foreach($passengers as $i => $p): ?>
                                    <tr>
                                        <td class="ps-3 fw-bold text-muted"><?= $i + 1 ?></td>
                                        <td>
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($p['full_name']) ?></div>
                                            <small class="text-muted">Booking: #<?= $p['booking_id'] ?></small>
                                        </td>
                                        <td><?= !empty($p['dob']) ? date('Y', strtotime($p['dob'])) : '-' ?></td>
                                        <td><?= $p['gender'] ?></td>
                                        <td>
                                            <?php if(!empty($p['phone'])): ?>
                                                <div><?= $p['phone'] ?></div>
                                            <?php else: ?>
                                                <small class="text-muted text-truncate" title="SĐT người đặt: <?= $p['booker_phone'] ?>">
                                                    (Theo người đặt)
                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($p['passport_number'] ?? '') ?></td>
                                        
                                        <td class="bg-warning-subtle">
                                            <textarea name="passengers[<?= $p['customer_id'] ?>][note]" 
                                                      class="form-control form-control-sm" rows="1" 
                                                      placeholder="Ăn chay, dị ứng..."><?= htmlspecialchars($p['note'] ?? '') ?></textarea>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white py-3 text-end sticky-bottom">
                <button type="submit" class="btn btn-primary fw-bold px-4">
                    <i class="bi bi-save me-2"></i> Lưu Ghi chú
                </button>
            </div>
        </div>
    </form>
</div>