<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?= $title ?></h1>
        <a href="<?= BASE_URL ?>?act=admin-suppliers" class="btn btn-secondary">Quay lại</a>
    </div>

    <div class="card shadow-sm border-0" style="max-width: 800px; margin: 0 auto;">
        <div class="card-body">
            <?php
            // Kiểm tra xem là form Thêm hay Sửa
            $isEdit = isset($supplier);
            $action = $isEdit ? '?act=admin-suppliers-update' : '?act=admin-suppliers-store';
            ?>
            <form action="<?= BASE_URL . $action ?>" method="POST">
                <?php if ($isEdit): ?>
                    <input type="hidden" name="supplier_id" value="<?= $supplier['supplier_id'] ?>">
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label fw-bold">Tên Nhà cung cấp <span class="text-danger">*</span></label>
                    <input type="text" name="supplier_name" class="form-control" required
                        value="<?= $isEdit ? htmlspecialchars($supplier['supplier_name']) : '' ?>"
                        placeholder="VD: Khách sạn Mường Thanh">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Loại dịch vụ</label>
                        <select name="service_type" class="form-select">
                            <?php
                            $types = ['Khách sạn', 'Nhà hàng', 'Vận chuyển', 'Vé tham quan', 'Khác'];
                            $current = $isEdit ? $supplier['service_type'] : '';
                            foreach ($types as $t) {
                                $sel = ($current == $t) ? 'selected' : '';
                                echo "<option value='$t' $sel>$t</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Số điện thoại</label>
                        <input type="text" name="contact_phone" class="form-control"
                            value="<?= $isEdit ? htmlspecialchars($supplier['contact_phone']) : '' ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Email liên hệ</label>
                    <input type="email" name="email" class="form-control"
                        value="<?= $isEdit ? htmlspecialchars($supplier['email']) : '' ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Địa chỉ</label>
                    <textarea name="address" class="form-control" rows="2"><?= $isEdit ? htmlspecialchars($supplier['address']) : '' ?></textarea>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary fw-bold">
                        <i class="bi bi-save me-1"></i> Lưu thông tin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>