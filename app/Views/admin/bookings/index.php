<?php
// app/Views/admin/bookings/index.php
?>

<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Quản lý Đặt Tour</h1>
            <p class="text-muted mb-0 small">Danh sách các đơn đặt chỗ từ khách hàng</p>
        </div>
        <a href="<?= BASE_URL ?>?act=admin-bookings-create" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Tạo Booking mới
        </a>
    </div>
    
    <?php if (!empty($flash) || !empty($_SESSION['flash'])): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-success border-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <?= htmlspecialchars($flash ?? $_SESSION['flash']) ?>
            <?php unset($_SESSION['flash']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form class="row g-3" method="get" action="<?= BASE_URL ?>">
                <input type="hidden" name="act" value="admin-bookings">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" name="q" class="form-control border-start-0 ps-0 bg-light"
                            placeholder="Mã vé, SĐT..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select bg-light cursor-pointer">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="Chờ xác nhận" <?= (($_GET['status'] ?? '') === 'Chờ xác nhận') ? 'selected' : '' ?>>Chờ xác nhận</option>
                        <option value="Đã cọc" <?= (($_GET['status'] ?? '') === 'Đã cọc') ? 'selected' : '' ?>>Đã cọc</option>
                        <option value="Hoàn tất" <?= (($_GET['status'] ?? '') === 'Hoàn tất') ? 'selected' : '' ?>>Hoàn tất</option>
                        <option value="Hủy" <?= (($_GET['status'] ?? '') === 'Hủy') ? 'selected' : '' ?>>Đã hủy</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" name="date" class="form-control bg-light" value="<?= htmlspecialchars($_GET['date'] ?? '') ?>">
                </div>
                <div class="col-md-2">
                    <button class="btn btn-primary w-100 fw-semibold" type="submit">Lọc</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <?php if (empty($bookings)): ?>
                <div class="text-center py-5">
                    <div class="mb-3"><i class="bi bi-journal-x text-muted" style="font-size: 3rem; opacity: 0.5;"></i></div>
                    <h5 class="text-muted">Chưa có dữ liệu đặt tour</h5>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4">Mã Booking</th>
                                <th>SĐT Liên hệ</th>
                                <th>Thông tin Tour</th>
                                <th>Chi tiết</th>
                                <th>Trạng thái</th>
                                <th class="text-end pe-4">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($bookings as $b):
                                $st = $b['status'] ?? 'Chờ xác nhận';
                                $badgeClass = match ($st) {
                                    'Hoàn tất' => 'bg-success-subtle text-success border-success-subtle',
                                    'Đã cọc' => 'bg-primary-subtle text-primary border-primary-subtle',
                                    'Hủy' => 'bg-danger-subtle text-danger border-danger-subtle',
                                    default => 'bg-warning-subtle text-warning border-warning-subtle'
                                };
                                $bookingId = $b['booking_id'];
                                $contactPhone = $b['contact_phone'] ?? '---';
                            ?>
                                <tr>
                                    <td class="ps-4">
                                        <a href="<?= BASE_URL ?>?act=admin-bookings-edit&id=<?= $bookingId ?>" class="fw-bold text-decoration-none text-primary">#<?= $bookingId ?></a>
                                        <div class="small text-muted"><?= date('d/m/Y H:i', strtotime($b['created_at'])) ?></div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark"><i class="bi bi-telephone me-1 text-muted"></i> <?= htmlspecialchars($contactPhone) ?></span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark text-truncate" style="max-width: 200px;">
                                            <?= htmlspecialchars($b['tour_name'] ?? 'Tour ID: ' . $b['tour_id']) ?>
                                        </div>
                                        <div class="small text-muted">
                                            <i class="bi bi-calendar-event me-1"></i> KH: <?= date('d/m/Y', strtotime($b['start_date'])) ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div><span class="fw-bold"><?= number_format($b['total_price']) ?></span> <small>₫</small></div>
                                        <div class="small text-muted"><?= $b['total_people'] ?> khách</div>
                                    </td>
                                    <td>
                                        <span class="badge rounded-pill border px-3 py-2 <?= $badgeClass ?>">
                                            <?= $st ?>
                                        </span>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="<?= BASE_URL ?>?act=admin-bookings-edit&id=<?= $bookingId ?>" 
                                           class="btn btn-sm btn-outline-primary fw-bold">
                                            <i class="bi bi-pencil-square me-1"></i> Cập nhật
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($bookings) && count($bookings) > 10): ?>
            <div class="card-footer bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <div class="small text-muted">Hiển thị <?= count($bookings) ?> kết quả</div>
                <nav><ul class="pagination pagination-sm mb-0"><li class="page-item active"><a class="page-link" href="#">1</a></li></ul></nav>
            </div>
        <?php endif; ?>
    </div>
</div>