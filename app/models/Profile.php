<?php
class Profile extends Model {
    public function getProfile($userId) {
        $stmt = $this->db->prepare("SELECT a.Name, a.Mail, a.Phone, a.Address FROM Account a WHERE a.ID = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
