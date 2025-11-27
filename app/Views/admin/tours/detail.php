<div class="card shadow-sm border-0 mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="m-0 font-weight-bold text-success"><i class="bi bi-calendar-check me-2"></i>Lịch khởi hành (Tour Departures)</h5>
    </div>
    <div class="card-body">
        
        <form action="<?= BASE_URL ?>?act=admin-tours-store-departure" method="POST" class="row g-3 align-items-end mb-4 border-bottom pb-4">
            <input type="hidden" name="tour_id" value="<?= $tour['tour_id'] ?>">
            <input type="hidden" name="duration_days" value="<?= $tour['duration_days'] ?>"> <div class="col-md-3">
                <label class="small fw-bold text-muted">Ngày đi</label>
                <input type="date" name="start_date" class="form-control" required min="<?= date('Y-m-d') ?>">
            </div>
            <div class="col-md-3">
                <label class="small fw-bold text-muted">Giá vé (đợt này)</label>
                <div class="input-group">
                    <input type="number" name="price" class="form-control" value="<?= $tour['price'] ?>" required>
                    <span class="input-group-text">₫</span>
                </div>
            </div>
            <div class="col-md-2">
                <label class="small fw-bold text-muted">Số chỗ</label>
                <input type="number" name="max_people" class="form-control" value="<?= $tour['max_people'] ?? 20 ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success w-100 fw-bold"><i class="bi bi-plus-lg"></i> Thêm</button>
            </div>
        </form>

        <?php if(empty($departures)): ?>
            <p class="text-muted text-center">Chưa có lịch khởi hành nào được tạo.</p>
        <?php else: ?>
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>Ngày đi</th>
                        <th>Ngày về</th>
                        <th>Giá vé</th>
                        <th>Chỗ (Đã đặt/Tổng)</th>
                        <th>Trạng thái</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($departures as $dep): ?>
                    <tr>
                        <td class="fw-bold text-primary"><?= date('d/m/Y', strtotime($dep['start_date'])) ?></td>
                        <td><?= date('d/m/Y', strtotime($dep['end_date'])) ?></td>
                        <td class="fw-bold"><?= number_format($dep['price']) ?> ₫</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="me-2"><?= $dep['booked_count'] ?>/<?= $dep['max_people'] ?></span>
                                <div class="progress flex-grow-1" style="height: 6px; width: 80px;">
                                    <?php $percent = ($dep['booked_count'] / $dep['max_people']) * 100; ?>
                                    <div class="progress-bar bg-<?= $percent > 90 ? 'danger' : 'success' ?>" role="progressbar" style="width: <?= $percent ?>%"></div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-info text-dark"><?= $dep['status'] ?></span></td>
                        <td class="text-end">
                            <?php if($dep['booked_count'] == 0): ?>
                            <form action="<?= BASE_URL ?>?act=admin-tours-delete-departure" method="POST" onsubmit="return confirm('Xóa lịch này?')">
                                <input type="hidden" name="departure_id" value="<?= $dep['departure_id'] ?>">
                                <input type="hidden" name="tour_id" value="<?= $tour['tour_id'] ?>">
                                <button class="btn btn-sm btn-outline-danger border-0"><i class="bi bi-trash"></i></button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>