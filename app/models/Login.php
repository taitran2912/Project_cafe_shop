<?php
class Login extends Model {
    /**
     * Kiểm tra đăng nhập bằng email và mật khẩu
     */
    public function checkLogin($email, $password) {
        // Câu lệnh SQL
        $query = "SELECT * FROM Account WHERE Email = ? AND Status = 'active' LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Nếu có user
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // So khớp mật khẩu (nếu đã mã hoá bằng password_hash)
            if ($password == $user['Password']) {
                return $user;
            }
        }

        // Sai tài khoản hoặc mật khẩu
        return false;
    }
}
