<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách đoàn - <?= htmlspecialchars($tour['tour_name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 14px; background: #fff; }
        .table thead th { background-color: #f0f0f0 !important; -webkit-print-color-adjust: exact; border-bottom: 2px solid #000; }
        .header { margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        @media print {
            .no-print { display: none !important; }
            body { padding: 0; }
            .table-bordered td, .table-bordered th { border: 1px solid #000 !important; }
        }
    </style>
</head>
<body class="p-4">

    <div class="no-print mb-3 text-end">
        <button onclick="window.print()" class="btn btn-primary fw-bold">🖨️ In Danh Sách</button>
        <button onclick="window.close()" class="btn btn-secondary">Đóng</button>
    </div>

    <div class="header text-center">
        <h2 class="fw-bold text-uppercase">DANH SÁCH KHÁCH ĐOÀN</h2>
        <h4 class="fw-bold"><?= htmlspecialchars($tour['tour_name']) ?></h4>
        <p class="mb-0">
            Ngày đi: <strong><?= date('d/m/Y', strtotime($departure['start_date'])) ?></strong> 
            - Ngày về: <strong><?= date('d/m/Y', strtotime($departure['end_date'])) ?></strong>
        </p>
        <p>HDV: <?= htmlspecialchars($departure['hdv_info'] ?? '___________________') ?> | Xe: <?= htmlspecialchars($departure['driver_info'] ?? '___________________') ?></p>
    </div>

    <table class="table table-bordered table-sm align-middle">
        <thead>
            <tr class="text-center">
                <th style="width: 40px;">STT</th>
                <th>Họ và Tên</th>
                <th style="width: 60px;">Năm sinh</th>
                <th style="width: 60px;">G.Tính</th>
                <th>SĐT Liên hệ</th>
                <th>Giấy tờ (CCCD/Passport)</th>
                <th>Ghi chú (Ăn uống/Sức khỏe)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($passengers as $i => $p): ?>
                <tr>
                    <td class="text-center"><?= $i + 1 ?></td>
                    <td class="fw-bold text-uppercase"><?= htmlspecialchars($p['full_name']) ?></td>
                    <td class="text-center"><?= !empty($p['dob']) ? date('Y', strtotime($p['dob'])) : '' ?></td>
                    <td class="text-center"><?= $p['gender'] ?></td>
                    <td><?= $p['phone'] ?></td>
                    <td><?= htmlspecialchars($p['passport_number'] ?? '') ?></td>
                    <td><?= htmlspecialchars($p['note'] ?? '') ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="row mt-4">
        <div class="col-6">
            <p><strong>Thống kê:</strong> Tổng <?= count($passengers) ?> khách.</p>
        </div>
        <div class="col-6 text-end">
            <p><em>Ngày in: <?= date('d/m/Y H:i') ?></em></p>
            <p class="mt-5"><strong>Người lập biểu</strong></p>
        </div>
    </div>

</body>
</html>