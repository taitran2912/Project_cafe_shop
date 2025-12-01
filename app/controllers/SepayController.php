<?php

class SepayController extends Controller
{
    public function webhook()
    {
        header('Content-Type: application/json');

        $raw = file_get_contents('php://input');

        if (empty($raw)) {
            echo json_encode(['success' => false, 'message' => 'Empty payload']);
            return;
        }

        $data = json_decode($raw, true);

        if (!is_array($data)) {
            echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
            return;
        }
        // Gọi model xử lý
        $model = $this->model("Sepay");
        $result = $model->handleWebhook($data);
        echo json_encode($result);
    }
}
?>
