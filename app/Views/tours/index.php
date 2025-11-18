<?php
// app/Views/admin/tours/index.php
// expects: $tours (array), optional $error, $success
?>
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Quản lý Tour</h2>
        <div>
            <a href="/index.php/admin/tours/create" class="btn btn-success">+ Tạo tour mới</a>
        </div>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <!-- Search / filter -->
    <form class="row g-2 mb-3" method="GET" action="/index.php/admin/tours">
        <div class="col-auto">
            <input type="search" name="q" class="form-control" placeholder="Tìm theo tên, địa điểm..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        </div>
        <div class="col-auto">
            <select name="tour_type" class="form-select">
                <option value="">Tất cả loại</option>
                <option value="Trong nước" <?= (($_GET['tour_type'] ?? '') === 'Trong nước') ? 'selected' : '' ?>>Trong nước</option>
                <option value="Quốc tế" <?= (($_GET['tour_type'] ?? '') === 'Quốc tế') ? 'selected' : '' ?>>Quốc tế</option>
                <option value="Theo yêu cầu" <?= (($_GET['tour_type'] ?? '') === 'Theo yêu cầu') ? 'selected' : '' ?>>Theo yêu cầu</option>
            </select>
        </div>
        <div class="col-auto">
            <button class="btn btn-primary">Tìm</button>
            <a href="/index.php/admin/tours" class="btn btn-outline-secondary">Làm lại</a>
        </div>
    </form>

    <?php if (empty($tours)): ?>
        <div class="alert alert-info">Chưa có tour nào.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width:60px">#</th>
                        <th>Tên tour</th>
                        <th>Loại</th>
                        <th style="width:120px;text-align:right">Giá (VND)</th>
                        <th style="width:110px">Ngày tạo</th>
                        <th style="width:220px">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tours as $t):
                        $id = $t['tour_id'] ?? ($t['id'] ?? '');
                        $name = htmlspecialchars($t['tour_name'] ?? '—');
                        $type = htmlspecialchars($t['tour_type'] ?? '—');
                        $price = isset($t['price']) ? number_format($t['price'], 0, ',', '.') : 'Liên hệ';
                        $created = $t['created_at'] ?? '';
                    ?>
                        <tr>
                            <td><?= $id ?></td>
                            <td><?= $name ?></td>
                            <td><?= $type ?></td>
                            <td style="text-align:right"><?= $price ?></td>
                            <td><?= $created ?></td>
                            <td>
                                <a href="/index.php/tours/show?id=<?= $id ?>" class="btn btn-sm btn-outline-primary">Xem</a>
                                <a href="<?= BASE_URL ?>?act=admin-tours-edit&id=<?= $id ?>" class="btn btn-outline-primary">Sửa</a>

                                <!-- Delete form -->
                                <form method="post" action="/index.php/admin/tours/delete" style="display:inline-block" onsubmit="return confirm('Bạn có chắc muốn xóa tour này?');">
                                    <input type="hidden" name="id" value="<?= $id ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- OPTIONAL: pagination placeholder (controller can provide $page, $totalPages) -->
        <?php if (isset($page) && isset($totalPages) && $totalPages > 1): ?>
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                        <li class="page-item <?= ($p == $page) ? 'active' : '' ?>">
                            <a class="page-link" href="/index.php/admin/tours?page=<?= $p ?>"><?= $p ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>
        <?php endif; ?>

    <?php endif; ?>
</div>

<!-- small inline style to ensure Bootstrap look if layout doesn't include it -->
<style>
    .btn-sm {
        padding: .375rem .6rem;
        font-weight: 600;
    }
</style>