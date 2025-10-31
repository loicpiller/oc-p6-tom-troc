<?php
namespace App\Repositories;

use MVC\Core\BaseRepository;
use App\Entities\UserEntity;

class UserRepository extends BaseRepository
{
    protected string $table = 'user';

    private function  hydrate(array $data): UserEntity
    {
        return new UserEntity($data);
    }

    public function findUserByEmail(string $email): ?UserEntity
    {
        $userData = $this->db->where('email', '=', $email)->first();
        if (null === $userData) {
            return null;
        }
        return $this->hydrate($userData);
    }

    public function findUserById(int $id): ?UserEntity
    {
        $userData = $this->find($id);

        if (null === $userData) {
            return null;
        }

        return $this->hydrate($userData);
    }
}
