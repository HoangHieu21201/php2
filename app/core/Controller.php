<?php

// Import trực tiếp các thành phần lõi của Illuminate (Laravel)
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\View\Compilers\BladeCompiler;
use Illuminate\View\Engines\CompilerEngine;
use Illuminate\View\Engines\EngineResolver;
use Illuminate\View\Engines\PhpEngine;
use Illuminate\View\Factory;
use Illuminate\View\FileViewFinder;

class Controller
{
    public function view(string $view, array $data = []): void
    {
        // 1. Chuẩn hoá tên view: "home/index" -> "home.index"
        $view = $this->normalizeViewName($view);

        // 2. Cấu hình đường dẫn
        $viewsPath = VIEW_PATH; // Thư mục chứa view
        $cachePath = BASE_PATH . '/storage/cache'; // Thư mục chứa cache

        // Tạo thư mục cache nếu chưa có
        if (!is_dir($cachePath) && !mkdir($cachePath, 0775, true) && !is_dir($cachePath)) {
            throw new RuntimeException("Cannot create cache directory: {$cachePath}");
        }

        // --- KHỞI TẠO BLADE ENGINE THỦ CÔNG ĐỂ TRÁNH LỖI REFLECTION ---
        
        // Tạo các instance cơ bản
        $filesystem = new Filesystem;
        $eventDispatcher = new Dispatcher(new Container);

        // Tạo View Resolver (bộ giải quyết engine)
        $resolver = new EngineResolver;

        // Đăng ký 'blade' engine
        $resolver->register('blade', function () use ($filesystem, $cachePath) {
            // Tự khởi tạo Compiler để tránh lỗi "blade.compiler does not exist"
            $compiler = new BladeCompiler($filesystem, $cachePath);
            return new CompilerEngine($compiler);
        });

        // Đăng ký 'php' engine (dự phòng)
        $resolver->register('php', function () use ($filesystem) {
            return new PhpEngine($filesystem);
        });

        // Tìm kiếm file view
        $finder = new FileViewFinder($filesystem, [$viewsPath]);

        // Tạo Factory (đối tượng chính để render)
        $factory = new Factory($resolver, $finder, $eventDispatcher);

        // 3. Render
        try {
            echo $factory->make($view, $data)->render();
        } catch (Exception $e) {
            // Xử lý lỗi hiển thị thân thiện hơn
            echo "Lỗi Blade View: " . $e->getMessage();
            // In thêm stack trace nếu cần debug
            // echo "<pre>" . $e->getTraceAsString() . "</pre>";
        }
    }

    protected function normalizeViewName(string $view): string
    {
        $view = trim($view);
        $view = str_replace(['\\', '/'], '.', $view);
        $view = preg_replace('/\.+/', '.', $view);
        return trim($view, '.');
    }

    public function model($name)
    {
        $class = ucfirst($name);
        if (!class_exists($class)) {
             $classWithNamespace = "App\\Models\\" . $class;
             if(class_exists($classWithNamespace)) {
                 return new $classWithNamespace();
             }
            throw new Exception("Model class not found: $name");
        }
        return new $class();
    }

    public function redirect($path)
    {
        $base = rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/');
        $target = $base . '/' . ltrim($path, '/');
        header('Location: ' . $target);
        exit;
    }

    public function notFound($message): void
    {
        http_response_code(404);
        echo "<h1>Controller Not Found - " . htmlspecialchars($message) . "</h1>";
        exit;
    }
}