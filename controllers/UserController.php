<?php

class UserController
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create($request)
    {
        $data = $request->getParsedBody();
        $id = $this->user->create($data);
        return json_encode(['success' => true, 'result' => ['id' => $id]]);
    }

    public function get($request)
    {
        $id = $request->getAttribute('id');
        $filters = $request->getQueryParams();

        try {
            $users = $this->user->get($id, $filters);
            return json_encode(['success' => true, 'result' => ['users' => $users]]);
        } catch (Exception $e) {
            return json_encode(['success' => false, 'result' => ['error' => $e->getMessage()]]);
        }
    }

    public function update($request)
    {
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody();

        try {
            $user = $this->user->update($id, $data);
            return json_encode(['success' => true, 'result' => $user]);
        } catch (Exception $e) {
            return json_encode(['success' => false, 'result' => ['error' => $e->getMessage()]]);
        }
    }

    public function delete($request)
    {
        $id = $request->getAttribute('id');

        try {
            if ($id) {
                $user = $this->user->get($id);
                $this->user->delete($id);
                return json_encode(['success' => true, 'result' => $user]);
            } else {
                $this->user->delete();
                return json_encode(['success' => true]);
            }
        } catch (Exception $e) {
            return json_encode(['success' => false, 'result' => ['error' => $e->getMessage()]]);
        }
    }
}