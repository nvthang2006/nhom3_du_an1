<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Tạo Booking Mới</h1>
            <p class="text-muted mb-0 small">Tạo đơn đặt tour thủ công cho khách hàng</p>
        </div>
        <a href="<?= BASE_URL ?>?act=admin-bookings" class="btn btn-outline-secondary shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Quay lại danh sách
        </a>
    </div>

    <form method="post" action="<?= BASE_URL ?>?act=admin-bookings-store" id="bookingForm">
        <div class="row g-4">

            <div class="col-lg-7">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-map me-2"></i>Thông tin Tour</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Chọn Tour <span class="text-danger">*</span></label>
                            <select name="tour_id" id="tourSelect" class="form-select form-select-lg" required>
                                <option value="" data-price="0">-- Chọn Tour du lịch --</option>
                                <?php foreach ($tours as $t): ?>
                                    <option value="<?= $t['tour_id'] ?>" data-price="<?= $t['price'] ?>">
                                        <?= htmlspecialchars($t['tour_name']) ?> - Giá: <?= number_format($t['price']) ?>₫
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Ngày khởi hành <span class="text-danger">*</span></label>
                                <input type="date" name="start_date" required class="form-control" value="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Số lượng khách <span class="text-danger">*</span></label>
                                <input type="number" name="total_people" id="totalPeople" required min="1" value="1" class="form-control">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Ghi chú / Yêu cầu đặc biệt</label>
                            <textarea name="note" class="form-control" rows="3" placeholder="VD: Khách ăn chay, cần đón tại sân bay, khách đoàn công ty..."></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h6 class="m-0 font-weight-bold text-primary"><i class="bi bi-person-lines-fill me-2"></i>Khách hàng</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Chọn Khách hàng <span class="text-danger">*</span></label>
                            <select name="customer_id" class="form-select" required>
                                <option value="">-- Tìm khách hàng --</option>
                                <?php foreach ($customers as $c): ?>
                                    <option value="<?= $c['user_id'] ?>">
                                        <?= htmlspecialchars($c['full_name']) ?> (<?= $c['phone'] ?? $c['email'] ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text text-end"><a href="#" class="text-decoration-none small">+ Tạo khách mới</a></div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm bg-light">
                    <div class="card-body">
                        <h6 class="fw-bold text-muted mb-3">TỔNG THANH TOÁN</h6>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Đơn giá tour:</span>
                            <span class="fw-semibold" id="displayPrice">0 ₫</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Số lượng:</span>
                            <span class="fw-semibold"><span id="displayPeople">1</span> người</span>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label small text-muted">Tổng tiền (có thể sửa thủ công):</label>
                            <div class="input-group input-group-lg">
                                <input type="number" name="total_price" id="totalPrice" required class="form-control fw-bold text-primary" value="0">
                                <span class="input-group-text">₫</span>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Xác nhận Booking
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tourSelect = document.getElementById('tourSelect');
        const peopleInput = document.getElementById('totalPeople');
        const priceInput = document.getElementById('totalPrice');

        const displayPrice = document.getElementById('displayPrice');
        const displayPeople = document.getElementById('displayPeople');

        function calculateTotal() {
            // Lấy giá tour từ thuộc tính data-price của option đang chọn
            const selectedOption = tourSelect.options[tourSelect.selectedIndex];
            const pricePerPax = parseFloat(selectedOption.getAttribute('data-price')) || 0;
            const people = parseInt(peopleInput.value) || 1;

            const total = pricePerPax * people;

            // Cập nhật giao diện
            priceInput.value = total;
            displayPrice.textContent = new Intl.NumberFormat('vi-VN').format(pricePerPax) + ' ₫';
            displayPeople.textContent = people;
        }

        // Lắng nghe sự kiện thay đổi
        tourSelect.addEventListener('change', calculateTotal);
        peopleInput.addEventListener('input', calculateTotal);
    });
</script>