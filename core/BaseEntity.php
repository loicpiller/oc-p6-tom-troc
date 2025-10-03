<?php
namespace MVC\Core;

abstract class BaseEntity
{
    /**
     * Constructs a new instance of the entity.
     *
     * @param array $data Optional associative array to initialize the entity's properties.
     */
    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            $this->hydrate($data);
        }
    }

    /**
     * Hydrates the entity with data from an associative array.
     *
     * @param array $data The data to hydrate the entity with.
     */
    public function hydrate(array $data): void
    {
        foreach ($data as $key => $value) {
            $method = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }

    /**
     * Converts a camelCase string to snake_case.
     *
     * @param string $input The camelCase string to convert.
     * @return string The converted snake_case string.
     */
    private function camelToSnake(string $input): string
    {
        return strtolower(preg_replace('/[A-Z]/', '_$0', lcfirst($input)));
    }

    /**
     * Converts the entity to an associative array.
     *
     * @return array The entity's properties as an associative array.
     */
    public function toArray(): array
    {
        $array = [];
        foreach (get_object_vars($this) as $key => $value) {
            $array[$this->camelToSnake($key)] = $value;
        }
        return $array;
    }
}
