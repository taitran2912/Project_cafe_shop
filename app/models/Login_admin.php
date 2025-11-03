<?php
class Login_admin extends Model {
    /**
     * Kiểm tra đăng nhập bằng email và mật khẩu (MD5)
     */
    public function checkLogin($email, $password) {
        // Mã hoá mật khẩu bằng MD5
        // $password = md5($password);

        $query = "SELECT ID, Name, Phone, Email, Role FROM Account WHERE Email = ? AND Password = ? AND Status = 'active' LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            return $result->fetch_assoc(); // Trả về thông tin user
        }

        return false; // Sai email hoặc mật khẩu
    }
}