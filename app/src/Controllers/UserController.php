<?php
namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Doctrine\ORM\EntityManager;
use App\Models\User;

class UserController
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
        $user = new User();
        $user->setFullName($data['full_name']);
        $user->setRole($data['role']);
        $user->setEfficiency($data['efficiency']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $response->getBody()->write(json_encode([
            'success' => true,
            'result' => ['id' => $user->getId()]
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function get(Request $request, Response $response, array $args): Response
    {
        $repository = $this->entityManager->getRepository(User::class);

        if (isset($args['id'])) {
            $user = $repository->find($args['id']);
            if (!$user) {
                $response->getBody()->write(json_encode([
                    'success' => false,
                    'result' => ['error' => 'User not found']
                ]));
                return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
            }
            $users = [$user];
        } else {
            $users = $repository->findAll();
        }

        $response->getBody()->write(json_encode([
            'success' => true,
            'result' => ['users' => $users]
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function update(Request $request, Response $response, array $args): Response
    {
        $data = $request->getParsedBody();
        $user = $this->entityManager->find(User::class, $args['id']);

        if (!$user) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'result' => ['error' => 'User not found']
            ]));
            return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
        }

        if (isset($data['full_name'])) {
            $user->setFullName($data['full_name']);
        }
        if (isset($data['role'])) {
            $user->setRole($data['role']);
        }
        if (isset($data['efficiency'])) {
            $user->setEfficiency($data['efficiency']);
        }

        $this->entityManager->flush();

        $response->getBody()->write(json_encode([
            'success' => true,
            'result' => $user
        ]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function delete(Request $request, Response $response, array $args): Response
    {
        if (isset($args['id'])) {
            $user = $this->entityManager->find(User::class, $args['id']);
            if (!$user) {
                $response->getBody()->write(json_encode([
                    'success' => false,
                    'result' => ['error' => 'User not found']
                ]));
                return $response->withStatus(404)->withHeader('Content-Type', 'application/json');
            }
            $this->entityManager->remove($user);
            $this->entityManager->flush();

            $response->getBody()->write(json_encode([
                'success' => true,
                'result' => $user
            ]));
        } else {
            $this->entityManager->createQuery('DELETE FROM App\Models\User')->execute();
            $response->getBody()->write(json_encode([
                'success' => true
            ]));
        }

        return $response->withHeader('Content-Type', 'application/json');
    }
}