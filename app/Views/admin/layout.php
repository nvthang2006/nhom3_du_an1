<?php
// app/Views/admin/layout.php
if (session_status() === PHP_SESSION_NONE) session_start();
$user = null;
if (class_exists('\App\Core\Auth')) {
  $user = \App\Core\Auth::user();
}
?>
<!doctype html>
<html lang="vi">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Admin • DA1</title>

  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Optional: Bootstrap Icons CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root {
      --brand: #0d6efd;
      --sidebar-bg: #0f1724;
      --sidebar-text: #cbd5e1;
      --muted: #6b7280;
    }

    html,
    body {
      height: 100%;
    }

    body {
      background: #f4f6fb;
      color: #0f172a;
      font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
      margin: 0;
    }

    /* Layout */
    .admin-shell {
      min-height: 100vh;
      display: flex;
      flex-direction: row;
    }

    /* Sidebar */
    .admin-sidebar {
      width: 260px;
      background: linear-gradient(180deg, var(--sidebar-bg) 0%, #0b1220 100%);
      color: var(--sidebar-text);
      padding: 18px 12px;
      transition: width .2s ease;
      position: sticky;
      top: 0;
      height: 100vh;
    }

    .admin-sidebar.collapsed {
      width: 72px;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 8px 6px;
      margin-bottom: 12px;
    }

    .brand .logo {
      width: 46px;
      height: 46px;
      border-radius: 10px;
      background: linear-gradient(135deg, #0ea5a4, #06b6d4);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: 700;
      box-shadow: 0 6px 18px rgba(2, 6, 23, .25);
    }

    .brand .title {
      font-weight: 700;
      font-size: 16px;
      color: #fff;
    }

    .side-nav {
      margin-top: 12px;
    }

    .side-nav a {
      display: flex;
      align-items: center;
      gap: 12px;
      color: var(--sidebar-text);
      padding: 10px 10px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
    }

    .side-nav a:hover,
    .side-nav a.active {
      background: rgba(255, 255, 255, 0.04);
      color: #fff;
    }

    .side-nav .bi {
      font-size: 18px;
      opacity: .95;
    }

    /* Header */
    .admin-main {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    header.admin-header {
      background: #fff;
      padding: 12px 20px;
      border-bottom: 1px solid #e9eef6;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      position: sticky;
      top: 0;
      z-index: 20;
    }

    .header-left {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .toggle-btn {
      border: 0;
      background: none;
      font-size: 20px;
      color: var(--muted);
    }

    .search-input {
      min-width: 240px;
      max-width: 420px;
    }

    .user-pill {
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 6px 10px;
      border-radius: 999px;
      background: #f3f4f6;
    }

    /* Content area */
    .admin-content {
      padding: 20px;
    }

    .page-title {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 12px;
      margin-bottom: 12px;
    }

    .breadcrumb {
      background: transparent;
      padding: 0;
      margin-bottom: 0;
    }

    /* Card/box tweaks */
    .card {
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(2, 6, 23, .04);
    }

    /* Responsive: collapse sidebar on small screens */
    @media (max-width: 991px) {
      .admin-sidebar {
        position: fixed;
        left: -320px;
        width: 260px;
        z-index: 40;
      }

      .admin-sidebar.show {
        left: 0;
      }

      .admin-shell.sbs-open .admin-sidebar {
        left: 0;
      }

      .overlay {
        display: block;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.35);
        z-index: 30;
      }
    }

    @media (min-width: 992px) {
      .overlay {
        display: none;
      }
    }

    /* small helpers */
    .muted-sm {
      color: var(--muted);
      font-size: 0.92rem;
    }

    .small-muted {
      font-size: 0.9rem;
      color: var(--muted);
    }
  </style>
</head>

<body>
  <div class="admin-shell" id="adminShell">
    <!-- SIDEBAR -->
    <aside class="admin-sidebar" id="adminSidebar" role="navigation" aria-label="Sidebar">
      <div class="brand">
        <div class="logo">DA1</div>
        <div class="d-none d-md-block">
          <div class="title">Admin panel</div>
          <div class="small-muted"></div>
        </div>
      </div>

      <!-- User -->
      <div class="d-flex align-items-center gap-2 px-2 py-2 mb-3" style="border-radius:8px;">
        <div style="width:44px;height:44px;border-radius:10px;background:#e2e8f0;display:flex;align-items:center;justify-content:center;font-weight:700;color:#0f172a">
          <?= htmlspecialchars(substr($user['full_name'] ?? ($user['email'] ?? 'A'), 0, 1)) ?>
        </div>
        <div class="d-none d-md-block">
          <div class="fw-semibold"><?= htmlspecialchars($user['full_name'] ?? ($user['email'] ?? 'Admin')) ?></div>
          <div class="small-muted"><?= htmlspecialchars($user['role'] ?? '—') ?></div>
        </div>
      </div>

      <!-- Navigation -->
      <nav class="side-nav" aria-label="Main admin menu">
        <a href="<?= BASE_URL ?>?act=admin-dashboard" class="<?= ($_SERVER['REQUEST_URI'] ?? '') === ($base . '/admin') ? 'active' : '' ?>"><i class="bi bi-speedometer2"></i> <span class="d-none d-md-inline">Dashboard</span></a>
        <a href="<?= BASE_URL ?>?act=admin-tours" class="<?= (strpos($_SERVER['REQUEST_URI'], '/admin/tours') !== false) ? 'active' : '' ?>"><i class="bi bi-geo-alt"></i> <span class="d-none d-md-inline">Quản lý Tour</span></a>
        <a href="<?= BASE_URL ?>?act=admin-tours" class="<?= (strpos($_SERVER['REQUEST_URI'], '/admin/tours') !== false) ? 'active' : '' ?>"><i class="bi bi-person-circle"></i> <span class="d-none d-md-inline">Người dùng</span></a>
        <a href="<?= $base ?>/admin/bookings" class="<?= (strpos($_SERVER['REQUEST_URI'], '/admin/bookings') !== false) ? 'active' : '' ?>"><i class="bi bi-journal-check"></i> <span class="d-none d-md-inline">Đặt chỗ</span></a>
        <a href="<?= $base ?>/admin/guides" class="<?= (strpos($_SERVER['REQUEST_URI'], '/admin/guides') !== false) ? 'active' : '' ?>"><i class="bi bi-people"></i> <span class="d-none d-md-inline">Hướng dẫn viên</span></a>
        <a href="<?= $base ?>/admin/reports" class="<?= (strpos($_SERVER['REQUEST_URI'], '/admin/reports') !== false) ? 'active' : '' ?>"><i class="bi bi-bar-chart"></i> <span class="d-none d-md-inline">Báo cáo</span></a>

        <hr style="border-color: rgba(255,255,255,0.04)">

        <a href="<?= $base ?>/admin/settings"><i class="bi bi-gear"></i> <span class="d-none d-md-inline">Cấu hình</span></a>
        <a href="<?= $base ?>/index.php/logout"><i class="bi bi-box-arrow-right"></i> <span class="d-none d-md-inline">Đăng xuất</span></a>
      </nav>

      <div class="mt-auto d-none d-md-block small-muted px-2" style="margin-top:18px;">
        Version: <strong>1.0</strong><br>
        © <?= date('Y') ?> DA1
      </div>
    </aside>

    <!-- MAIN -->
    <div class="admin-main">
      <!-- Header -->
      <header class="admin-header">
        <div class="header-left">
          <button class="toggle-btn btn" id="sidebarToggle" aria-label="Toggle sidebar"><i class="bi bi-list"></i></button>
          <div class="d-none d-md-block">
            <form action="<?= $base ?>/admin/tours" method="GET" class="d-flex align-items-center">
              <input type="search" name="q" class="form-control search-input" placeholder="Tìm tour, địa điểm..." value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
            </form>
          </div>
        </div>

        <div class="d-flex align-items-center gap-3">
          <div class="user-pill d-none d-md-flex">
            <i class="bi bi-gear-fill text-muted"></i>
            <div class="small-muted">Admin</div>
          </div>
          <div class="dropdown">
            <a href="#" class="d-flex align-items-center text-decoration-none" id="userMenuBtn" data-bs-toggle="dropdown" aria-expanded="false">
              <div style="width:36px;height:36px;border-radius:8px;background:#0ea5a4;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;margin-right:8px">
                <?= htmlspecialchars(substr($user['full_name'] ?? ($user['email'] ?? 'A'), 0, 1)) ?>
              </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenuBtn">
              <li class="dropdown-header"><?= htmlspecialchars($user['full_name'] ?? ($user['email'] ?? 'Admin')) ?></li>
              <li><a class="dropdown-item" href="<?= $base ?>/admin/profile">Hồ sơ</a></li>
              <li><a class="dropdown-item" href="<?= $base ?>/admin/settings">Cài đặt</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item text-danger" href="<?= $base ?>/index.php/logout">Đăng xuất</a></li>
            </ul>
          </div>
        </div>
      </header>

      <!-- Content -->
      <section class="admin-content">
        <div class="page-title">
          <div>
            <h2 class="h5 mb-1"><?= isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Quản trị' ?></h2>
            <?php if (!empty($pageSubtitle)): ?>
              <div class="small-muted"><?= htmlspecialchars($pageSubtitle) ?></div>
            <?php endif; ?>
          </div>
          <div class="d-flex align-items-center gap-2">
            <!-- optional actions injected by views -->
            <?= $pageActions ?? '' ?>
          </div>
        </div>

        <!-- Flash messages (supports $_SESSION flash or $flash) -->
        <?php if (!empty($_SESSION['flash'])): ?>
          <div class="alert alert-info"><?= htmlspecialchars($_SESSION['flash']);
                                        unset($_SESSION['flash']); ?></div>
        <?php endif; ?>
        <?php if (!empty($flash)): ?>
          <div class="alert alert-info"><?= htmlspecialchars($flash) ?></div>
        <?php endif; ?>

        <!-- main view include -->
        <div>
          <?php
          if ($viewFile && file_exists($viewFile)) {
            require $viewFile;
          } else {
            echo '<div class="card p-4 text-muted">No view file found.</div>';
          }
          ?>
        </div>
      </section>
    </div>
  </div>

  <!-- overlay for mobile -->
  <div id="mobileOverlay" class="overlay" style="display:none"></div>

  <!-- Bootstrap JS bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    (function() {
      const shell = document.getElementById('adminShell');
      const sidebar = document.getElementById('adminSidebar');
      const toggle = document.getElementById('sidebarToggle');
      const overlay = document.getElementById('mobileOverlay');

      function isMobile() {
        return window.innerWidth < 992;
      }

      function openSidebarMobile() {
        sidebar.classList.add('show');
        overlay.style.display = 'block';
        document.body.classList.add('no-scroll');
      }

      function closeSidebarMobile() {
        sidebar.classList.remove('show');
        overlay.style.display = 'none';
        document.body.classList.remove('no-scroll');
      }

      // Toggle behavior: small screen -> slide in; large screen -> collapse to icons
      toggle.addEventListener('click', function(e) {
        if (isMobile()) {
          if (sidebar.classList.contains('show')) closeSidebarMobile();
          else openSidebarMobile();
        } else {
          sidebar.classList.toggle('collapsed');
          document.getElementById('adminSidebar').classList.toggle('collapsed');
        }
      });

      overlay.addEventListener('click', closeSidebarMobile);

      // close sidebar on resize if needed
      window.addEventListener('resize', function() {
        if (!isMobile()) {
          overlay.style.display = 'none';
          sidebar.classList.remove('show');
        } else {
          // nothing
        }
      });

    })();
  </script>
</body>

</html>