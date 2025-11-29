<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Điều hành Tour: <?= htmlspecialchars($tour['tour_name']) ?></h1>
            <p class="text-muted mb-0 small">
                Khởi hành: <span class="text-primary fw-bold"><?= date('d/m/Y', strtotime($departure['start_date'])) ?></span>
                - Mã lịch: #<?= $departure['departure_id'] ?>
            </p>
        </div>
        <a href="<?= BASE_URL ?>?act=admin-tours-detail&id=<?= $tour['tour_id'] ?>" class="btn btn-outline-secondary shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-person-badge-fill me-2"></i>Kế hoạch & Nhân sự</h6>
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>?act=admin-departures-update-op" method="POST">
                        <input type="hidden" name="departure_id" value="<?= $departure['departure_id'] ?>">

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Giờ tập trung / Xuất phát</label>
                            <input type="time" name="start_time" class="form-control" value="<?= $departure['start_time'] ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Điểm tập trung (Đón khách)</label>
                            <input type="text" name="gathering_point" class="form-control"
                                value="<?= htmlspecialchars($departure['gathering_point'] ?? '') ?>"
                                placeholder="VD: Nhà hát lớn Hà Nội">
                        </div>

                        <hr class="my-4">

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Hướng dẫn viên (HDV)</label>
                            <select name="hdv_id" class="form-select">
                                <option value="">-- Chọn HDV --</option>
                                <?php foreach ($hdvs as $h): ?>
                                    <option value="<?= $h['user_id'] ?>" <?= ($departure['hdv_id'] == $h['user_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($h['full_name']) ?> - <?= $h['phone'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Thông tin Tài xế / Xe</label>
                            <input type="text" name="driver_info" class="form-control"
                                value="<?= htmlspecialchars($departure['driver_info'] ?? '') ?>"
                                placeholder="VD: Xe 29 chỗ - 30A.12345 - Tài xế Hùng">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">Nhân viên Hậu cần</label>
                            <input type="text" name="logistics_info" class="form-control"
                                value="<?= htmlspecialchars($departure['logistics_info'] ?? '') ?>"
                                placeholder="VD: Ms. Lan (Đặt ăn)">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary fw-bold">Lưu thay đổi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-success"><i class="bi bi-briefcase-fill me-2"></i>Phân bổ Dịch vụ</h6>
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                        <i class="bi bi-plus-lg"></i> Thêm
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-3">Loại</th>
                                    <th>Chi tiết dịch vụ</th>
                                    <th>SL</th>
                                    <th>Chi phí</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($services)): ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">Chưa đặt dịch vụ nào.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($services as $sv): ?>
                                        <tr>
                                            <td class="ps-3"><span class="badge bg-light text-dark border"><?= $sv['service_type'] ?></span></td>
                                            <td>
                                                <div class="fw-bold"><?= htmlspecialchars($sv['provider_name']) ?></div>
                                                <div class="small text-muted"><?= htmlspecialchars($sv['details']) ?></div>
                                            </td>
                                            <td><?= $sv['quantity'] ?></td>
                                            <td class="text-danger fw-bold"><?= number_format($sv['total_cost']) ?></td>
                                            <td class="text-end pe-3">
                                                <form action="<?= BASE_URL ?>?act=admin-departures-delete-service" method="POST" onsubmit="return confirm('Xóa dịch vụ này?')">
                                                    <input type="hidden" name="service_id" value="<?= $sv['service_id'] ?>">
                                                    <input type="hidden" name="departure_id" value="<?= $departure['departure_id'] ?>">
                                                    <button class="btn btn-sm text-danger border-0 bg-transparent"><i class="bi bi-trash"></i></button>
                                                </form>
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
    </div>
</div>

<div class="modal fade" id="addServiceModal" tabindex="-1">
    <div class="modal-dialog">
        <form action="<?= BASE_URL ?>?act=admin-departures-add-service" method="POST">
            <input type="hidden" name="departure_id" value="<?= $departure['departure_id'] ?>">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Đặt Dịch Vụ Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Loại dịch vụ</label>
                        <select name="service_type" class="form-select">
                            <option value="Khách sạn">Khách sạn/Lưu trú</option>
                            <option value="Vận chuyển">Xe/Vận chuyển</option>
                            <option value="Nhà hàng">Nhà hàng/Ăn uống</option>
                            <option value="Vé tham quan">Vé tham quan</option>
                            <option value="Khác">Khác</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nhà cung cấp</label>
                        <input type="text" name="provider_name" class="form-control" list="supplierList" placeholder="Nhập tên đối tác..." required>
                        <datalist id="supplierList">
                            <?php foreach ($suppliers as $s): ?>
                                <option value="<?= htmlspecialchars($s['supplier_name']) ?>">
                                <?php endforeach; ?>
                        </datalist>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Chi tiết booking</label>
                        <textarea name="details" class="form-control" rows="2" placeholder="VD: 05 phòng đôi, ăn tối set 200k..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">Số lượng</label>
                            <input type="number" name="quantity" class="form-control" value="1">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">Tổng chi phí dự kiến</label>
                            <input type="number" name="total_cost" class="form-control" value="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-success">Lưu dịch vụ</button>
                </div>
            </div>
        </form>
    </div>
</div>