<?php
namespace App\Repositories;

use MVC\Core\BaseRepository;
use App\Entities\BookEntity;
use App\Repositories\UserRepository;
use App\Repositories\BookStatusRepository;

class BookRepository extends BaseRepository
{
    protected string $table = 'book';

    private UserRepository $userRepository;
    private BookStatusRepository $statusRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
        $this->statusRepository = new BookStatusRepository();
    }

    private function hydrate(array $data): BookEntity
    {
        $book = new BookEntity($data);

        $user = $this->userRepository->findUserById($book->getUserId());
        $book->setUser($user);

        $status = $this->statusRepository->findStatusById($book->getStatusId());
        $book->setStatus($status);

        return $book;
    }

    public function findBookById(int $id): ?BookEntity
    {
        $data = $this->find($id);
        return $data ? $this->hydrate($data) : null;
    }

    public function findByUser(int $userId): array
    {
        $rows = $this->db->where('user_id', '=', $userId)->get();
        return array_map(fn($row) => $this->hydrate($row), $rows);
    }
}
