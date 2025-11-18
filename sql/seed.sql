USE TourManagement;

-- Please create admin password using PHP's password_hash('123456', PASSWORD_DEFAULT) and replace below.
INSERT INTO users (full_name,email,password,phone,role) VALUES
('Admin User','admin@example.com','REPLACE_WITH_PHP_PASSWORD_HASH','0123456789','admin');

INSERT INTO users (full_name,email,password,phone,role) VALUES
('Nguyen Van HDV','hdv@example.com','REPLACE_WITH_PHP_PASSWORD_HASH_FOR_HDV','0987654321','hdv');

INSERT INTO tours (tour_name,tour_type,description,price,duration_days,policy,created_by) VALUES
('Hanoi Classic - 3 Days','Trong nước','Hanoi city tour',2500000,3,'Hủy trước 7 ngày hoàn 80%',1);

INSERT INTO tour_schedule (tour_id,day_number,description,location) VALUES
(1,1,'Hoan Kiem & Old Quarter','Hoan Kiem'),
(1,2,'Temple of Literature','Van Mieu');

INSERT INTO bookings (tour_id,created_by,total_people,total_price,start_date,status,note) VALUES
(1,1,4,10000000,'2025-11-20','Hoàn tất','Demo booking');

INSERT INTO tour_assignments (tour_id,hdv_id,start_date,end_date,note) VALUES
(1,2,'2025-11-20','2025-11-22','Assigned to HDV');
