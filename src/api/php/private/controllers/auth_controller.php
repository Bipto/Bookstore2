<?php

class AuthController
{
    public function __construct(private RelationalDatabase $db) {}

    public function handleLogin(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $queryBuilder = QueryBuilder::table('bookstore.users')
            ->select()
            ->where([
                'email' => $data['email']
            ]);

        $result = $this->db->executeAndReturnOne($queryBuilder);

        $json = [];
        $json['success'] = password_verify($data['password'], $result['password']);
        echo json_encode($json);
    }
}
