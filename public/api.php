<?php
require_once __DIR__ . '/../src/UserRepository.php';
require_once __DIR__ . '/../src/UserController.php';

$dataFile = realpath(__DIR__ . '/../data/users.json');
if ($dataFile === false) {
    // fallback to relative path
    $dataFile = __DIR__ . '/../data/users.json';
}
$repo = new UserRepository($dataFile);
$controller = new UserController($repo);

$action = isset($_GET['action']) ? $_GET['action'] : null;

// Read input: prefer JSON body for POST, otherwise use $_POST
function getInput()
{
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    if (is_array($data)) return $data;
    return $_POST;
}

try {
    switch ($action) {
        case 'list':
            // allow GET
            $controller->list();
            break;
        case 'create':
            $input = getInput();
            $controller->create($input);
            break;
        case 'update':
            $input = getInput();
            $controller->update($input);
            break;
        case 'delete':
            $input = getInput();
            $controller->delete($input);
            break;
        default:
            http_response_code(400);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => 'Invalid action']);
            break;
    }
} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['error' => $e->getMessage()]);
}
