<?php

namespace App\Models;

class Report extends BaseModel
{

    public function getFinancialStats($fromDate, $toDate)
    {
        $sql = "
            SELECT 
                t.tour_id, 
                t.tour_name,
                
                -- Tính Doanh Thu (Dùng d1, d2)
                (SELECT COALESCE(SUM(total_price), 0) 
                 FROM bookings b 
                 WHERE b.tour_id = t.tour_id 
                 AND b.status != 'Hủy'
                 AND DATE(b.created_at) BETWEEN :d1 AND :d2
                ) as revenue,
                
                -- Tính Chi Phí (Dùng d3, d4 thay vì d1, d2 lại)
                (SELECT COALESCE(SUM(amount), 0) 
                 FROM tour_expenses e 
                 WHERE e.tour_id = t.tour_id 
                 AND e.expense_date BETWEEN :d3 AND :d4
                ) as expense

            FROM tours t
            ORDER BY revenue DESC
        ";

        // Truyền đủ 4 tham số
        return $this->fetchAll($sql, [
            'd1' => $fromDate,
            'd2' => $toDate,
            'd3' => $fromDate,
            'd4' => $toDate
        ]);
    }

    public function getSystemTotals($fromDate, $toDate)
    {
        // Hàm này gọi hàm trên nên không cần sửa SQL, chỉ cần logic tính tổng
        $stats = $this->getFinancialStats($fromDate, $toDate);

        $totalRevenue = 0;
        $totalExpense = 0;

        foreach ($stats as $item) {
            $totalRevenue += $item['revenue'];
            $totalExpense += $item['expense'];
        }

        return [
            'revenue' => $totalRevenue,
            'expense' => $totalExpense,
            'profit'  => $totalRevenue - $totalExpense
        ];
    }
}
