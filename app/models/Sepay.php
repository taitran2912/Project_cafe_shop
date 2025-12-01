<?php

class Sepay extends Model
{
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

    public function handleWebhook($data)
    {
        $this->writeLog("Webhook Received", $data);

        // Validate minimal fields
        $required = ['transferType', 'transferAmount', 'content'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                return ['success' => false, 'message' => "Thiếu trường: $field"];
            }
        }

        if ($data['transferType'] !== 'in') {
            return ['success' => true, 'message' => 'Not incoming transaction'];
        }

        // Extract ORDxxxx
        $orderCode = $this->extractOrderCode($data);
        if (!$orderCode) {
            return ['success' => false, 'message' => 'Không tìm thấy mã đơn trong nội dung'];
        }

        $this->writeLog("Order Code", $orderCode);

        // Tìm đơn theo cột note
        $sql = "SELECT ID, ID_Customer, Total, Payment_status 
                FROM Orders 
                WHERE Note LIKE ?";

        $stmt = $this->db->prepare($sql);
        $pattern = "%$orderCode%";
        $stmt->bind_param("s", $pattern);
        $stmt->execute();
        $query = $stmt->get_result();

        if ($query->num_rows == 0) {
            return ['success' => false, 'message' => 'Không tìm thấy đơn hàng'];
        }

        $order = $query->fetch_assoc();

        if ($order['Payment_status'] === "paid") {
            return ['success' => true, 'message' => 'Đơn đã thanh toán'];
        }

        $receivedAmount = (int)$data['transferAmount'];
        $orderAmount = (int)$order['Total'];

        // Bắt đầu giao dịch DB
        $this->db->begin_transaction();

        try {

            // 1. Update trạng thái đơn
            $update = $this->db->prepare("UPDATE Orders SET Payment_status='Paid', Status = 'Confirmed' WHERE ID=?");
            $update->bind_param("i", $order['ID']);
            $update->execute();

            // 2. Lưu log vào bảng log
            $log = $this->db->prepare(
                "INSERT INTO log (order_id, transaction_code, event, amount, content, payload, created_at)
                 VALUES (?, ?, 'success', ?, ?, ?, NOW())"
            );

            $transactionCode = $data['referenceCode'] ?? "";
            $content = $data['content'];
            $payload = json_encode($data, JSON_UNESCAPED_UNICODE);

            $log->bind_param(
                "isdss",
                $order['ID'],
                $transactionCode,
                $receivedAmount,
                $content,
                $payload
            );
            $log->execute();

            // 3. Xóa giỏ hàng
            $clearDetail = $this->db->prepare(
                "DELETE cd FROM Cart_detail cd
                 INNER JOIN Cart c ON cd.ID_Cart = c.ID
                 WHERE c.ID_Customer = ?"
            );
            $clearDetail->bind_param("i", $order['ID_Customer']);
            $clearDetail->execute();

            $clearCart = $this->db->prepare("DELETE FROM Cart WHERE ID_Customer = ?");
            $clearCart->bind_param("i", $order['ID_Customer']);
            $clearCart->execute();

            $this->db->commit();

            $this->writeLog("Payment Updated", ['order_id' => $order['ID']]);

            return [
                'success' => true,
                'message' => 'Thanh toán thành công',
                'order_id' => $order['ID']
            ];

        } catch (Exception $e) {

            $this->db->rollback();
            $this->writeLog("DB ERROR", $e->getMessage());

            return [
                'success' => false,
                'message' => 'Lỗi xử lý: ' . $e->getMessage()
            ];
        }
    }

    private function extractOrderCode($data)
    {
        if (preg_match('/ORD\d+/', $data['content'], $match)) return $match[0];
        if (isset($data['description']) && preg_match('/ORD\d+/', $data['description'], $match)) return $match[0];
        return false;
    }
}

