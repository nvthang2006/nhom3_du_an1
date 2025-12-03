<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Chi tiết Tour: <?= htmlspecialchars($assign['tour_name'] ?? 'Tour') ?></h1>
            <p class="text-muted mb-0 small">
                Thời gian: <span class="fw-bold text-primary"><?= date('d/m/Y', strtotime($assign['start_date'])) ?></span>
                đến <span class="fw-bold text-primary"><?= isset($assign['end_date']) ? date('d/m/Y', strtotime($assign['end_date'])) : '...' ?></span>
            </p>
        </div>
        <a href="<?= BASE_URL ?>?act=hdv-dashboard" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert alert-success border-0 shadow-sm border-start border-success border-4">
            <i class="bi bi-check-circle-fill me-2"></i><?= $_SESSION['flash'];
                                                        unset($_SESSION['flash']); ?>
        </div>
    <?php endif; ?>

    <div class="row g-4">

        <div class="col-lg-8">

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-map-fill me-2"></i>Lịch Trình Di Chuyển</h6>
                </div>
                <div class="card-body p-0">
                    <?php if (empty($schedules)): ?>
                        <div class="p-4 text-center text-muted">Chưa cập nhật lịch trình chi tiết.</div>
                    <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($schedules as $sch): ?>
                                <div class="list-group-item p-3 border-start border-4 border-primary ms-3 my-2 shadow-sm rounded-end">
                                    <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0 fw-bold text-primary">Ngày <?= $sch['day_number'] ?>: <?= htmlspecialchars($sch['location']) ?></h6>
                                        <?php if (!empty($sch['image'])): ?>
                                            <i class="bi bi-image text-muted" data-bs-toggle="tooltip" title="Có hình ảnh"></i>
                                        <?php endif; ?>
                                    </div>
                                    <p class="mb-0 small text-muted"><?= nl2br(htmlspecialchars($sch['description'])) ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <?php
            // Tính toán số lượng
            $totalGuest = count($customers);
            $checkedInCount = 0;
            foreach ($customers as $c) {
                if ($c['check_in'] == 1) $checkedInCount++;
            }
            $isFull = ($totalGuest > 0 && $checkedInCount == $totalGuest);
            $percent = ($totalGuest > 0) ? round(($checkedInCount / $totalGuest) * 100) : 0;
            ?>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="bi bi-people-fill me-2"></i>Danh sách đoàn
                        </h6>
                        <span class="badge bg-light text-dark border">
                            Đã điểm danh: <b><?= $checkedInCount ?>/<?= $totalGuest ?></b>
                        </span>
                    </div>

                    <?php if ($isFull): ?>
                        <div class="d-flex align-items-center animate-pulse">
                            <span class="badge bg-success fs-6 px-3 py-2 shadow-sm">
                                <i class="bi bi-check-circle-fill me-1"></i> ĐOÀN ĐÃ TẬP TRUNG ĐẦY ĐỦ
                            </span>
                        </div>
                    <?php else: ?>
                        <div class="progress" style="width: 100px; height: 10px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $percent ?>%"></div>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4">Họ tên / SĐT</th>
                                    <th>Thông tin</th>
                                    <th>Ghi chú</th>
                                    <th class="text-end pe-4">Check-in (<?= date('d/m', strtotime($assign['start_date'])) ?>)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($customers)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">Chưa có dữ liệu khách hàng.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php
                                    $today = date('Y-m-d');
                                    $startDate = $assign['start_date'];
                                    $endDate = $assign['end_date'];
                                    $isStarted = ($today >= $startDate);
                                    $isEnded = ($today > $endDate);
                                    ?>
                                    <?php foreach ($customers as $cus): ?>
                                        <tr class="<?= $cus['check_in'] == 1 ? 'bg-success-subtle' : '' ?>">
                                            <td class="ps-4">
                                                <div class="fw-bold"><?= htmlspecialchars($cus['full_name']) ?></div>
                                                <div class="small text-muted"><?= htmlspecialchars($cus['phone'] ?? '---') ?></div>
                                            </td>
                                            <td>
                                                <small><?= $cus['gender'] ?? '-' ?> / <?= !empty($cus['dob']) ? date('Y', strtotime($cus['dob'])) : '-' ?></small>
                                            </td>

                                            <td>
                                                <form action="<?= BASE_URL ?>?act=hdv-customer-update" method="POST" class="d-flex gap-1">
                                                    <input type="hidden" name="type" value="note">
                                                    <input type="hidden" name="customer_id" value="<?= $cus['customer_id'] ?>">
                                                    <input type="hidden" name="assign_id" value="<?= $assign['assign_id'] ?>">
                                                    <input type="text" name="value" class="form-control form-control-sm border-0 bg-transparent"
                                                        value="<?= htmlspecialchars($cus['note'] ?? '') ?>" placeholder="Nhập ghi chú...">
                                                    <button class="btn btn-sm btn-link text-muted"><i class="bi bi-pencil-square"></i></button>
                                                </form>
                                            </td>

                                            <td class="text-end pe-4">
                                                <?php if (!$isStarted): ?>
                                                    <span class="badge bg-light text-muted border"><i class="bi bi-clock"></i> Chờ ngày đi</span>
                                                <?php elseif ($isEnded && $cus['check_in'] == 0): ?>
                                                    <span class="badge bg-secondary">Vắng mặt</span>
                                                <?php else: ?>
                                                    <form action="<?= BASE_URL ?>?act=hdv-customer-update" method="POST">
                                                        <input type="hidden" name="type" value="checkin">
                                                        <input type="hidden" name="customer_id" value="<?= $cus['customer_id'] ?>">
                                                        <input type="hidden" name="assign_id" value="<?= $assign['assign_id'] ?>">

                                                        <?php if ($cus['check_in'] == 1): ?>
                                                            <input type="hidden" name="value" value="0">
                                                            <button class="btn btn-sm btn-success fw-bold shadow-sm">
                                                                <i class="bi bi-check-lg"></i> Đã đến
                                                            </button>
                                                        <?php else: ?>
                                                            <input type="hidden" name="value" value="1">
                                                            <button class="btn btn-sm btn-outline-secondary">
                                                                <i class="bi bi-circle"></i> Xác nhận
                                                            </button>
                                                        <?php endif; ?>
                                                    </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="m-0 font-weight-bold"><i class="bi bi-journal-plus me-2"></i>Viết nhật ký / Báo cáo</h6>
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>?act=hdv-log-store" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="assign_id" value="<?= $assign['assign_id'] ?>">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Ngày ghi nhận</label>
                            <input type="date" name="log_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Nội dung / Sự kiện</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="VD: Đoàn đã tập trung đủ..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-danger">Sự cố (Nếu có)</label>
                            <input type="text" name="issue" class="form-control border-danger-subtle text-danger">
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary fw-bold">Lưu nhật ký</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-clock-history me-2"></i>Nhật trình đã lưu</h6>
                </div>
                <ul class="list-group list-group-flush">
                    <?php if (empty($logs)): ?>
                        <li class="list-group-item text-center text-muted py-4">Chưa có nhật ký nào.</li>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                            <li class="list-group-item p-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <strong class="text-primary"><?= date('d/m/Y', strtotime($log['log_date'])) ?></strong>
                                    <small class="text-muted"><?= date('H:i', strtotime($log['created_at'])) ?></small>
                                </div>
                                <p class="mb-0 small text-dark"><?= nl2br(htmlspecialchars($log['description'])) ?></p>
                                <?php if (!empty($log['issue'])): ?>
                                    <div class="text-danger small mt-1"><strong>Sự cố:</strong> <?= htmlspecialchars($log['issue']) ?></div>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    /* Hiệu ứng nhấp nháy nhẹ cho badge xác nhận */
    @keyframes pulse-green {
        0% {
            box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.7);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(25, 135, 84, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(25, 135, 84, 0);
        }
    }

    .animate-pulse .badge {
        animation: pulse-green 2s infinite;
    }
</style>