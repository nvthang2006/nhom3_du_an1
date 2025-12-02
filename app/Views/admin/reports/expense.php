<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý Chi Phí: <?= htmlspecialchars($tour['tour_name']) ?></h1>
        <a href="<?= BASE_URL ?>?act=admin-reports" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại báo cáo
        </a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">Thêm khoản chi mới</h6>
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>?act=admin-reports-store" method="POST">
                        <input type="hidden" name="tour_id" value="<?= $tour['tour_id'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label">Tên khoản chi <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" required placeholder="VD: Thuê xe 29 chỗ">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Số tiền (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" name="amount" class="form-control" required min="0">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ngày chi</label>
                            <input type="date" name="expense_date" class="form-control" required value="<?= date('Y-m-d') ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="note" class="form-control" rows="2"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Lưu khoản chi</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold text-primary">Lịch sử chi phí</h6>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Ngày</th>
                                <th>Nội dung</th>
                                <th class="text-end">Số tiền</th>
                                <th class="text-end pe-4">Xóa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0;
                            foreach ($expenses as $e): 
                                $total += $e['amount'];
                            ?>
                            <tr>
                                <td class="ps-4"><?= date('d/m/Y', strtotime($e['expense_date'])) ?></td>
                                <td>
                                    <?= htmlspecialchars($e['title']) ?>
                                    <div class="small text-muted"><?= htmlspecialchars($e['note']) ?></div>
                                </td>
                                <td class="text-end fw-bold"><?= number_format($e['amount']) ?> ₫</td>
                                <td class="text-end pe-4">
                                    <form action="<?= BASE_URL ?>?act=admin-reports-delete" method="POST" onsubmit="return confirm('Chắc chắn xóa?')">
                                        <input type="hidden" name="expense_id" value="<?= $e['expense_id'] ?>">
                                        <input type="hidden" name="tour_id" value="<?= $tour['tour_id'] ?>">
                                        <button class="btn btn-sm btn-outline-danger border-0"><i class="bi bi-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            
                            <?php if(empty($expenses)): ?>
                                <tr><td colspan="4" class="text-center py-4 text-muted">Chưa có dữ liệu chi phí</td></tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot class="bg-light">
                            <tr>
                                <td colspan="2" class="text-end fw-bold">Tổng chi phí:</td>
                                <td class="text-end fw-bold text-danger"><?= number_format($total) ?> ₫</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>