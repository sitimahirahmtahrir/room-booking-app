<?php
use Slim\Routing\RouteCollectorProxy;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Controllers\BookingController;
use Controllers\RoomTypeController;
use Controllers\AuthController;

return function($app) {
    $pdo = require __DIR__ . '/../config/db.php';
    
    $authController = new AuthController();
    $bookingController = new BookingController(new Models\Booking($pdo));
    $roomTypeController = new RoomTypeController(new Models\RoomType($pdo));

    $app->group('/api', function (RouteCollectorProxy $group) use ($pdo, $authController, $bookingController, $roomTypeController) {
        // Auth routes
        $group->post('/login', [$authController, 'login']);
        $group->get('/logout', [$authController, 'logout']);

        // Public routes
        $group->get('/bookings/today', [$bookingController, 'getBookingsByDate']);
        $group->get('/bookings/week', function (Request $request, Response $response) use ($bookingController) {
            return $bookingController->getBookingsByTimeFrame($request, $response, ['timeFrame' => 'week']);
        });
        $group->get('/bookings/month', function (Request $request, Response $response) use ($bookingController) {
            return $bookingController->getBookingsByTimeFrame($request, $response, ['timeFrame' => 'month']);
        });

        // Protected routes
        $group->group('', function (RouteCollectorProxy $group) use ($bookingController, $roomTypeController) {
            // Booking routes
            $group->get('/bookings', [$bookingController, 'getAllBookings']);
            $group->get('/bookings/{id}', [$bookingController, 'getBookingById']);
            $group->post('/bookings', [$bookingController, 'createBooking']);
            $group->put('/bookings/{id}', [$bookingController, 'updateBooking']);
            $group->delete('/bookings/{id}', [$bookingController, 'deleteBooking']);

            // Room type routes
            $group->get('/room-types', [$roomTypeController, 'getAllRoomTypes']);
            $group->get('/room-types/{id}', [$roomTypeController, 'getRoomTypeById']);
            $group->post('/room-types', [$roomTypeController, 'createRoomType']);
            $group->put('/room-types/{id}', [$roomTypeController, 'updateRoomType']);
            $group->delete('/room-types/{id}', [$roomTypeController, 'deleteRoomType']);
        })->add([$authController, 'check']);
    });
};
