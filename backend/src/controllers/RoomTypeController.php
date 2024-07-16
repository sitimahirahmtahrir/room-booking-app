<?php
namespace Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Models\RoomType;

class RoomTypeController {
    protected $roomType;

    public function __construct(RoomType $roomType) {
        $this->roomType = $roomType;
    }

    public function getAllRoomTypes(Request $request, Response $response): Response {
        $roomTypes = $this->roomType->getAllRoomTypes();
        $response->getBody()->write(json_encode($roomTypes));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getRoomTypeById(Request $request, Response $response, array $args): Response {
        $roomType = $this->roomType->getRoomTypeById($args['id']);
        if ($roomType) {
            $response->getBody()->write(json_encode($roomType));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(['error' => 'Room type not found']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }
    }

    public function createRoomType(Request $request, Response $response): Response {
        $data = $request->getParsedBody();
        $newRoomTypeId = $this->roomType->createRoomType($data);
        $response->getBody()->write(json_encode(['id' => $newRoomTypeId]));
        return $response->withStatus(201)->withHeader('Content-Type', 'application/json');
    }

    public function updateRoomType(Request $request, Response $response, array $args): Response {
        $data = $request->getParsedBody();
        $updated = $this->roomType->updateRoomType($args['id'], $data);
        if ($updated) {
            $response->getBody()->write(json_encode(['message' => 'Room type updated']));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(['error' => 'Room type not found or no changes made']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }
    }

    public function deleteRoomType(Request $request, Response $response, array $args): Response {
        $deleted = $this->roomType->deleteRoomType($args['id']);
        if ($deleted) {
            $response->getBody()->write(json_encode(['message' => 'Room type deleted']));
            return $response->withHeader('Content-Type', 'application/json');
        } else {
            $response->getBody()->write(json_encode(['error' => 'Room type not found']));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }
    }
}
