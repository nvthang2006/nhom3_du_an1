<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Báo cáo Tài chính</h1>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="<?= BASE_URL ?>" class="row align-items-end g-3">
                <input type="hidden" name="act" value="admin-reports">
                
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted">Từ ngày</label>
                    <input type="date" name="start" class="form-control" value="<?= $startDate ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold small text-muted">Đến ngày</label>
                    <input type="date" name="end" class="form-control" value="<?= $endDate ?>">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-filter"></i> Lọc dữ liệu</button>
                </div>
                <div class="col-md-3 text-end">
                    <div class="small text-muted">Lợi nhuận ròng</div>
                    <h4 class="fw-bold <?= $totals['profit'] >= 0 ? 'text-success' : 'text-danger' ?>">
                        <?= number_format($totals['profit']) ?> ₫
                    </h4>
                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm border-start border-success border-4">
                <div class="card-body">
                    <div class="text-uppercase small fw-bold text-success mb-1">Tổng Doanh Thu</div>
                    <div class="h3 mb-0 fw-bold text-gray-800"><?= number_format($totals['revenue']) ?> ₫</div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm border-start border-danger border-4">
                <div class="card-body">
                    <div class="text-uppercase small fw-bold text-danger mb-1">Tổng Chi Phí</div>
                    <div class="h3 mb-0 fw-bold text-gray-800"><?= number_format($totals['expense']) ?> ₫</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 fw-bold text-primary">Hiệu quả kinh doanh theo Tour</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>Tên Tour</th>
                            <th class="text-end">Doanh thu</th>
                            <th class="text-end">Chi phí</th>
                            <th class="text-end">Lợi nhuận</th>
                            <th class="text-end">Tỷ suất LN</th>
                            <th class="text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($stats as $row): 
                            $profit = $row['revenue'] - $row['expense'];
                            $margin = ($row['revenue'] > 0) ? round(($profit / $row['revenue']) * 100, 1) : 0;
                        ?>
                        <tr>
                            <td class="fw-bold"><?= htmlspecialchars($row['tour_name']) ?></td>
                            <td class="text-end text-success"><?= number_format($row['revenue']) ?></td>
                            <td class="text-end text-danger"><?= number_format($row['expense']) ?></td>
                            <td class="text-end fw-bold <?= $profit >= 0 ? 'text-primary' : 'text-danger' ?>">
                                <?= number_format($profit) ?>
                            </td>
                            <td class="text-end">
                                <span class="badge <?= $margin > 20 ? 'bg-success' : ($margin > 0 ? 'bg-warning' : 'bg-danger') ?>">
                                    <?= $margin ?>%
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="<?= BASE_URL ?>?act=admin-reports-expense&tour_id=<?= $row['tour_id'] ?>" 
                                   class="btn btn-sm btn-outline-secondary" title="Quản lý chi phí">
                                    <i class="bi bi-receipt"></i> Chi phí
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 fw-bold text-primary">Biểu đồ so sánh</h6>
        </div>
        <div class="card-body">
            <canvas id="financeChart" height="100"></canvas>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('financeChart').getContext('2d');
    const data = {
        labels: <?= json_encode(array_column($stats, 'tour_name')) ?>,
        datasets: [
            {
                label: 'Doanh thu',
                data: <?= json_encode(array_column($stats, 'revenue')) ?>,
                backgroundColor: 'rgba(25, 135, 84, 0.6)',
                borderColor: 'rgba(25, 135, 84, 1)',
                borderWidth: 1
            },
            {
                label: 'Lợi nhuận',
                data: <?= json_encode(array_map(fn($r) => $r['revenue'] - $r['expense'], $stats)) ?>,
                backgroundColor: 'rgba(13, 110, 253, 0.6)',
                borderColor: 'rgba(13, 110, 253, 1)',
                borderWidth: 1,
                type: 'line',
                tension: 0.3
            }
        ]
    };
    new Chart(ctx, {
        type: 'bar',
        data: data,
        options: { responsive: true, scales: { y: { beginAtZero: true } } }
    });
</script>