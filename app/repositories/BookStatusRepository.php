<?php
namespace App\Repositories;

use MVC\Core\BaseRepository;
use App\Entities\BookStatusEntity;

class BookStatusRepository extends BaseRepository
{
    protected string $table = 'book_status';

    private function hydrate(array $data): BookStatusEntity
    {
        return new BookStatusEntity($data);
    }

    public function findStatusById(int $id): ?BookStatusEntity
    {
        $data = $this->find($id);
        return $data ? $this->hydrate($data) : null;
    }
}
