<div class="container-fluid p-4">
    <div class="d-flex justify-content-between mb-3">
        <h3>Hồ sơ: <?= htmlspecialchars($hdv['full_name']) ?></h3>
        <a href="<?= BASE_URL ?>?act=admin-hdv" class="btn btn-secondary">Quay lại</a>
    </div>

    <form method="post" action="<?= BASE_URL ?>?act=admin-hdv-update" enctype="multipart/form-data">
        <input type="hidden" name="user_id" value="<?= $hdv['user_id'] ?>">
        <input type="hidden" name="old_avatar" value="<?= $hdv['avatar'] ?>">

        <div class="row">
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body text-center">
                        <img src="<?= !empty($hdv['avatar']) ? BASE_URL . $hdv['avatar'] : 'https://sf-static.upanhlaylink.com/img/image_202511277a136b95cd3247b106f567482db76471.jpg' ?>" class="img-thumbnail mb-3" style="width:150px;height:150px;object-fit:cover">
                        <input type="file" name="avatar" class="form-control mb-2">
                        <div class="fw-bold"><?= $hdv['email'] ?></div>
                        <div><?= $hdv['phone'] ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-white fw-bold text-primary">Thông tin chuyên môn</div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Ngày sinh</label>
                                <input type="date" name="date_of_birth" class="form-control" value="<?= $hdv['date_of_birth'] ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Số năm kinh nghiệm</label>
                                <input type="number" name="experience_years" class="form-control" value="<?= $hdv['experience_years'] ?>">
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold text-primary">Phân loại HDV</label>
                                <select name="classification" class="form-select">
                                    <?php
                                    $types = ['Nội địa', 'Quốc tế', 'Chuyên tuyến', 'Chuyên khách đoàn', 'Freelancer'];
                                    $currentType = $hdv['classification'] ?? 'Nội địa';
                                    foreach ($types as $type):
                                    ?>
                                        <option value="<?= $type ?>" <?= $currentType == $type ? 'selected' : '' ?>>
                                            <?= $type ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Ngôn ngữ (VD: Anh, Pháp, Trung)</label>
                                <input type="text" name="languages" class="form-control" value="<?= htmlspecialchars($hdv['languages'] ?? '') ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Chứng chỉ / Thẻ HDV</label>
                                <textarea name="certificate" class="form-control" rows="3"><?= htmlspecialchars($hdv['certificate'] ?? '') ?></textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Tình trạng sức khỏe</label>
                                <input type="text" name="health_status" class="form-control" value="<?= htmlspecialchars($hdv['health_status'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="mt-3 text-end">
                            <button type="submit" class="btn btn-success px-4">Lưu hồ sơ</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>