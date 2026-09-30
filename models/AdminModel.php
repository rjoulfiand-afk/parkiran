<?php
class AdminModel {
    private $db;
    public function __construct($koneksi) {
        $this->db = $koneksi;
    }
    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>