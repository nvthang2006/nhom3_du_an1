<div class="container-fluid p-4">
    <h3 class="mb-4">Quản lý Đội ngũ Hướng dẫn viên</h3>
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
                    <?php foreach ($hdvs as $h): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="<?= !empty($h['avatar']) ? BASE_URL . $h['avatar'] : 'https://www.galeriemichael.com/wp-content/uploads/2024/03/anh-anime-chibi_7.jpeg' ?>" class="rounded-circle me-2" width="40" height="40">
                                    <div>
                                        <div class="fw-bold"><?= htmlspecialchars($h['full_name']) ?></div>
                                        <small class="text-muted"><?= $h['email'] ?></small>
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
                                <a href="<?= BASE_URL ?>?act=admin-hdv-edit&id=<?= $h['user_id'] ?>" class="btn btn-sm btn-primary">Hồ sơ chi tiết</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>