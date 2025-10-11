<?php
class Controller {
    public function model($model) {
        $modelPath = "./app/models/" . $model . ".php";

        // 🔍 Kiểm tra file model có tồn tại không
        if (file_exists($modelPath)) {
            require_once $modelPath;

            // 🔍 Kiểm tra class có tồn tại trong file không
            if (class_exists($model)) {
                echo "✅ Model '{$model}' đã được load thành công.<br>";
                return new $model();
            } else {
                die("❌ Class '{$model}' KHÔNG tồn tại trong file {$modelPath}.<br>");
            }
        } else {
            die("❌ Không tìm thấy file model tại: {$modelPath}.<br>");
        }
    }

    public function view($view, $data = []) {
        $viewPath = "./app/views/" . $view . ".php";

        // 🔍 Kiểm tra file view có tồn tại không
        if (file_exists($viewPath)) {
            extract($data);
            echo "✅ View '{$view}' đã được load thành công.<br>";
            require_once $viewPath;
        } else {
            die("❌ Không tìm thấy file view tại: {$viewPath}.<br>");
        }
    }
}
?>