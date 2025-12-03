<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0 text-gray-800">Quản lý Đội ngũ Hướng dẫn viên</h3>
        <a href="<?= BASE_URL ?>?act=admin-hdv-create" class="btn btn-success shadow-sm">
            <i class="bi bi-person-plus-fill me-1"></i> Thêm HDV Mới
        </a>
    </div>

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i> <?= $_SESSION['flash']; unset($_SESSION['flash']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th>Họ tên / Email</th>
                        <th>Phân loại & Ngôn ngữ</th>
                        <th>Kinh nghiệm</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($hdvs)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Chưa có hướng dẫn viên nào.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($hdvs as $h): ?>
                            <?php 
                                // Link ảnh mặc định (Placeholder chuyên nghiệp hơn)
                                $defaultAvatar = 'https://t4.ftcdn.net/jpg/05/49/98/39/360_F_549983970_bRCkYfk0P6PP5fveM071eIJ3I4d5k435.jpg';
                                
                                // Xử lý đường dẫn ảnh
                                $avatarSrc = !empty($h['avatar']) ? BASE_URL . $h['avatar'] : $defaultAvatar;
                            ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?= $avatarSrc ?>" 
                                             class="rounded-circle me-2 border" 
                                             style="width: 40px; height: 40px; object-fit: cover;"
                                             onerror="this.onerror=null; this.src='<?= $defaultAvatar ?>';"
                                             alt="Avatar">
                                        <div>
                                            <div class="fw-bold text-dark"><?= htmlspecialchars($h['full_name']) ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($h['email']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="mb-1">
                                        <?php
                                        $cls = $h['classification'] ?? 'Nội địa';
                                        $badgeColor = match ($cls) {
                                            'Quốc tế' => 'bg-danger',
                                            'Chuyên tuyến' => 'bg-success',
                                            'Chuyên khách đoàn' => 'bg-warning text-dark',
                                            'Freelancer' => 'bg-info text-dark',
                                            default => 'bg-primary'
                                        };
                                        ?>
                                        <span class="badge <?= $badgeColor ?>"><?= htmlspecialchars($cls) ?></span>
                                    </div>
                                    <div>
                                        <span class="badge bg-light text-dark border">
                                            <?= htmlspecialchars($h['languages'] ?? 'Chưa cập nhật') ?>
                                        </span>
                                    </div>
                                </td>
                                <td><?= $h['experience_years'] ?> năm</td>
                                <td>
                                    <a href="<?= BASE_URL ?>?act=admin-hdv-edit&id=<?= $h['user_id'] ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-square me-1"></i> Hồ sơ
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>