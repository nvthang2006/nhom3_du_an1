<?php
namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Models\Report;
use App\Models\Tour;
use App\Models\TourExpense;

class ReportController extends AdminBaseController {
    
    public function __construct() {
        if (!Auth::check() || !Auth::isRole('admin')) {
            header('Location: ' . BASE_URL . '?act=login');
            exit;
        }
    }

    public function index() {
        // Mặc định lấy tháng hiện tại
        $startDate = $_GET['start'] ?? date('Y-m-01');
        $endDate   = $_GET['end'] ?? date('Y-m-t');

        $reportModel = new Report();
        $stats = $reportModel->getFinancialStats($startDate, $endDate);
        $totals = $reportModel->getSystemTotals($startDate, $endDate);

        $this->view('admin/reports/index', [
            'stats' => $stats,
            'totals' => $totals,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    // Hiển thị form và danh sách chi phí của 1 tour
    public function expense() {
        $tourId = $_GET['tour_id'] ?? 0;
        
        $mTour = new Tour();
        $tour = $mTour->find($tourId);
        
        if (!$tour) {
            $_SESSION['error'] = "Không tìm thấy Tour";
            $this->redirect('?act=admin-reports');
            return;
        }

        $mExpense = new TourExpense();
        $expenses = $mExpense->getByTourId($tourId);

        $this->view('admin/reports/expense', [
            'tour' => $tour,
            'expenses' => $expenses
        ]);
    }

    // Lưu chi phí mới
    public function storeExpense() {
        $tourId = $_POST['tour_id'];
        
        $data = [
            'tour_id' => $tourId,
            'title' => $_POST['title'],
            'amount' => $_POST['amount'],
            'expense_date' => $_POST['expense_date'],
            'note' => $_POST['note'],
            'created_by' => Auth::user()['user_id']
        ];

        $m = new TourExpense();
        $m->add($data);

        $_SESSION['flash'] = "Đã thêm khoản chi thành công!";
        $this->redirect("?act=admin-reports-expense&tour_id=$tourId");
    }

    public function deleteExpense() {
        $id = $_POST['expense_id'];
        $tourId = $_POST['tour_id'];
        
        $m = new TourExpense();
        $m->delete($id);
        
        $_SESSION['flash'] = "Đã xóa khoản chi!";
        $this->redirect("?act=admin-reports-expense&tour_id=$tourId");
    }
}