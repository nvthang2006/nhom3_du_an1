<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <div class="d-flex align-items-center gap-2">
                <h1 class="h3 mb-0 text-gray-800 fw-bold">Chi tiết Tour: <?= htmlspecialchars($tour['tour_name']) ?></h1>
                <?php if ($tour['status'] === 'Hoạt động'): ?>
                    <span class="badge bg-success rounded-pill">Đang hoạt động</span>
                <?php else: ?>
                    <span class="badge bg-secondary rounded-pill">Ngừng hoạt động</span>
                <?php endif; ?>
            </div>
            <p class="text-muted mb-0 small mt-1">
                <i class="bi bi-hash"></i> ID: <?= $tour['tour_id'] ?> | 
                <i class="bi bi-calendar3"></i> Ngày tạo: <?= date('d/m/Y', strtotime($tour['created_at'])) ?>
            </p>
        </div>
        <div class="d-flex gap-2">
            <a href="<?= BASE_URL ?>?act=admin-tours-edit&id=<?= $tour['tour_id'] ?>" class="btn btn-warning shadow-sm fw-bold">
                <i class="bi bi-pencil-square me-1"></i> Sửa Tour
            </a>
            <a href="<?= BASE_URL ?>?act=admin-tours" class="btn btn-outline-secondary shadow-sm">
                <i class="bi bi-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert alert-success border-0 shadow-sm border-start border-success border-4">
            <i class="bi bi-check-circle-fill me-2"></i><?= $_SESSION['flash']; unset($_SESSION['flash']); ?>
        </div>
    <?php endif; ?>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-primary border-4">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-bold mb-1">Giá niêm yết (Gốc)</div>
                    <div class="fs-4 fw-bold text-primary"><?= number_format($tour['price']) ?> ₫</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-info border-4">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-bold mb-1">Thời lượng</div>
                    <div class="fs-4 fw-bold text-dark"><?= $tour['duration_days'] ?> Ngày</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-warning border-4">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-bold mb-1">Loại Tour</div>
                    <div class="fs-4 fw-bold text-dark"><?= htmlspecialchars($tour['tour_type']) ?></div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100 border-start border-success border-4">
                <div class="card-body">
                    <div class="text-muted small text-uppercase fw-bold mb-1">Số chỗ tối đa</div>
                    <div class="fs-4 fw-bold text-dark"><?= $tour['max_people'] ?? 20 ?> Khách</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom pt-3">
            <ul class="nav nav-tabs card-header-tabs" id="tourTab" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active fw-bold" id="departures-tab" data-bs-toggle="tab" data-bs-target="#departures" type="button">
                        <i class="bi bi-calendar-week me-2"></i>Quản lý Lịch Khởi Hành
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-bold" id="itinerary-tab" data-bs-toggle="tab" data-bs-target="#itinerary" type="button">
                        <i class="bi bi-map me-2"></i>Lịch trình (Schedule)
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link fw-bold" id="suppliers-tab" data-bs-toggle="tab" data-bs-target="#suppliers" type="button">
                        <i class="bi bi-building me-2"></i>Đối tác liên kết
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content" id="tourTabContent">
                
                <div class="tab-pane fade show active" id="departures" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold text-primary mb-0">Danh sách các đoàn khởi hành</h5>
                        <button type="button" class="btn btn-primary btn-sm fw-bold" data-bs-toggle="modal" data-bs-target="#createDepartureModal">
                            <i class="bi bi-plus-lg me-1"></i> Tạo lịch mới
                        </button>
                    </div>

                    <ul class="nav nav-pills mb-3 bg-light p-2 rounded" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active btn-sm" id="pills-upcoming-tab" data-bs-toggle="pill" data-bs-target="#pills-upcoming" type="button">Sắp khởi hành <span class="badge bg-white text-primary ms-1"><?= count($upcomingDepartures) ?></span></button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link btn-sm" id="pills-history-tab" data-bs-toggle="pill" data-bs-target="#pills-history" type="button">Lịch sử / Đã xong <span class="badge bg-white text-secondary ms-1"><?= count($historyDepartures) ?></span></button>
                        </li>
                    </ul>

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-upcoming">
                            <?php if (empty($upcomingDepartures)): ?>
                                <div class="text-center py-5 border rounded bg-light border-dashed">
                                    <i class="bi bi-calendar-x text-muted opacity-50 display-4"></i>
                                    <p class="text-muted mt-3">Chưa có lịch khởi hành nào sắp tới.</p>
                                </div>
                            <?php else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle border mb-0">
                                        <thead class="bg-light">
                                            <tr>
                                                <th>Ngày đi / Mã</th>
                                                <th>Ngày về</th>
                                                <th>Giá bán</th>
                                                <th style="width: 200px;">Tình trạng chỗ</th>
                                                <th>Trạng thái</th>
                                                <th class="text-end">Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($upcomingDepartures as $dep): ?>
                                                <tr>
                                                    <td>
                                                        <div class="fw-bold text-primary"><?= date('d/m/Y', strtotime($dep['start_date'])) ?></div>
                                                        <small class="text-muted font-monospace">#<?= $dep['departure_id'] ?></small>
                                                    </td>
                                                    <td><?= date('d/m/Y', strtotime($dep['end_date'])) ?></td>
                                                    <td class="fw-bold text-success"><?= number_format($dep['price']) ?> ₫</td>
                                                    <td>
                                                        <?php 
                                                            $percent = ($dep['booked_count'] / $dep['max_people']) * 100; 
                                                            $color = $percent > 80 ? 'danger' : ($percent > 50 ? 'warning' : 'success');
                                                        ?>
                                                        <div class="d-flex justify-content-between small mb-1">
                                                            <span><?= $dep['booked_count'] ?> khách</span>
                                                            <span class="text-muted">/ <?= $dep['max_people'] ?></span>
                                                        </div>
                                                        <div class="progress" style="height: 6px;">
                                                            <div class="progress-bar bg-<?= $color ?>" role="progressbar" style="width: <?= $percent ?>%"></div>
                                                        </div>
                                                    </td>
                                                    <td><span class="badge bg-success-subtle text-success border border-success-subtle"><?= $dep['status'] ?></span></td>
                                                    <td class="text-end">
                                                        <a href="<?= BASE_URL ?>?act=admin-departures-manage&id=<?= $dep['departure_id'] ?>" class="btn btn-sm btn-outline-primary fw-bold">
                                                            Điều hành <i class="bi bi-arrow-right"></i>
                                                        </a>
                                                        <?php if($dep['booked_count'] == 0): ?>
                                                            <form action="<?= BASE_URL ?>?act=admin-tours-delete-departure" method="POST" class="d-inline ms-1" onsubmit="return confirm('Bạn chắc chắn muốn xóa lịch này?')">
                                                                <input type="hidden" name="departure_id" value="<?= $dep['departure_id'] ?>">
                                                                <input type="hidden" name="tour_id" value="<?= $tour['tour_id'] ?>">
                                                                <button class="btn btn-sm btn-light text-danger border"><i class="bi bi-trash"></i></button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="tab-pane fade" id="pills-history">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle text-muted small">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Ngày đi</th>
                                            <th>Ngày về</th>
                                            <th>Khách thực tế</th>
                                            <th>Trạng thái</th>
                                            <th class="text-end">Chi tiết</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($historyDepartures)): ?>
                                            <tr><td colspan="5" class="text-center py-3">Không có dữ liệu lịch sử.</td></tr>
                                        <?php else: ?>
                                            <?php foreach ($historyDepartures as $dep): ?>
                                                <tr>
                                                    <td><?= date('d/m/Y', strtotime($dep['start_date'])) ?></td>
                                                    <td><?= date('d/m/Y', strtotime($dep['end_date'])) ?></td>
                                                    <td><?= $dep['booked_count'] ?></td>
                                                    <td><?= $dep['status'] ?></td>
                                                    <td class="text-end">
                                                        <a href="<?= BASE_URL ?>?act=admin-departures-manage&id=<?= $dep['departure_id'] ?>" class="btn btn-sm btn-light border">Xem</a>
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

                <div class="tab-pane fade" id="itinerary" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-8">
                            <h5 class="fw-bold text-dark mb-3">Chương trình Tour chi tiết</h5>
                            <?php if (empty($schedules)): ?>
                                <div class="alert alert-warning">Chưa cập nhật lịch trình cho tour này. <a href="<?= BASE_URL ?>?act=admin-tours-edit&id=<?= $tour['tour_id'] ?>" class="fw-bold">Cập nhật ngay</a></div>
                            <?php else: ?>
                                <div class="timeline">
                                    <?php foreach ($schedules as $sch): ?>
                                        <div class="card mb-3 border-start border-primary border-3 shadow-sm">
                                            <div class="card-body">
                                                <div class="d-flex gap-3">
                                                    <div class="flex-shrink-0 text-center" style="min-width: 60px;">
                                                        <div class="bg-primary text-white rounded p-1 fw-bold">Ngày</div>
                                                        <div class="fs-4 fw-bold text-primary"><?= $sch['day_number'] ?></div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="fw-bold text-uppercase mb-1"><i class="bi bi-geo-alt-fill text-danger me-1"></i><?= htmlspecialchars($sch['location']) ?></h6>
                                                        <p class="mb-0 text-muted"><?= nl2br(htmlspecialchars($sch['description'])) ?></p>
                                                    </div>
                                                    <?php if(!empty($sch['image'])): ?>
                                                        <div class="flex-shrink-0 d-none d-md-block">
                                                            <img src="<?= BASE_URL . $sch['image'] ?>" class="rounded" style="width: 100px; height: 70px; object-fit: cover;">
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-lg-4">
                            <div class="card bg-light border-0">
                                <div class="card-body">
                                    <h6 class="fw-bold">Mô tả Tour</h6>
                                    <p class="small text-muted"><?= nl2br(htmlspecialchars($tour['description'])) ?></p>
                                    <hr>
                                    <h6 class="fw-bold">Chính sách & Điều khoản</h6>
                                    <p class="small text-muted"><?= nl2br(htmlspecialchars($tour['policy'])) ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="suppliers" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Nhà cung cấp đã liên kết</h5>
                        <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                            <i class="bi bi-link-45deg me-1"></i> Thêm liên kết
                        </button>
                    </div>

                    <?php if (empty($suppliers)): ?>
                        <div class="alert alert-light border text-center">Chưa có nhà cung cấp nào được gán cho tour này.</div>
                    <?php else: ?>
                        <table class="table table-bordered align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th>Tên nhà cung cấp</th>
                                    <th>Loại dịch vụ</th>
                                    <th>Liên hệ</th>
                                    <th class="text-end">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($suppliers as $s): ?>
                                    <tr>
                                        <td class="fw-bold"><?= htmlspecialchars($s['supplier_name']) ?></td>
                                        <td><span class="badge bg-light text-dark border"><?= htmlspecialchars($s['service_type']) ?></span></td>
                                        <td class="small">
                                            <div><i class="bi bi-telephone me-1"></i> <?= htmlspecialchars($s['contact_phone']) ?></div>
                                            <div><i class="bi bi-envelope me-1"></i> <?= htmlspecialchars($s['email']) ?></div>
                                        </td>
                                        <td class="text-end">
                                            <form action="<?= BASE_URL ?>?act=admin-tours-remove-supplier" method="POST" onsubmit="return confirm('Bạn muốn gỡ nhà cung cấp này khỏi tour?')">
                                                <input type="hidden" name="tour_id" value="<?= $tour['tour_id'] ?>">
                                                <input type="hidden" name="supplier_id" value="<?= $s['supplier_id'] ?>">
                                                <button class="btn btn-sm btn-light text-danger border-0" title="Gỡ liên kết"><i class="bi bi-x-lg"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createDepartureModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="<?= BASE_URL ?>?act=admin-tours-store-departure" method="POST">
            <input type="hidden" name="tour_id" value="<?= $tour['tour_id'] ?>">
            <input type="hidden" name="duration_days" id="tourDuration" value="<?= $tour['duration_days'] ?>">
            
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-calendar-plus me-2"></i>Thêm Lịch Khởi Hành Mới</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted">Ngày khởi hành <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="startDate" class="form-control form-control-lg" required min="<?= date('Y-m-d') ?>" onchange="calcEndDate()">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted">Ngày kết thúc (Dự kiến)</label>
                            <input type="date" name="end_date" id="endDate" class="form-control form-control-lg bg-light" readonly>
                        </div>
                        <div class="col-12"><hr class="my-2 text-muted opacity-25"></div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted">Giá vé áp dụng (VNĐ)</label>
                            <div class="input-group">
                                <input type="number" name="price" class="form-control fw-bold text-primary" value="<?= $tour['price'] ?>" required>
                                <span class="input-group-text">₫</span>
                            </div>
                            <div class="form-text">Mặc định lấy theo giá gốc của tour.</div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted">Số chỗ mở bán</label>
                            <input type="number" name="max_people" class="form-control" value="<?= $tour['max_people'] ?? 20 ?>">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="btn btn-primary fw-bold px-4">Lưu & Mở bán</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="addSupplierModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="<?= BASE_URL ?>?act=admin-tours-add-supplier" method="POST">
            <input type="hidden" name="tour_id" value="<?= $tour['tour_id'] ?>">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Liên kết Nhà cung cấp</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Chọn đối tác từ danh sách</label>
                        <select name="supplier_id" class="form-select" required>
                            <option value="">-- Chọn NCC --</option>
                            <?php foreach ($allSuppliers as $sup): ?>
                                <option value="<?= $sup['supplier_id'] ?>"><?= htmlspecialchars($sup['supplier_name']) ?> (<?= $sup['service_type'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Thêm liên kết</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function calcEndDate() {
        const start = document.getElementById('startDate').value;
        const days = parseInt(document.getElementById('tourDuration').value) || 1;
        if(start) {
            const date = new Date(start);
            date.setDate(date.getDate() + days - 1);
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            document.getElementById('endDate').value = `${y}-${m}-${d}`;
        }
    }
</script>