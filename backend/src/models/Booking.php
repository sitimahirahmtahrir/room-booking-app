<?php
namespace Models;

use PDO;

class Booking {
    protected $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function getAllBookings() {
        $stmt = $this->pdo->query("SELECT * FROM bookings");
        return $stmt->fetchAll();
    }

    public function getBookingById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM bookings WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createBooking($data) {
        $stmt = $this->pdo->prepare("INSERT INTO bookings (room, date, startTime, endTime, user) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$data['room'], $data['date'], $data['startTime'], $data['endTime'], $data['user']]);
        return $this->pdo->lastInsertId();
    }

    public function updateBooking($data) {
        $stmt = $this->pdo->prepare("UPDATE bookings SET room = ?, date = ?, startTime = ?, endTime = ?, user = ? WHERE id = ?");
        $stmt->execute([$data['room'], $data['date'], $data['startTime'], $data['endTime'], $data['user'], $data['id']]);
        return $stmt->rowCount();
    }

    public function deleteBooking($id) {
        $stmt = $this->pdo->prepare("DELETE FROM bookings WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }

    public function getBookingsByDate($date) {
        $stmt = $this->pdo->prepare("SELECT * FROM bookings WHERE date = ?");
        $stmt->execute([$date]);
        return $stmt->fetchAll();
    }

    public function getBookingsByTimeFrame($timeFrame) {
        switch ($timeFrame) {
            case 'day':
                $date = date('Y-m-d');
                return $this->getBookingsByDate($date);
            case 'week':
                $startOfWeek = date('Y-m-d', strtotime('Monday this week'));
                $endOfWeek = date('Y-m-d', strtotime('Sunday this week'));
                return $this->getBookingsBetweenDates($startOfWeek, $endOfWeek);
            case 'month':
                $startOfMonth = date('Y-m-01');
                $endOfMonth = date('Y-m-t');
                return $this->getBookingsBetweenDates($startOfMonth, $endOfMonth);
            default:
                return [];
        }
    }

    private function getBookingsBetweenDates($startDate, $endDate) {
        $stmt = $this->pdo->prepare("SELECT * FROM bookings WHERE date BETWEEN ? AND ?");
        $stmt->execute([$startDate, $endDate]);
        return $stmt->fetchAll();
    }
}
