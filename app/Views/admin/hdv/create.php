<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Thêm mới Hướng dẫn viên</h1>
            <p class="text-muted mb-0 small">Tạo tài khoản và hồ sơ chuyên môn cho HDV</p>
        </div>
        <a href="<?= BASE_URL ?>?act=admin-hdv" class="btn btn-outline-secondary shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form method="post" action="<?= BASE_URL ?>?act=admin-hdv-store" enctype="multipart/form-data" class="needs-validation">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-primary text-white py-3">
                        <h6 class="m-0 fw-bold"><i class="bi bi-person-lock me-2"></i>Thông tin Tài khoản</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 100px; height: 100px;">
                                <i class="bi bi-camera text-secondary fs-1"></i>
                            </div>
                            <div class="small text-muted">Ảnh đại diện</div>
                            <input type="file" name="avatar" class="form-control form-control-sm mt-2" accept="image/*">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" name="full_name" class="form-control" required placeholder="Nguyễn Văn A">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email (Tên đăng nhập) <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required placeholder="hdv@example.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control" required placeholder="0912345678">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required placeholder="******">
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 fw-bold text-success"><i class="bi bi-briefcase me-2"></i>Hồ sơ Chuyên môn</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Ngày sinh</label>
                                <input type="date" name="date_of_birth" class="form-control">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Kinh nghiệm (Năm)</label>
                                <input type="number" name="experience_years" class="form-control" value="0" min="0">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Phân loại HDV</label>
                                <select name="classification" class="form-select">
                                    <option value="Nội địa">Nội địa</option>
                                    <option value="Quốc tế">Quốc tế</option>
                                    <option value="Chuyên tuyến">Chuyên tuyến</option>
                                    <option value="Chuyên khách đoàn">Chuyên khách đoàn</option>
                                    <option value="Freelancer">Freelancer</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tình trạng sức khỏe</label>
                                <input type="text" name="health_status" class="form-control" value="Tốt">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Ngôn ngữ thành thạo</label>
                                <input type="text" name="languages" class="form-control" placeholder="VD: Tiếng Anh, Tiếng Trung...">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Chứng chỉ / Thẻ hành nghề</label>
                                <textarea name="certificate" class="form-control" rows="4" placeholder="Nhập thông tin thẻ HDV, các chứng chỉ nghiệp vụ..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white py-3 text-end">
                        <button type="reset" class="btn btn-light me-2">Làm lại</button>
                        <button type="submit" class="btn btn-primary fw-bold px-4">
                            <i class="bi bi-person-plus-fill me-2"></i> Tạo Hướng Dẫn Viên
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>