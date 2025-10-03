<?php

namespace MVC\Core;

use Exception;

abstract class BaseRepository
{
    protected QueryBuilder $db;
    protected string $table;
    protected string $primaryKey = 'id';

    /**
     * Constructs a new instance of the model.
     *
     * @throws Exception If the model does not define a protected $table property.
     */
    public function __construct()
    {
        $this->db = new QueryBuilder();
        if (!isset($this->table)) {
            throw new Exception("Repository must define a protected \$table property.");
        }
        $this->db->table($this->table);
    }

    /**
     * Finds a row by its primary key.
     *
     * @param int $id The primary key value of the row to find.
     * @return array|null The row data as an associative array, or null if not found.
     */
    public function find(mixed $keyValue): ?array
    {
        return $this->db
            ->where($this->primaryKey, '=', $keyValue)
            ->first();
    }

    /**
     * Retrieves all rows from the database.
     *
     * @return array An array of associative arrays representing the rows.
     */
    public function all(): array
    {
        return $this->db->get();
    }

    /**
     * Saves a row to the database.
     *
     * If the row's primary key is set, it will be updated.
     * Otherwise, it will be inserted.
     *
     * @param array $data The data to save.
     * @return bool True if the save was successful, false otherwise.
     */
    public function save(array $data): bool
    {
        if (isset($data[$this->primaryKey])) {
            return $this->db
                ->where($this->primaryKey, '=', $data[$this->primaryKey])
                ->update($data);
        }
        return $this->db->insert($data);
    }

    /**
     * Deletes a row from the database by its primary key.
     *
     * @param mixed $keyValue The primary key value of the row to delete.
     * @return bool True if the deletion was successful, false otherwise.
     */
    public function delete(mixed $keyValue): bool
    {
        return $this->db
            ->where($this->primaryKey, '=', $keyValue)
            ->delete();
    }
}
