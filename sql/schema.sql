-- Schema for DA1 rebuilt
CREATE DATABASE IF NOT EXISTS TourManagement CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE TourManagement;

CREATE TABLE IF NOT EXISTS users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  full_name VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE,
  password VARCHAR(255) NOT NULL,
  phone VARCHAR(20),
  role ENUM('admin','hdv','customer') NOT NULL DEFAULT 'customer',
  status TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS tours (
  tour_id INT AUTO_INCREMENT PRIMARY KEY,
  tour_name VARCHAR(150) NOT NULL,
  tour_type ENUM('Trong nước','Quốc tế','Theo yêu cầu') NOT NULL DEFAULT 'Trong nước',
  description TEXT,
  price DECIMAL(12,2) DEFAULT 0.00,
  duration_days INT DEFAULT 1,
  policy TEXT,
  status ENUM('Hoạt động','Ngừng') NOT NULL DEFAULT 'Hoạt động',
  created_by INT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (created_by) REFERENCES users(user_id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS tour_schedule (
  schedule_id INT AUTO_INCREMENT PRIMARY KEY,
  tour_id INT NOT NULL,
  day_number INT NOT NULL,
  description TEXT,
  location VARCHAR(255),
  image VARCHAR(255),
  FOREIGN KEY (tour_id) REFERENCES tours(tour_id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS bookings (
  booking_id INT AUTO_INCREMENT PRIMARY KEY,
  tour_id INT NOT NULL,
  created_by INT NOT NULL,
  total_people INT NOT NULL DEFAULT 1,
  total_price DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  start_date DATE NOT NULL,
  status ENUM('Chờ xác nhận','Đã cọc','Hoàn tất','Hủy') NOT NULL DEFAULT 'Chờ xác nhận',
  note TEXT,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (tour_id) REFERENCES tours(tour_id),
  FOREIGN KEY (created_by) REFERENCES users(user_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS tour_assignments (
  assign_id INT AUTO_INCREMENT PRIMARY KEY,
  tour_id INT NOT NULL,
  hdv_id INT NOT NULL,
  start_date DATE NOT NULL,
  end_date DATE,
  note TEXT,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (tour_id) REFERENCES tours(tour_id),
  FOREIGN KEY (hdv_id) REFERENCES users(user_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS tour_logs (
  log_id INT AUTO_INCREMENT PRIMARY KEY,
  assign_id INT NOT NULL,
  log_date DATE NOT NULL,
  description TEXT,
  issue TEXT,
  feedback TEXT,
  image VARCHAR(255),
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (assign_id) REFERENCES tour_assignments(assign_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS reports (
  report_id INT AUTO_INCREMENT PRIMARY KEY,
  tour_id INT NOT NULL,
  period_from DATE,
  period_to DATE,
  revenue DECIMAL(14,2) DEFAULT 0.00,
  expense DECIMAL(14,2) DEFAULT 0.00,
  profit DECIMAL(14,2) DEFAULT 0.00,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (tour_id) REFERENCES tours(tour_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS customer_booking_history (
  history_id INT AUTO_INCREMENT PRIMARY KEY,
  customer_id INT,
  booking_id INT,
  tour_id INT,
  start_date DATE,
  end_date DATE,
  joined_people INT DEFAULT 1,
  payment_status ENUM('Đã thanh toán','Chưa thanh toán','Hoàn tiền') DEFAULT 'Đã thanh toán',
  feedback TEXT,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (customer_id) REFERENCES users(user_id),
  FOREIGN KEY (booking_id) REFERENCES bookings(booking_id),
  FOREIGN KEY (tour_id) REFERENCES tours(tour_id)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS checkin_records (
  checkin_id INT AUTO_INCREMENT PRIMARY KEY,
  booking_id INT,
  customer_id INT,
  schedule_id INT NOT NULL,
  assign_id INT,
  hdv_id INT,
  checkin_time DATETIME,
  checkout_time DATETIME,
  checked_count INT DEFAULT 0,
  status ENUM('CheckedIn','CheckedOut','NoShow','Skipped') DEFAULT 'CheckedIn',
  latitude DECIMAL(10,7),
  longitude DECIMAL(10,7),
  image VARCHAR(255),
  note TEXT,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (booking_id) REFERENCES bookings(booking_id),
  FOREIGN KEY (customer_id) REFERENCES users(user_id),
  FOREIGN KEY (schedule_id) REFERENCES tour_schedule(schedule_id),
  FOREIGN KEY (assign_id) REFERENCES tour_assignments(assign_id),
  FOREIGN KEY (hdv_id) REFERENCES users(user_id)
) ENGINE=InnoDB;
