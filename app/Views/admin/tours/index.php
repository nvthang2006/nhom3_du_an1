<?php
// app/Views/admin/tours/index.php
// Variables expected: $tours (array), optional $flash (string)
// Layout (admin/layout.php) will include this file and uses $base, $user, etc.

$pageTitle = 'Quản lý Tour';
$pageSubtitle = 'Danh sách tour và thao tác CRUD';
$pageActions = '<a href="<?= BASE_URL ?>?act=admin-tours-create" class="btn btn-success btn-sm">+ Tạo tour mới</a>';
?>

<div class="container-fluid">
    <!-- Header row (title + actions) -->
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h3 class="h5 mb-0"><?= htmlspecialchars($pageTitle) ?></h3>
            <div class="small-muted"><?= htmlspecialchars($pageSubtitle) ?></div>
        </div>
        <div><a href="<?= BASE_URL ?>?act=admin-tours-create" class="btn btn-success btn-sm">+ Tạo tour mới</a></div>
    </div>

    <?php if (!empty($flash)): ?>
        <div class="alert alert-info rounded-3"><?= htmlspecialchars($flash) ?></div>
    <?php endif; ?>

    <!-- Filter card -->
    <div class="card mb-3 shadow-sm">
        <div class="card-body">
            <form class="row g-2 align-items-center" method="get" action="<?= BASE_URL ?>?act=admin-tours">
                <div class="col-md-4">
                    <input type="text" name="q" class="form-control" placeholder="Tìm theo tên, loại..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                </div>

                <div class="col-md-3">
                    <select name="tour_type" class="form-select">
                        <option value="">Tất cả loại</option>
                        <option value="Trong nước" <?= (($_GET['tour_type'] ?? '') === 'Trong nước') ? 'selected' : '' ?>>Trong nước</option>
                        <option value="Quốc tế" <?= (($_GET['tour_type'] ?? '') === 'Quốc tế') ? 'selected' : '' ?>>Quốc tế</option>
                        <option value="Theo yêu cầu" <?= (($_GET['tour_type'] ?? '') === 'Theo yêu cầu') ? 'selected' : '' ?>>Theo yêu cầu</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <button class="btn btn-primary w-100 fw-semibold" type="submit">Tìm kiếm</button>
                </div>

                </form>
        </div>
    </div>

    <!-- Table card -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <?php if (empty($tours)): ?>
                <div class="p-4 text-center text-muted">Chưa có tour nào. <a href="<?= ($base ?? '/index.php') ?>/admin/tours/create">Tạo tour mới</a></div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-uppercase small">
                                <th style="width:60px">#</th>
                                <th>Tên tour</th>
                                <th style="width:140px">Loại</th>
                                <th style="width:120px; text-align:right">Giá</th>
                                <th style="width:110px">Ngày tạo</th>
                                <th style="width:140px">Trạng thái</th>
                                <th style="width:200px">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tours as $t):
                                $id = (int)($t['tour_id'] ?? $t['id'] ?? 0);
                                $name = htmlspecialchars($t['tour_name'] ?? '-');
                                $desc = htmlspecialchars(mb_substr($t['description'] ?? '', 0, 100));
                                $type = htmlspecialchars($t['tour_type'] ?? '-');
                                $price = number_format((float)($t['price'] ?? 0), 0, ',', '.');
                                $created = !empty($t['created_at']) ? date('Y-m-d', strtotime($t['created_at'])) : '-';
                                $status = $t['status'] ?? '';
                            ?>
                                <tr>
                                    <td><?= $id ?></td>
                                    <td>
                                        <div class="fw-semibold"><?= $name ?></div>
                                        <div class="text-muted small text-truncate" style="max-width:420px"><?= $desc ?></div>
                                    </td>
                                    <td><?= $type ?></td>
                                    <td class="text-end"><?= $price ?> ₫</td>
                                    <td><?= $created ?></td>
                                    <td>
                                        <?php if ($status === 'Hoạt động'): ?>
                                            <span class="badge bg-success">Hoạt động</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary"><?= htmlspecialchars($status ?: 'Ngừng') ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            <a href="<?= BASE_URL ?>?act=admin-tours-edit&id=<?= $id ?>" class="btn btn-outline-primary">Sửa</a>

                                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-id="<?= $id ?>" data-name="<?= htmlspecialchars($t['tour_name'] ?? '', ENT_QUOTES) ?>">Xóa</button>


                                            <a href="<?= BASE_URL ?>?act=admin-tours-detail&id=<?= $id ?>" class="btn btn-outline-success">Xem</a>

                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>

        <!-- Footer with paging -->
        <div class="card-footer d-flex justify-content-between align-items-center">
            <div class="text-muted small">Hiển thị <?= count($tours ?? []) ?> kết quả</div>
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><a class="page-link" href="#">«</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">»</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Delete confirmation modal (same markup but moved here for layout) -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="deleteForm" method="post" action="<?= BASE_URL ?>?act=admin-tours-delete">
            <input type="hidden" name="id" id="deleteTourId" value="">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Xác nhận xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có chắc muốn xóa tour <strong id="deleteTourName"></strong> không? Hành động này không thể hoàn tác.</p>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="1" id="deleteWithBookings" name="force">
                        <label class="form-check-label" for="deleteWithBookings">Nếu tour có booking, xóa luôn (cẩn thận)</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Huỷ</button>
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Script: modal handling (Bootstrap bundle already loaded in layout) -->
<script>
    (function() {
        var deleteModal = document.getElementById('deleteModal');
        if (!deleteModal) return;
        deleteModal.addEventListener('show.bs.modal', function(event) {
            var button = event.relatedTarget;
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');
            document.getElementById('deleteTourId').value = id;
            document.getElementById('deleteTourName').textContent = name;
        });
    })();
</script>