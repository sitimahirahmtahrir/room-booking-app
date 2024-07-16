<?php
namespace Models;

use PDO;

class RoomType {
    protected $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAllRoomTypes() {
        $stmt = $this->pdo->query("SELECT * FROM room_types");
        return $stmt->fetchAll();
    }

    public function getRoomTypeById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM room_types WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createRoomType($data) {
        $stmt = $this->pdo->prepare("INSERT INTO room_types (type, description) VALUES (?, ?)");
        $stmt->execute([$data['type'], $data['description']]);
        return $this->pdo->lastInsertId();
    }

    public function updateRoomType($data) {
        $stmt = $this->pdo->prepare("UPDATE room_types SET type = ?, description = ? WHERE id = ?");
        $stmt->execute([$data['type'], $data['description'], $data['id']]);
        return $stmt->rowCount();
    }

    public function deleteRoomType($id) {
        $stmt = $this->pdo->prepare("DELETE FROM room_types WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }
}
