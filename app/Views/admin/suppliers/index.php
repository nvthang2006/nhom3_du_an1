<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý Đối tác & Nhà cung cấp</h1>
        <a href="<?= BASE_URL ?>?act=admin-suppliers-create" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> Thêm mới
        </a>
    </div>

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert alert-success"><?= $_SESSION['flash'];
                                            unset($_SESSION['flash']); ?></div>
    <?php endif; ?>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Tên Nhà cung cấp</th>
                        <th>Loại dịch vụ</th>
                        <th>Liên hệ (SĐT / Email)</th>
                        <th>Địa chỉ</th>
                        <th class="text-end pe-4">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($suppliers)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-4">Chưa có dữ liệu</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($suppliers as $s): ?>
                            <tr>
                                <td class="ps-4 fw-bold"><?= htmlspecialchars($s['supplier_name']) ?></td>
                                <td>
                                    <span class="badge bg-light text-dark border"><?= htmlspecialchars($s['service_type']) ?></span>
                                </td>
                                <td>
                                    <div><?= htmlspecialchars($s['contact_phone']) ?></div>
                                    <small class="text-muted"><?= htmlspecialchars($s['email']) ?></small>
                                </td>
                                <td class="text-muted small"><?= htmlspecialchars($s['address']) ?></td>
                                <td class="text-end pe-4">
                                    <a href="<?= BASE_URL ?>?act=admin-suppliers-edit&id=<?= $s['supplier_id'] ?>" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="<?= BASE_URL ?>?act=admin-suppliers-delete" method="POST" class="d-inline" onsubmit="return confirm('Xóa đối tác này?');">
                                        <input type="hidden" name="id" value="<?= $s['supplier_id'] ?>">
                                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
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