<?php
namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Models\Booking;

class BookingController {
    protected $booking;

    public function __construct(Booking $booking) {
        $this->booking = $booking;
    }

    public function getAllBookings(Request $request, Response $response): Response {
        $bookings = $this->booking->getAllBookings();
        $response->getBody()->write(json_encode($bookings));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getBookingById(Request $request, Response $response, array $args): Response {
        $booking = $this->booking->getBookingById($args['id']);
        if ($booking) {
            $response->getBody()->write(json_encode($booking));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(['error' => 'Booking not found']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }
    }

    public function createBooking(Request $request, Response $response): Response {
        $data = $request->getParsedBody();
        $id = $this->booking->createBooking($data['room'], $data['date'], $data['startTime'], $data['endTime'], $data['user']);
        $response->getBody()->write(json_encode(['id' => $id]));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

    public function updateBooking(Request $request, Response $response, array $args): Response {
        $data = $request->getParsedBody();
        $affectedRows = $this->booking->updateBooking($args['id'], $data['room'], $data['date'], $data['startTime'], $data['endTime'], $data['user']);
        if ($affectedRows > 0) {
            $response->getBody()->write(json_encode(['message' => 'Booking updated']));
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(['error' => 'Update failed or no changes made']));
            return $response->withStatus(304)->withHeader('Content-Type', 'application/json');
        }
    }

    public function deleteBooking(Request $request, Response $response, array $args): Response {
        $affectedRows = $this->booking->deleteBooking($args['id']);
        if ($affectedRows > 0) {
            $response->getBody()->write(json_encode(['message' => 'Booking deleted']));
            return $response->withStatus(200)->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(['error' => 'Booking not found']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }
    }

    public function getBookingsByDate(Request $request, Response $response): Response {
        $date = $request->getQueryParams()['date'] ?? date('Y-m-d');
        $bookings = $this->booking->getBookingsByDate($date);
        $response->getBody()->write(json_encode($bookings));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getBookingsByTimeFrame(Request $request, Response $response, array $args): Response {
        $timeFrame = $args['timeFrame'];
        $bookings = $this->booking->getBookingsByTimeFrame($timeFrame);
        $response->getBody()->write(json_encode($bookings));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
