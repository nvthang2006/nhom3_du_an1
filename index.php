<?php
session_start();

// Require file môi trường và hàm hỗ trợ
require_once './app/Core/Env.php';
require_once './app/Core/bootstrap.php';
require_once './app/Core/Database.php';
require_once './app/Core/Router.php';
require_once './app/Core/Auth.php';

$act = $_GET['act'] ?? '/';

match ($act) {
    '/' => header("Location: ?act=login"),
    // 'tours' => (new App\Controllers\HomeController())->tours(),
    // 'tours-show' => (new App\Controllers\HomeController())->show(), // ?id=

    // Auth
    'login' => (new App\Controllers\AuthController())->showLogin(),
    'login-post' => (new App\Controllers\AuthController())->login(),
    'logout' => (new App\Controllers\AuthController())->logout(),
    'register' => (new App\Controllers\AuthController())->showRegister(),
    'register-post' => (new App\Controllers\AuthController())->register(),

    // Admin
    'admin-dashboard' => (new App\Controllers\Admin\DashboardController())->index(),
    'admin-tours' => (new App\Controllers\Admin\TourController())->index(),
    'admin-tours-create' => (new App\Controllers\Admin\TourController())->create(),
    'admin-tours-store' => (new App\Controllers\Admin\TourController())->store(),
    'admin-tours-edit' => (new App\Controllers\Admin\TourController())->edit(), // ?id=
    'admin-tours-update' => (new App\Controllers\Admin\TourController())->update(),
    'admin-tours-delete' => (new App\Controllers\Admin\TourController())->delete(),
    'admin-tours-detail' => (new App\Controllers\Admin\TourController())->show(),
    'admin-bookings' => (new App\Controllers\Admin\BookingController())->index(),
    'admin-bookings-create' => (new App\Controllers\Admin\BookingController())->create(),
    'admin-bookings-store' => (new App\Controllers\Admin\BookingController())->store(),
    'admin-bookings-prepare' => (new App\Controllers\Admin\BookingController())->prepare(),
    'admin-bookings-edit' => (new App\Controllers\Admin\BookingController())->edit(),
    'admin-bookings-update' => (new App\Controllers\Admin\BookingController())->update(),
    'admin-tours-store-departure' => (new \App\Controllers\Admin\TourController())->storeDeparture(),
    'admin-tours-delete-departure' => (new \App\Controllers\Admin\TourController())->deleteDeparture(),
    'admin-departures-passengers' => (new App\Controllers\Admin\DepartureController())->passengers(),
    'admin-departures-update-passengers' => (new App\Controllers\Admin\DepartureController())->updatePassengers(),
    'admin-departures-print' => (new App\Controllers\Admin\DepartureController())->printPassengers(),
    'admin-tours-delete-gallery' => (new App\Controllers\Admin\TourController())->deleteGalleryImage(),

    // HDV
    'hdv-dashboard' => (new App\Controllers\Hdv\DashboardController())->index(),
    'hdv-checkin' => (new App\Controllers\Hdv\CheckinController())->list(),
    'hdv-checkin-create' => (new App\Controllers\Hdv\CheckinController())->create(),


    // --- Quản lý HDV ---
    'admin-hdv' => (new App\Controllers\Admin\HdvController())->index(),
    'admin-hdv-create' => (new App\Controllers\Admin\HdvController())->create(), // <--- MỚI
    'admin-hdv-store' => (new App\Controllers\Admin\HdvController())->store(),   // <--- MỚI
    'admin-hdv-edit' => (new App\Controllers\Admin\HdvController())->edit(),
    'admin-hdv-update' => (new App\Controllers\Admin\HdvController())->update(),
    // --- Quản lý Nhà cung cấp ---
    'admin-tours-add-supplier' => (new App\Controllers\Admin\TourController())->addSupplier(),
    'admin-tours-remove-supplier' => (new App\Controllers\Admin\TourController())->removeSupplier(),
    'admin-suppliers' => (new App\Controllers\Admin\SupplierController())->index(),
    'admin-suppliers-create' => (new App\Controllers\Admin\SupplierController())->create(),
    'admin-suppliers-store' => (new App\Controllers\Admin\SupplierController())->store(),
    'admin-suppliers-edit' => (new App\Controllers\Admin\SupplierController())->edit(),
    'admin-suppliers-update' => (new App\Controllers\Admin\SupplierController())->update(),
    'admin-suppliers-delete' => (new App\Controllers\Admin\SupplierController())->delete(),

    // --- Quản lý Điều hành Tour ---
    'admin-departures-manage' => (new App\Controllers\Admin\DepartureController())->manage(),
    'admin-departures-update-op' => (new App\Controllers\Admin\DepartureController())->updateOperational(),
    'admin-departures-add-service' => (new App\Controllers\Admin\DepartureController())->addService(),
    'admin-departures-delete-service' => (new App\Controllers\Admin\DepartureController())->deleteService(),

    default => http_response_code(404) && print("404 Not Found"),
};
