<?php if (empty($bookings)) echo '<p>No bookings</p>';
else {
    echo '<h2>Bookings</h2><table><tr><th>ID</th><th>Tour</th><th>Total</th><th>Status</th></tr>';
    foreach ($bookings as $b) {
        echo '<tr><td>' . $b['booking_id'] . '</td><td>' . $b['tour_id'] . '</td><td>' . $b['total_people'] . '</td><td>' . $b['status'] . '</td></tr>';
    }
    echo '</table>';
}
