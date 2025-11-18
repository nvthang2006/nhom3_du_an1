<section style="max-width:420px;margin:40px auto;background:#fff;padding:20px;border-radius:12px;box-shadow:0 6px 20px rgba(15,23,42,.08)">
  <h1 style="margin-top:0;margin-bottom:12px">Đăng nhập</h1>
  <p style="margin-top:0;color:#6b7280">Đăng nhập để quản trị hệ thống tour.</p>
<?php if(!empty($error)): ?>
    <div style="color:#b91c1c;margin-bottom:12px;font-weight:700;">
      <?= htmlspecialchars($error) ?>
    </div>
  <?php endif; ?>
  <form action="<?= BASE_URL ?>?act=login-post" method="POST">
    <div style="margin-bottom:10px">
      <label for="email">Email</label><br>
      <input type="email" id="email" name="email" required
        style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:8px">
    </div>
    <div style="margin-bottom:10px">
      <label for="password">Mật khẩu</label><br>
      <input type="password" id="password" name="password" required
        style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:8px">
    </div>
    <button type="submit"
      style="width:100%;padding:10px;border:0;border-radius:8px;background:#0f766e;color:#fff;font-weight:700">
      Đăng nhập
    </button>
    <p style="margin-top:12px;text-align:center;color:#6b7280">
      Chưa có tài khoản?
      <a href="<?= BASE_URL ?>?act=register" style="font-weight:700">Đăng ký ngay</a>
    </p>


  </form>
</section>