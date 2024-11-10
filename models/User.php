<?php

class User
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function create($data)
    {
        $stmt = $this->pdo->prepare("INSERT INTO users (full_name, role, efficiency) VALUES (:full_name, :role, :efficiency)");
        $stmt->execute($data);
        return $this->pdo->lastInsertId();
    }

    public function get($id = null, $filters = [])
    {
        $query = "SELECT * FROM users";
        $params = [];

        if ($id) {
            $query .= " WHERE id = :id";
            $params['id'] = $id;
        } else {
            foreach ($filters as $key => $value) {
                if (!empty($params)) {
                    $query .= " AND";
                } else {
                    $query .= " WHERE";
                }
                $query .= " $key = :$key";
                $params[$key] = $value;
            }
        }

        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function update($id, $data)
    {
        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = :$key";
        }
        $set = implode(', ', $set);

        $stmt = $this->pdo->prepare("UPDATE users SET $set WHERE id = :id");
        $data['id'] = $id;
        $stmt->execute($data);
        return $this->get($id);
    }

    public function delete($id = null)
    {
        if ($id) {
            $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute(['id' => $id]);
            return $stmt->rowCount();
        } else {
            $stmt = $this->pdo->prepare("DELETE FROM users");
            $stmt->execute();
            return $stmt->rowCount();
        }
    }
}