<section style="max-width:420px;margin:40px auto;background:#fff;padding:20px;border-radius:12px;box-shadow:0 6px 20px rgba(15,23,42,.08)">
    <h1 style="margin-top:0;margin-bottom:12px">Đăng ký tài khoản</h1>
    <p style="margin-top:0;color:#6b7280">Tạo tài khoản mới để sử dụng hệ thống.</p>

    <?php if (!empty($error)): ?>
        <div style="color:#b91c1c;margin-bottom:12px;font-weight:700;">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div style="color:#15803d;margin-bottom:12px;font-weight:700;">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>index.php?act=register-post" method="POST">

        <div style="margin-bottom:10px">
            <label for="full_name">Họ và tên</label><br>
            <input type="text" id="full_name" name="full_name" required
                style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:8px">
        </div>

        <div style="margin-bottom:10px">
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" required
                style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:8px">
        </div>

        <div style="margin-bottom:10px">
            <label for="phone">Số điện thoại</label><br>
            <input type="text" id="phone" name="phone"
                style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:8px">
        </div>

        <div style="margin-bottom:10px">
            <label for="password">Mật khẩu</label><br>
            <input type="password" id="password" name="password" required
                style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:8px">
        </div>

        <div style="margin-bottom:10px">
            <label for="password_confirmation">Nhập lại mật khẩu</label><br>
            <input type="password" id="password_confirmation" name="password_confirmation" required
                style="width:100%;padding:8px;border:1px solid #e5e7eb;border-radius:8px">
        </div>

        <button type="submit"
            style="width:100%;padding:10px;border:0;border-radius:8px;background:#0f766e;color:#fff;font-weight:700">
            Đăng ký
        </button>
    </form>

    <p style="margin-top:12px;text-align:center;color:#6b7280">
        Đã có tài khoản?
        <a href="<?= BASE_URL ?>index.php?act=login" style="font-weight:700">Đăng nhập</a>
    </p>
</section>
