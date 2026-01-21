<?php
require_once __DIR__ . '/UserRepository.php';

class UserController
{
    private $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    public function list()
    {
        $users = $this->repo->getAll();
        $this->jsonResponse($users);
    }

    public function create($input)
    {
        $errors = $this->validate($input);
        if (!empty($errors)) {
            $this->jsonResponse(['errors' => $errors], 422);
            return;
        }
        $user = [
            'name' => trim($input['name']),
            'email' => trim($input['email']),
            'phone' => trim($input['phone']),
        ];
        $created = $this->repo->create($user);
        $this->jsonResponse($created, 201);
    }

    public function update($input)
    {
        if (!isset($input['id'])) {
            $this->jsonResponse(['error' => 'ID is required'], 400);
            return;
        }
        $errors = $this->validate($input);
        if (!empty($errors)) {
            $this->jsonResponse(['errors' => $errors], 422);
            return;
        }
        $updated = $this->repo->update($input['id'], $input);
        if ($updated === null) {
            $this->jsonResponse(['error' => 'User not found'], 404);
            return;
        }
        $this->jsonResponse($updated);
    }

    public function delete($input)
    {
        if (!isset($input['id'])) {
            $this->jsonResponse(['error' => 'ID is required'], 400);
            return;
        }
        $ok = $this->repo->delete($input['id']);
        if (!$ok) {
            $this->jsonResponse(['error' => 'User not found'], 404);
            return;
        }
        $this->jsonResponse(['success' => true]);
    }

    private function validate($input)
    {
        $errors = [];
        if (!isset($input['name']) || trim($input['name']) === '') {
            $errors['name'] = 'Nome é obrigatório';
        }
        if (!isset($input['email']) || trim($input['email']) === '') {
            $errors['email'] = 'Email é obrigatório';
        } elseif (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email inválido';
        }
        if (!isset($input['phone']) || trim($input['phone']) === '') {
            $errors['phone'] = 'Telefone é obrigatório';
        }
        return $errors;
    }

    private function jsonResponse($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
}
