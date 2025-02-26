<?php

namespace MVC\Core;

use InvalidArgumentException;
use MVC\Core\Database;
use PDO;

class QueryBuilder
{
    private PDO $pdo;
    private string $table;
    private array $select;
    private array $where = [];
    private array $parameters = [];
    private array $orderBy = [];

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    /**
     * Specifies the table to query.
     *
     * @param string $table The table name.
     * @return $this
     */
    public function table(string $table): QueryBuilder
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Selects the specified columns from the table.
     *
     * @param string ...$columns The columns to select.
     * @return $this
     */
    public function select(string ...$columns): QueryBuilder
    {
        $this->select = $columns;
        return $this;
    }

    /**
     * Adds a WHERE clause to the query.
     *
     * @param string $column The column name.
     * @param string $operator The operator (=, <, >, LIKE, etc.).
     * @param string $value The value to compare the column to.
     * @return $this
     */
    public function where(string $column, string $operator, string $value): QueryBuilder
    {
        // Prevent SQL injection
        $this->where[] = "$column $operator ?";
        $this->parameters[] = $value;
        return $this;
    }

    /**
     * Specifies the column and direction of the ORDER BY clause.
     *
     * @param string $column The column name.
     * @param string $direction The direction of the ORDER BY clause (ASC or DESC).
     * @return $this
     * @throws InvalidArgumentException If the direction is not ASC or DESC.
     */
    public function orderBy(string $column, string $direction): QueryBuilder
    {
        $direction = strtoupper($direction);

        // Validate the direction
        if (!in_array($direction, ['ASC', 'DESC'])) {
            throw new InvalidArgumentException("Invalid ORDER BY direction: $direction");
        }

        $this->orderBy[] = "$column $direction";
        return $this;
    }

    public function get(): array
    {
        $sql = "SELECT " . implode(', ', $this->select) . " FROM " . $this->table;

        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(' AND ', $this->where);
        }

        if (!empty($this->orderBy)) {
            $sql .= " ORDER BY " . implode(', ', $this->orderBy);
        }

        // Prepare the query to prevent SQL injection
        $query = $this->pdo->prepare($sql);
        $query->execute($this->parameters);
        return $query->fetchAll();
    }
}