<?php

namespace MVC\Core;

use Exception;
use InvalidArgumentException;
use PDO;

class QueryBuilder
{
    private PDO $pdo;
    private string $table;
    private array $select = ['*'];
    private array $where = [];
    private array $parameters = [];
    private array $orderBy = [];
    private array $groupBy = [];
    private array $joins = [];
    private int $limit = 0;
    private int $offset = 0;

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
    public function where(string $column, string $operator, mixed $value): QueryBuilder
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

    /**
     * Specifies the columns to group by.
     *
     * @param string ...$columns The column names to group by.
     * @return $this
     */
    public function groupBy(string ...$columns): QueryBuilder
    {
        $this->groupBy = array_merge($this->groupBy, $columns);
        return $this;
    }

    /**
     * Specifies a join with another table.
     *
     * @param string $table The table to join with.
     * @param string $firstColumn The column on the current table.
     * @param string $operator The operator to use in the ON clause (=, <, >, LIKE, etc.).
     * @param string $secondColumn The column on the joined table.
     * @param string $type The type of join (INNER, LEFT, RIGHT). Defaults to INNER.
     * @return $this
     * @throws InvalidArgumentException If the join type is not INNER, LEFT, or RIGHT.
     */
    public function join(string $table, string $firstColumn, string $operator, string $secondColumn, string $type = 'inner'): QueryBuilder
    {
        $type = strtoupper($type);

        // Validate the join type
        if (!in_array($type, ['INNER', 'LEFT', 'RIGHT'])) {
            throw new InvalidArgumentException("Invalid join type: $type");
        }

        $this->joins[] = "$type JOIN $table ON $firstColumn $operator $secondColumn";
        return $this;
    }

    /**
     * Specifies the maximum number of rows to return.
     *
     * @param int $limit The maximum number of rows to return.
     * @return $this
     */
    public function limit(int $limit): QueryBuilder
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Specifies the number of rows to skip before starting to return rows.
     *
     * @param int $offset The number of rows to skip.
     * @return $this
     */
    public function offset(int $offset): QueryBuilder
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * Fetches rows from the table.
     *
     * @return array An array of associative arrays representing the rows.
     */
    public function get(): array
    {
        $sql = "SELECT " . implode(', ', $this->select) . " FROM " . $this->table;

        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(' AND ', $this->where);
        }

        if (!empty($this->orderBy)) {
            $sql .= " ORDER BY " . implode(', ', $this->orderBy);
        }

        if (!empty($this->groupBy)) {
            $sql .= " GROUP BY " . implode(', ', $this->groupBy);
        }

        if (!empty($this->joins)) {
            $sql .= " " . implode(' ', $this->joins);
        }

        if ($this->limit > 0) {
            $sql .= " LIMIT $this->limit";
        }

        if ($this->offset > 0) {
            $sql .= " OFFSET $this->offset";
        }

        // Prepare the query to prevent SQL injection
        $query = $this->pdo->prepare($sql);
        var_dump($query->queryString); die();
        $query->execute($this->parameters);
        return $query->fetchAll();
    }

    /**
     * Inserts a new row into the table.
     *
     * @param array $data An associative array of column names to values.
     * @return bool True if the insertion was successful, false otherwise.
     */
    public function insert(array $data): bool
    {
        $columns = implode(', ', array_keys($data));
        $values = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO $this->table ($columns) VALUES ($values)";

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array_values($data));
    }

    /**
     * Updates rows in the table.
     *
     * @param array $data An associative array of column names to values.
     * @return bool True if the update was successful, false otherwise.
     * @throws Exception
     */
    public function update(array $data): bool
    {
        if (empty($this->where)) {
            throw new Exception("Update requires at least one WHERE condition to prevent mass updates.");
        }

        $set = implode(', ', array_map(fn($col) => "$col = ?", array_keys($data)));
        $sql = "UPDATE $this->table SET $set WHERE " . implode(' AND ', $this->where);

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([...array_values($data), ...$this->parameters]);
    }

    /**
     * Deletes rows from the table.
     *
     * @return bool True if the deletion was successful, false otherwise.
     * @throws Exception If no WHERE conditions are specified.
     */
    public function delete(): bool
    {
        if (empty($this->where)) {
            throw new Exception("Delete requires at least one WHERE condition to prevent mass deletions.");
        }

        $sql = "DELETE FROM $this->table WHERE " . implode(' AND ', $this->where);
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($this->parameters);
    }

    /**
     * Fetches the first matching row from the database.
     *
     * @return array|null The first matching row, or null if none were found.
     */
    public function first(): ?array
    {
        $sql = "SELECT " . implode(', ', $this->select) . " FROM " . $this->table;

        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(' AND ', $this->where);
        }

        $sql .= " LIMIT 1";

        $query = $this->pdo->prepare($sql);
        $query->execute($this->parameters);

        return $query->fetch() ?: null;
    }
}