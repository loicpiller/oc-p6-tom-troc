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

    private static array $queries = [];

    private const ALLOWED_OPERATORS = [
        '=',
        '!=',
        '<>',
        '<',
        '>',
        '<=',
        '>=',
        'LIKE',
        'NOT LIKE',
    ];

    public function __construct()
    {
        $this->pdo = Database::getConnection();
    }

    public static function getQueries(): array
    {
        return self::$queries;
    }

    private static function logQuery(string $sql, array $params = [])
    {
        self::$queries[] = ['sql' => $sql, 'params' => $params];
    }

    /**
     * Specifies the table to query.
     *
     * @param string $table The table name.
     * @return $this
     */
    public function table(string $table): QueryBuilder
    {
        $this->table = $this->validateIdentifier($table, 'table');
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
        $this->select = array_map(fn(string $column): string => $this->validateSelectExpression($column), $columns);
        return $this;
    }

    /**
     * Adds a WHERE clause to the query.
     *
     * @param string $column The column name.
     * @param string $operator The operator (=, <, >, LIKE, etc.).
     * @param mixed $value The value to compare the column to.
     * @return $this
     */
    public function where(string $column, string $operator, mixed $value): QueryBuilder
    {
        $column = $this->validateIdentifier($column, 'column');
        $operator = $this->validateOperator($operator);

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
        $column = $this->validateIdentifier($column, 'column');
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
        $validatedColumns = array_map(
            fn(string $column): string => $this->validateIdentifier($column, 'column'),
            $columns
        );
        $this->groupBy = array_merge($this->groupBy, $validatedColumns);
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
        $table = $this->validateIdentifier($table, 'table');
        $firstColumn = $this->validateIdentifier($firstColumn, 'column');
        $operator = $this->validateOperator($operator);
        $secondColumn = $this->validateIdentifier($secondColumn, 'column');
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
     * @return array<int, array<string, mixed>> An array of associative arrays representing the rows.
     */
    public function get(): array
    {
        $sql = "SELECT " . implode(', ', $this->select) . " FROM " . $this->table;

        if (!empty($this->joins)) {
            $sql .= " " . implode(' ', $this->joins);
        }

        if (!empty($this->where)) {
            $sql .= " WHERE " . implode(' AND ', $this->where);
        }

        if (!empty($this->orderBy)) {
            $sql .= " ORDER BY " . implode(', ', $this->orderBy);
        }

        if (!empty($this->groupBy)) {
            $sql .= " GROUP BY " . implode(', ', $this->groupBy);
        }

        if ($this->limit > 0) {
            $sql .= " LIMIT $this->limit";
        }

        if ($this->offset > 0) {
            $sql .= " OFFSET $this->offset";
        }

        self::logQuery($sql, $this->parameters);

        // Prepare the query to prevent SQL injection
        $query = $this->pdo->prepare($sql);
        $query->execute($this->parameters);
        $this->where = [];
        $this->parameters = [];
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
        $columnsList = array_keys($data);
        array_walk($columnsList, fn(string $column): string => $this->validateIdentifier($column, 'column'));
        $columns = implode(', ', $columnsList);
        $values = implode(', ', array_fill(0, count($data), '?'));
        $sql = "INSERT INTO $this->table ($columns) VALUES ($values)";

        self::logQuery($sql, array_values($data));

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

        $columnsList = array_keys($data);
        array_walk($columnsList, fn(string $column): string => $this->validateIdentifier($column, 'column'));
        $set = implode(', ', array_map(fn($col) => "$col = ?", $columnsList));
        $sql = "UPDATE $this->table SET $set WHERE " . implode(' AND ', $this->where);

        self::logQuery($sql, [...array_values($data), ...$this->parameters]);

        $stmt = $this->pdo->prepare($sql);
        $ret = $stmt->execute([...array_values($data), ...$this->parameters]);
        $this->where = [];
        $this->parameters = [];
        return $ret;
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

        self::logQuery($sql, $this->parameters);
        $stmt = $this->pdo->prepare($sql);
        $ret = $stmt->execute($this->parameters);
        $this->where = [];
        $this->parameters = [];
        return $ret;
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

        self::logQuery($sql, $this->parameters);

        $query = $this->pdo->prepare($sql);
        $query->execute($this->parameters);

        $this->where = [];
        $this->parameters = [];
        return $query->fetch() ?: null;
    }

    /**
     * Executes a custom SQL query.
     *
     * @param string $sql The full SQL query.
     * @param array $params The parameters for prepared statement.
     * @return array
     */
    public function customQuery(string $sql, array $params = []): array
    {
        self::logQuery($sql, $params);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    private function validateIdentifier(string $identifier, string $context): string
    {
        if (!preg_match('/^[A-Za-z_][A-Za-z0-9_]*(\.[A-Za-z_][A-Za-z0-9_]*)?$/', $identifier)) {
            throw new InvalidArgumentException("Invalid {$context} identifier: {$identifier}");
        }

        return $identifier;
    }

    private function validateSelectExpression(string $expression): string
    {
        if ($expression === '*') {
            return $expression;
        }

        return $this->validateIdentifier($expression, 'column');
    }

    private function validateOperator(string $operator): string
    {
        $operator = strtoupper(trim($operator));

        if (!in_array($operator, self::ALLOWED_OPERATORS, true)) {
            throw new InvalidArgumentException("Invalid SQL operator: {$operator}");
        }

        return $operator;
    }
}
