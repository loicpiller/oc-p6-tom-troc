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

    /** @param array<string, mixed> $data */
    private function hydrate(array $data): BookEntity
    {
        $book = new BookEntity($data);

        if (isset($data['created_at']) && is_string($data['created_at'])) {
            $book->setCreatedAt($data['created_at']);
        }

        $user = $this->userRepository->findUserById($book->getUserId());
        if ($user === null) {
            throw new \Exception("Book corresponding User not found.");
        }
        $book->setUser($user);

        $status = $this->statusRepository->findStatusById($book->getStatusId());
        if ($status === null) {
            throw new \Exception("Book corresponding BookStatus not found.");
        }
        $book->setStatus($status);

        return $book;
    }

    public function findBookById(int $id): ?BookEntity
    {
        $data = $this->find($id);
        return $data ? $this->hydrate($data) : null;
    }

    /** @return array<BookEntity> */
    public function findByUser(int $userId): array
    {
        $rows = $this->db->where('user_id', '=', $userId)->get();
        return array_map(fn($row) => $this->hydrate($row), $rows);
    }

    /**
     * @param int|null $statusId If provided, filters books by status_id.
     * @return array<BookEntity>
     */
    public function getAll(?int $statusId = null): array
    {
        if ($statusId !== null) {
            $this->db->where('status_id', '=', $statusId);
        }
        $books = $this->db->get();
        return array_map(fn($book) => $this->hydrate($book), $books);
    }

    /** @return array<BookEntity> */
    public function search(string $query): array
    {
        $term = '%' . $query . '%';
        /** @var array<int, array<string, mixed>> $rows */
        $rows = $this->db->customQuery(
            "SELECT * FROM book WHERE status_id = 1 AND (title LIKE ? OR author LIKE ?)",
            [$term, $term]
        );
        return array_map(fn($row) => $this->hydrate($row), $rows);
    }

    /** @return array<BookEntity> */
    public function getLast(int $limit): array
    {
        $books = $this->db
            ->orderBy('created_at', 'DESC')
            ->where('status_id', '=', 1)
            ->limit($limit)
            ->get();

        return array_map(fn($book) => $this->hydrate($book), $books);        
    }
}
