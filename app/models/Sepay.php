<?php

class Sepay extends Model
{
    // Ghi log ra file
    private function writeLog($message, $data = null)
    {
        $logFile = __DIR__ . '/../../storage/sepay_webhook.log';
        $timestamp = date('Y-m-d H:i:s');

        $log = "[{$timestamp}] {$message}";
        if ($data !== null) {
            $log .= " - " . json_encode($data, JSON_UNESCAPED_UNICODE);
        }

        file_put_contents($logFile, $log . PHP_EOL, FILE_APPEND);
    }

    // -------------------------------------------------
    // Xử lý webhook chính
    // -------------------------------------------------
    public function handleWebhook($data)
    {
        $this->writeLog("Webhook Received", $data);

        // Validate fields
        $required = ['transferType', 'transferAmount', 'content'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                return ['success' => false, 'message' => "Thiếu trường: $field"];
            }
        }

        // Chỉ xử lý tiền vào
        if ($data['transferType'] !== 'in') {
            return ['success' => true, 'message' => 'Not incoming transaction'];
        }

        // Extract order code
        $orderCode = $this->extractOrderCode($data);
        if (!$orderCode) {
            return ['success' => false, 'message' => 'Không tìm thấy mã đơn'];
        }

        $this->writeLog("Order Code Extracted", $orderCode);

        $sql = "SELECT id, user_id, total_amount, status FROM orders WHERE notes LIKE ?";
        $stmt = $this->db->prepare($sql);

        $pattern = "%$orderCode%";
        $stmt->bind_param("s", $pattern);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            return ['success' => false, 'message' => 'Không tìm thấy đơn hàng'];
        }

        $order = $result->fetch_assoc();

        if ($order['status'] == 1) {
            return ['success' => true, 'message' => 'Đơn đã thanh toán'];
        }

        $received = $data['transferAmount'];
        $expected = $order['total_amount'];

        // -------------------------------------------------
        // Bắt đầu giao dịch
        // -------------------------------------------------
        $this->db->begin_transaction();

        try {
            // Update ORDER
            $update = $this->db->prepare("UPDATE orders SET status = 1 WHERE id = ?");
            $update->bind_param("i", $order['id']);
            $update->execute();

            // Insert vào payment_logs
            $log = $this->db->prepare(
                "INSERT INTO payment_logs (order_id, transaction_code, event, amount, content, payload, created_at) 
                 VALUES (?, ?, 'success', ?, ?, ?, NOW())"
            );

            $transactionCode = $data['referenceCode'] ?? "";
            $content = $data['content'];
            $payload = json_encode($data, JSON_UNESCAPED_UNICODE);

            $log->bind_param("isdss", $order['id'], $transactionCode, $received, $content, $payload);
            $log->execute();

            // Xóa giỏ hàng
            $clear = $this->db->prepare("DELETE FROM cart WHERE customer_id = ?");
            $clear->bind_param("i", $order['user_id']);
            $clear->execute();

            $this->db->commit();

            $this->writeLog("Payment Success", ['order_id' => $order['id']]);

            return [
                'success' => true,
                'message' => 'Thanh toán thành công',
                'order_id' => $order['id']
            ];
        } catch (Exception $e) {
            $this->db->rollback();
            return [
                'success' => false,
                'message' => "Lỗi xử lý: " . $e->getMessage()
            ];
        }
    }

    // -------------------------------------------------
    // Tách mã đơn ORDxxxxx từ content / description
    // -------------------------------------------------
    private function extractOrderCode($data)
    {
        if (preg_match('/ORD\d+/', $data['content'], $match)) return $match[0];
        if (isset($data['description']) && preg_match('/ORD\d+/', $data['description'], $match)) return $match[0];
        return false;
    }
}

?>
