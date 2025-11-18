<?php
// app/Views/admin/dashboard.php
// expects: $stats (array), $recentBookings (array), $upcomingTours (array), $user, $base
$pageTitle = 'Bảng điều khiển';
$pageSubtitle = 'Tổng quan tình hình tour, booking và doanh thu';
$pageActions = '<a href="<?= BASE_URL ?>?admin-tours-create" class="btn btn-success btn-sm">+ Tạo tour mới</a>';
?>

<div class="container-fluid">
  <!-- Quick stats -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-sm-4 col-md-3">
      <div class="card h-100 shadow-sm">
        <div class="card-body text-center">
          <div class="text-muted small">Tổng tour</div>
          <div class="fs-3 fw-bold"><?= (int)($stats['tours_count'] ?? 0) ?></div>
        </div>
      </div>
    </div>

    <div class="col-6 col-sm-4 col-md-3">
      <div class="card h-100 shadow-sm">
        <div class="card-body text-center">
          <div class="text-muted small">Booking hôm nay</div>
          <div class="fs-3 fw-bold"><?= (int)($stats['today_bookings'] ?? 0) ?></div>
        </div>
      </div>
    </div>

    <div class="col-6 col-sm-4 col-md-3">
      <div class="card h-100 shadow-sm">
        <div class="card-body text-center">
          <div class="text-muted small">Đoàn đang khởi hành</div>
          <div class="fs-3 fw-bold"><?= (int)($stats['active_departures'] ?? 0) ?></div>
        </div>
      </div>
    </div>

    <div class="col-6 col-sm-6 col-md-3">
      <div class="card h-100 shadow-sm">
        <div class="card-body text-center">
          <div class="text-muted small">Doanh thu tháng này</div>
          <div class="fs-5 fw-bold text-success">
            <?= isset($stats['month_revenue']) ? number_format($stats['month_revenue']) . ' ₫' : '0 ₫' ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Main content row -->
  <div class="row g-3">
    <!-- Recent bookings (left) -->
    <div class="col-lg-7">
      <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between">
          <div class="fw-semibold">Booking gần đây</div>
          <small class="text-muted">Cập nhật mới nhất</small>
        </div>
        <div class="card-body p-0">
          <div class="table-responsive">
            <table class="table table-sm mb-0 align-middle">
              <thead class="table-light">
                <tr>
                  <th style="width:90px">Mã</th>
                  <th>Tour</th>
                  <th>Liên hệ</th>
                  <th style="width:140px">Trạng thái</th>
                </tr>
              </thead>
              <tbody>
                <?php if (!empty($recentBookings ?? [])): ?>
                  <?php foreach ($recentBookings as $b): ?>
                    <tr>
                      <td>#<?= htmlspecialchars($b['booking_id']) ?></td>
                      <td class="fw-semibold"><?= htmlspecialchars($b['tour_name'] ?? '-') ?></td>
                      <td><?= htmlspecialchars($b['contact_name'] ?? ($b['phone'] ?? '-')) ?></td>
                      <td>
                        <?php
                        $st = htmlspecialchars($b['status'] ?? 'Chờ');
                        $badge = 'secondary';
                        if (in_array($st, ['Hoàn tất', 'Completed'])) $badge = 'success';
                        elseif (in_array($st, ['Đã cọc', 'Paid'])) $badge = 'primary';
                        elseif (in_array($st, ['Hủy', 'Cancelled'])) $badge = 'danger';
                        ?>
                        <span class="badge bg-<?= $badge ?>"><?= $st ?></span>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="4" class="text-center text-muted py-4 small">Chưa có booking nào gần đây.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center small">
          <div class="text-muted">Hiển thị <?= count($recentBookings ?? []) ?> trong 20 bản ghi</div>
          <div>
            <a href="<?= $base ?>/admin/bookings" class="link-primary">Xem tất cả</a>
          </div>
        </div>
      </div>
    </div>

    <!-- Upcoming tours (right) -->
    <div class="col-lg-5">
      <div class="card shadow-sm h-100">
        <div class="card-header">
          <div class="d-flex align-items-center justify-content-between">
            <div class="fw-semibold">Tour sắp khởi hành</div>
            <small class="text-muted">Theo lịch</small>
          </div>
        </div>

        <div class="card-body">
          <?php if (!empty($upcomingTours ?? [])): ?>
            <ul class="list-group list-group-flush">
              <?php foreach ($upcomingTours as $t): ?>
                <li class="list-group-item d-flex gap-3 align-items-start">
                  <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start">
                      <div>
                        <div class="fw-semibold"><?= htmlspecialchars($t['tour_name'] ?? '-') ?></div>
                        <div class="small text-muted"><?= htmlspecialchars($t['tour_type'] ?? '') ?> • <?= htmlspecialchars($t['start_date'] ?? '-') ?></div>
                      </div>
                      <div class="text-end">
                        <div class="small-muted"><?= htmlspecialchars($t['joined_count'] ?? '') ?> khách</div>
                        <a href="<?= $base ?>/admin/tours/edit?id=<?= (int)($t['tour_id'] ?? 0) ?>" class="btn btn-outline-primary btn-sm mt-2">Chi tiết</a>
                      </div>
                    </div>
                  </div>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <div class="text-center text-muted small py-4">Chưa có lịch khởi hành nào sắp tới.</div>
          <?php endif; ?>
        </div>

        <div class="card-footer text-end small">
          <a href="<?= $base ?>/admin/departures" class="link-primary">Quản lý lịch khởi hành</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Optional lower row: charts / recent logs -->
  <div class="row g-3 mt-3">
    <div class="col-lg-8">
      <div class="card shadow-sm">
        <div class="card-header">
          Biểu đồ doanh thu (7 ngày)
        </div>
        <div class="card-body">
          <!-- placeholder canvas; nếu bạn muốn chart thật, controller truyền dữ liệu và thêm Chart.js -->
          <div style="min-height:120px;display:flex;align-items:center;justify-content:center;color:#8b95a7">
            (Biểu đồ doanh thu - thêm Chart.js nếu cần)
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card shadow-sm">
        <div class="card-header">
          Nhật ký hệ thống gần đây
        </div>
        <div class="card-body small text-muted">
          <?php if (!empty($logs ?? [])): ?>
            <ul class="list-unstyled mb-0">
              <?php foreach ($logs as $l): ?>
                <li class="mb-2">
                  <div class="fw-semibold"><?= htmlspecialchars($l['title'] ?? 'Log') ?></div>
                  <div class="small text-muted"><?= htmlspecialchars($l['created_at'] ?? '') ?></div>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php else: ?>
            <div class="text-center">Không có nhật ký nào.</div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>