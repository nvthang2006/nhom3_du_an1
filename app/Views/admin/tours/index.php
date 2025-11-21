<?php
// app/Views/admin/tours/index.php
// Variables expected: $tours (array), optional $flash (string)
?>

<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Quản lý Tour</h1>
            <p class="text-muted mb-0 small">Danh sách và quản lý các tour du lịch</p>
        </div>
        <a href="<?= BASE_URL ?>?act=admin-tours-create" class="btn btn-primary shadow-sm">
            <i class="bi bi-plus-circle me-1"></i> Tạo Tour mới
        </a>
    </div>

    <?php if (!empty($flash)): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 border-start border-success border-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($flash) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form class="row g-3" method="get" action="<?= BASE_URL ?>">
                <input type="hidden" name="act" value="admin-tours">

                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="q" class="form-control border-start-0 ps-0 bg-light"
                            placeholder="Tìm theo tên tour..."
                            value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                    </div>
                </div>

                <div class="col-md-3">
                    <select name="tour_type" class="form-select bg-light cursor-pointer">
                        <option value="">-- Tất cả loại --</option>
                        <option value="Trong nước" <?= (($_GET['tour_type'] ?? '') === 'Trong nước') ? 'selected' : '' ?>>Trong nước</option>
                        <option value="Quốc tế" <?= (($_GET['tour_type'] ?? '') === 'Quốc tế') ? 'selected' : '' ?>>Quốc tế</option>
                        <option value="Theo yêu cầu" <?= (($_GET['tour_type'] ?? '') === 'Theo yêu cầu') ? 'selected' : '' ?>>Theo yêu cầu</option>
                    </select>
                </div>

                <div class="col-md-4 d-flex gap-2">
                    <button class="btn btn-primary flex-grow-1" type="submit">Lọc dữ liệu</button>
                    <a href="<?= BASE_URL ?>?act=admin-tours" class="btn btn-light" title="Làm mới" data-bs-toggle="tooltip">
                        <i class="bi bi-arrow-counterclockwise"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <?php if (empty($tours)): ?>
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="bi bi-inbox text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                    </div>
                    <h5 class="text-muted">Không tìm thấy tour nào</h5>
                    <p class="text-muted small mb-3">Hãy thử thay đổi bộ lọc hoặc tạo tour mới.</p>
                    <a href="<?= BASE_URL ?>?act=admin-tours-create" class="btn btn-sm btn-outline-primary">
                        Tạo tour ngay
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th class="ps-4" style="width:60px;">ID</th>
                                <th>Thông tin Tour</th>
                                <th style="width:120px;">Loại</th>
                                <th style="width:130px;">Giá</th>
                                <th style="width:150px;">Trạng thái</th>
                                <th style="width:110px;">Ngày tạo</th>
                                <th style="width:140px;" class="text-end pe-4">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tours as $t):
                                $id = (int)($t['tour_id'] ?? $t['id'] ?? 0);
                                $name = htmlspecialchars($t['tour_name'] ?? '-');
                                $desc = htmlspecialchars(mb_substr($t['description'] ?? '', 0, 80));
                                $type = htmlspecialchars($t['tour_type'] ?? '-');
                                $price = number_format((float)($t['price'] ?? 0), 0, ',', '.');
                                $created = !empty($t['created_at']) ? date('d/m/Y', strtotime($t['created_at'])) : '-';
                                $status = $t['status'] ?? '';
                            ?>
                                <tr>
                                    <td class="ps-4 text-muted fw-bold">#<?= $id ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="rounded bg-primary-subtle d-flex align-items-center justify-content-center text-primary me-3"
                                                style="width: 40px; height: 40px; font-size: 1.2rem;">
                                                <i class="bi bi-map"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark mb-1"><?= $name ?></div>
                                                <?php if ($desc): ?>
                                                    <div class="text-muted small text-truncate" style="max-width: 250px;" title="<?= htmlspecialchars($t['description'] ?? '') ?>">
                                                        <?= $desc ?>...
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark border"><?= $type ?></span>
                                    </td>
                                    <td class="fw-bold text-primary">
                                        <?= $price ?> <small>₫</small>
                                    </td>
                                    <td>
                                        <?php if ($status === 'Hoạt động'): ?>
                                            <span class="badge rounded-pill bg-success-subtle text-success border border-success-subtle px-3">
                                                <span class="d-inline-block bg-success rounded-circle me-1" style="width:6px; height:6px;"></span> Hoạt động
                                            </span>
                                        <?php elseif ($status === 'Tạm dừng'): ?>
                                            <span class="badge rounded-pill bg-warning-subtle text-warning border border-warning-subtle px-3">
                                                <i class="bi bi-pause-fill me-1"></i> Tạm dừng
                                            </span>
                                        <?php else: ?>
                                            <span class="badge rounded-pill bg-secondary-subtle text-secondary border border-secondary-subtle px-3">
                                                Ngừng bán
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-muted small">
                                        <i class="bi bi-calendar3 me-1"></i> <?= $created ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="btn-group">
                                            <a href="<?= BASE_URL ?>?act=admin-tours-detail&id=<?= $id ?>"
                                                class="btn btn-sm btn-light text-primary"
                                                title="Xem chi tiết" data-bs-toggle="tooltip">
                                                <i class="bi bi-eye"></i>
                                            </a>

                                            <a href="<?= BASE_URL ?>?act=admin-tours-edit&id=<?= $id ?>"
                                                class="btn btn-sm btn-light text-warning"
                                                title="Chỉnh sửa" data-bs-toggle="tooltip">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>

                                            <button type="button"
                                                class="btn btn-sm btn-light text-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal"
                                                data-id="<?= $id ?>"
                                                data-name="<?= htmlspecialchars($name, ENT_QUOTES) ?>"
                                                title="Xóa" data-bs-toggle="tooltip">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($tours) && count($tours) > 10): ?>
            <div class="card-footer bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <div class="small text-muted">Hiển thị <?= count($tours) ?> kết quả</div>
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled"><a class="page-link" href="#">Trước</a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">Sau</a></li>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="deleteForm" method="post" action="<?= BASE_URL ?>?act=admin-tours-delete">
            <input type="hidden" name="id" id="deleteTourId" value="">

            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center pt-0 pb-4">
                    <div class="mb-3 text-danger">
                        <i class="bi bi-exclamation-circle display-1"></i>
                    </div>
                    <h4 class="modal-title mb-2">Xác nhận xóa?</h4>
                    <p class="text-muted mb-4">
                        Bạn có chắc chắn muốn xóa tour <strong id="deleteTourName" class="text-dark"></strong>?<br>
                        Hành động này không thể hoàn tác.
                    </p>

                    <div class="alert alert-warning d-inline-block text-start p-2 small mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="deleteWithBookings" name="force">
                            <label class="form-check-label fw-semibold" for="deleteWithBookings">
                                Xóa cả các Booking liên quan (Nguy hiểm)
                            </label>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Hủy bỏ</button>
                        <button type="submit" class="btn btn-danger px-4">Xóa ngay</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Kích hoạt Tooltips (nếu dùng Bootstrap 5)
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // 2. Xử lý truyền dữ liệu vào Modal Xóa
        var deleteModal = document.getElementById('deleteModal');
        if (deleteModal) {
            deleteModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var name = button.getAttribute('data-name');

                document.getElementById('deleteTourId').value = id;
                document.getElementById('deleteTourName').textContent = name;

                // Reset checkbox
                document.getElementById('deleteWithBookings').checked = false;
            });
        }
    });
</script>