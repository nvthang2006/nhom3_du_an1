<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Chi tiết Tour: <?= htmlspecialchars($tour['tour_name']) ?></h1>
            <p class="text-muted mb-0 small">Quản lý lịch trình, điều hành và đối tác</p>
        </div>
        <div>
            <button type="button" class="btn btn-primary shadow-sm fw-bold" data-bs-toggle="modal" data-bs-target="#createDepartureModal">
                <i class="bi bi-calendar-plus me-1"></i> Tạo Lịch Khởi Hành
            </button>
            <a href="<?= BASE_URL ?>?act=admin-tours" class="btn btn-outline-secondary shadow-sm ms-2">
                <i class="bi bi-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert alert-success border-0 shadow-sm border-start border-success border-4">
            <i class="bi bi-check-circle-fill me-2"></i><?= $_SESSION['flash']; unset($_SESSION['flash']); ?>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom-0 pb-0">
            <ul class="nav nav-tabs card-header-tabs" id="tourTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold text-primary" id="upcoming-tab" data-bs-toggle="tab" data-bs-target="#upcoming" type="button" role="tab">
                        <i class="bi bi-clock-history me-2"></i>Sắp khởi hành (<?= count($upcomingDepartures) ?>)
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-secondary" id="history-tab" data-bs-toggle="tab" data-bs-target="#history" type="button" role="tab">
                        <i class="bi bi-archive me-2"></i>Lịch sử / Đã hoàn tất (<?= count($historyDepartures) ?>)
                    </button>
                </li>
            </ul>
        </div>
        
        <div class="card-body">
            <div class="tab-content" id="tourTabContent">
                
                <div class="tab-pane fade show active" id="upcoming" role="tabpanel">
                    <?php if (empty($upcomingDepartures)): ?>
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x text-muted opacity-50" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2">Chưa có lịch khởi hành sắp tới.</p>
                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#createDepartureModal">
                                Tạo ngay lịch mới
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Ngày khởi hành</th>
                                        <th>Ngày về (Dự kiến)</th>
                                        <th>Giá bán</th>
                                        <th>Chỗ (Đã đặt/Tổng)</th>
                                        <th>Trạng thái</th>
                                        <th class="text-end">Điều hành</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($upcomingDepartures as $dep): ?>
                                        <tr>
                                            <td>
                                                <div class="fw-bold text-primary fs-5"><?= date('d/m/Y', strtotime($dep['start_date'])) ?></div>
                                                <small class="text-muted">#<?= $dep['departure_id'] ?></small>
                                            </td>
                                            <td><?= date('d/m/Y', strtotime($dep['end_date'])) ?></td>
                                            <td class="fw-bold"><?= number_format($dep['price']) ?> ₫</td>
                                            <td>
                                                <div class="d-flex align-items-center" style="width: 150px;">
                                                    <div class="flex-grow-1">
                                                        <div class="progress" style="height: 6px;">
                                                            <?php $percent = ($dep['booked_count'] / $dep['max_people']) * 100; ?>
                                                            <div class="progress-bar bg-<?= $percent > 90 ? 'danger' : 'success' ?>" style="width: <?= $percent ?>%"></div>
                                                        </div>
                                                        <small class="text-muted"><?= $dep['booked_count'] ?> / <?= $dep['max_people'] ?> khách</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                    <?= $dep['status'] ?>
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <a href="<?= BASE_URL ?>?act=admin-departures-manage&id=<?= $dep['departure_id'] ?>" 
                                                   class="btn btn-primary btn-sm">
                                                    <i class="bi bi-gear-fill me-1"></i> Quản lý
                                                </a>
                                                <?php if($dep['booked_count'] == 0): ?>
                                                    <form action="<?= BASE_URL ?>?act=admin-tours-delete-departure" method="POST" class="d-inline ms-1" onsubmit="return confirm('Xóa lịch này?')">
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
                        </div>
                    <?php endif; ?>
                </div>

                <div class="tab-pane fade" id="history" role="tabpanel">
                    <?php if (empty($historyDepartures)): ?>
                        <div class="text-center py-4 text-muted">Chưa có dữ liệu lịch sử.</div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0 text-muted">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Ngày đi</th>
                                        <th>Ngày về</th>
                                        <th>Khách</th>
                                        <th>Trạng thái</th>
                                        <th class="text-end">Xem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($historyDepartures as $dep): ?>
                                        <tr>
                                            <td><?= date('d/m/Y', strtotime($dep['start_date'])) ?></td>
                                            <td><?= date('d/m/Y', strtotime($dep['end_date'])) ?></td>
                                            <td><?= $dep['booked_count'] ?></td>
                                            <td>
                                                <?php if($dep['status'] == 'Hoàn tất'): ?>
                                                    <span class="badge bg-secondary">Đã xong</span>
                                                <?php elseif($dep['status'] == 'Hủy'): ?>
                                                    <span class="badge bg-danger">Đã hủy</span>
                                                <?php else: ?>
                                                    <span class="badge bg-light text-dark border"><?= $dep['status'] ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end">
                                                <a href="<?= BASE_URL ?>?act=admin-departures-manage&id=<?= $dep['departure_id'] ?>" 
                                                   class="btn btn-sm btn-light border">
                                                    Chi tiết
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-info"><i class="bi bi-building me-2"></i>Nhà cung cấp liên kết</h5>
            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                <i class="bi bi-plus-circle"></i> Thêm NCC
            </button>
        </div>
        <div class="card-body">
            <?php if (empty($suppliers)): ?>
                <div class="text-center py-3 text-muted">Chưa có NCC nào.</div>
            <?php else: ?>
                <table class="table table-bordered align-middle">
                    <tbody>
                        <?php foreach ($suppliers as $s): ?>
                            <tr>
                                <td><?= htmlspecialchars($s['supplier_name']) ?></td>
                                <td><?= htmlspecialchars($s['service_type']) ?></td>
                                <td class="text-end">
                                    <form action="<?= BASE_URL ?>?act=admin-tours-remove-supplier" method="POST" onsubmit="return confirm('Gỡ?')">
                                        <input type="hidden" name="tour_id" value="<?= $tour['tour_id'] ?>">
                                        <input type="hidden" name="supplier_id" value="<?= $s['supplier_id'] ?>">
                                        <button class="btn btn-sm btn-light text-danger"><i class="bi bi-x"></i></button>
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

<div class="modal fade" id="createDepartureModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form action="<?= BASE_URL ?>?act=admin-tours-store-departure" method="POST">
            <input type="hidden" name="tour_id" value="<?= $tour['tour_id'] ?>">
            <input type="hidden" name="duration_days" id="tourDuration" value="<?= $tour['duration_days'] ?>">
            
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold"><i class="bi bi-calendar-plus"></i> Lên lịch khởi hành mới</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Ngày đi <span class="text-danger">*</span></label>
                            <input type="date" name="start_date" id="startDate" class="form-control" required min="<?= date('Y-m-d') ?>" onchange="calcEndDate()">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Ngày về (Dự kiến)</label>
                            <input type="date" name="end_date" id="endDate" class="form-control bg-light" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Giá vé (VNĐ)</label>
                            <input type="number" name="price" class="form-control fw-bold text-primary" value="<?= $tour['price'] ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Số chỗ tối đa</label>
                            <input type="number" name="max_people" class="form-control" value="<?= $tour['max_people'] ?? 20 ?>">
                        </div>
                        
                        <div class="col-12"><hr class="text-muted"></div>
                        <div class="col-md-6">
                             <label class="form-label small text-muted fw-bold">Giờ đón (Tùy chọn)</label>
                            <input type="time" name="start_time" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted fw-bold">Điểm tập trung</label>
                            <input type="text" name="gathering_point" class="form-control" placeholder="VD: Sân bay Nội Bài...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-primary fw-bold">Lưu & Mở bán</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="addSupplierModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= BASE_URL ?>?act=admin-tours-add-supplier" method="POST">
            <input type="hidden" name="tour_id" value="<?= $tour['tour_id'] ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Thêm nhà cung cấp</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <select name="supplier_id" class="form-select" required>
                        <option value="">-- Chọn NCC --</option>
                        <?php foreach ($allSuppliers as $sup): ?>
                            <option value="<?= $sup['supplier_id'] ?>"><?= htmlspecialchars($sup['supplier_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Thêm</button>
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
            // Format YYYY-MM-DD
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            document.getElementById('endDate').value = `${y}-${m}-${d}`;
        }
    }
</script>