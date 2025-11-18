<?php
namespace App\Core;

class Router {
    private $routes = [];

    public function get($path, $handler){
        $this->routes['GET'][$this->normalize($path)] = $handler;
    }

    public function post($path, $handler){
        $this->routes['POST'][$this->normalize($path)] = $handler;
    }

    private function normalize($p){
        $raw = parse_url($p, PHP_URL_PATH);
        if ($raw === null) $raw = $p;
        $raw = (string)$raw;
        $raw = rtrim($raw, '/');
        if ($raw === '') return '/';
        return '/' . ltrim($raw, '/');
    }

    /**
     * Lấy path thực tế:
     * - /DA1/                        -> /
     * - /DA1/index.php               -> /
     * - /DA1/index.php/login         -> /login
     * - /index.php/admin/tours       -> /admin/tours
     */
    private function getCurrentPath($uri = null){
        if ($uri === null) {
            $uri = $_SERVER['REQUEST_URI'] ?? '/';
        }

        // Ví dụ: /DA1/index.php/login?x=1 -> /DA1/index.php/login
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';

        // Ví dụ: /DA1/index.php
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $scriptName = str_replace('\\', '/', $scriptName);
        $scriptDir  = $scriptName ? rtrim(dirname($scriptName), '/') : '';

        // Trường hợp truy cập vào thư mục gốc dự án: /DA1 hoặc /DA1/
        if ($scriptDir && ($path === $scriptDir || $path === $scriptDir . '/')) {
            $path = '/';
        } else {
            // Nếu path bắt đầu bằng scriptName thì cắt nó đi
            // /DA1/index.php/login -> cắt /DA1/index.php -> còn /login
            if ($scriptName && strpos($path, $scriptName) === 0) {
                $path = substr($path, strlen($scriptName));
            }

            // Nếu rỗng thì là root
            if ($path === '' || $path === false) {
                $path = '/';
            }
        }

        return $this->normalize($path);
    }

    public function dispatch($uri = null, $method = null){
        if ($method === null) {
            $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        }
        $method = strtoupper((string)$method);

        // Lấy path đã xử lý
        $path = $this->getCurrentPath($uri);

        $handler = $this->routes[$method][$path] ?? null;

        if (!$handler) {
            http_response_code(404);
            echo "404 Not Found: $method $path";
            exit;
        }

        if (!is_string($handler) || strpos($handler,'@') === false){
            http_response_code(500);
            echo 'Invalid route handler';
            exit;
        }

        [$controller, $action] = explode('@', $handler, 2);
        $controller = str_replace('/', '\\', $controller);
        $controllerClass = "\\App\\Controllers\\$controller";

        if (!class_exists($controllerClass)) {
            http_response_code(500);
            echo "Controller not found: $controllerClass";
            exit;
        }

        $c = new $controllerClass();

        if (!method_exists($c, $action)) {
            http_response_code(500);
            echo "Action not found: $controllerClass@$action";
            exit;
        }

        call_user_func([$c, $action]);
    }

    
}
