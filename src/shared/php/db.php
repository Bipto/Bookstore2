<?php

enum QueryType
{
    case SELECT;
    case INSERT;
}

class QueryBuilder
{
    private ?QueryType $queryType;
    private string $selectString = '';
    private string $insertString = '';
    private string $table;

    private $bindingValues = [];
    private string $whereString = '';

    private function __construct(string $table)
    {
        $this->table = $table;
    }

    public static function table(string $table): QueryBuilder
    {
        return new QueryBuilder($table);
    }

    public function select(array $columns = ['*']): self
    {
        $this->queryType = QueryType::SELECT;

        $this->selectString = 'SELECT ';

        $count = count($columns);

        for ($i = 0; $i < $count; $i++) {
            $column = $columns[$i];
            $this->selectString .=  $column;

            if ($i !== $count - 1) {
                $this->selectString .= ', ';
            }
        }

        $this->selectString .= 'FROM ' . $this->table;

        return $this;
    }

    public function insert(array $values): self
    {
        $this->queryType = QueryType::INSERT;

        $keysSQL = '(';
        $valuesSQL = '(';

        $count = count($values);
        $keys = array_keys($values);
        $values = array_values($values);

        for ($i = 0; $i < $count; $i++) {
            $paramName = ":{$keys[$i]}";
            $keysSQL .= "{$keys[$i]}";
            $valuesSQL .= "{$paramName}";

            $this->bindingValues[$paramName] = $values[$i];

            if ($i !== $count - 1) {
                $keysSQL .= ', ';
                $valuesSQL .= ', ';
            }
        }

        $keysSQL .= ')';
        $valuesSQL .= ')';

        $this->insertString = "INSERT INTO {$this->table}{$keysSQL} VALUES {$valuesSQL}";

        return $this;
    }

    public function where(array $where): self
    {
        $this->whereString = 'WHERE ';

        $count = count($where);
        $keys = array_keys($where);
        $values = array_values($where);

        for ($i = 0; $i < $count; $i++) {
            $paramName = ":{$keys[$i]}";
            $this->whereString .= "{$keys[$i]} = {$paramName}";

            $this->bindingValues[$paramName] = $values[$i];

            if ($i !== $count - 1) {
                $this->selectString .= ' AND ';
            }
        }

        return $this;
    }

    public function getBindingParameters(): array
    {
        return $this->bindingValues;
    }

    public function queryString(): string
    {
        if (is_null($this->queryType)) {
            throw new Exception("No query type has been specified");
        }

        $sql = '';

        if ($this->queryType === QueryType::SELECT) {
            $sql .= $this->selectString . ' ' . $this->whereString;
        } else if ($this->queryType === QueryType::INSERT) {
            $sql .= $this->insertString .= ' ' . $this->whereString;
        }

        return $sql;
    }
}

class RelationalDatabase
{
    private ?PDO $pdo = null;

    public function __construct(
        string $driver,
        string $host,
        string $port,
        string $db,
        string $user,
        string  $password,
        string $charset
    ) {

        $dsn = "$driver:host=$host;port=$port;dbname=$db";

        if ($driver === 'mysql') {
            $dsn .= ";charset=$charset";
        }

        $this->pdo = new PDO(
            $dsn,
            $user,
            $password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }

    public function executeAndReturnAll(QueryBuilder $query)
    {
        $stmt = $this->pdo->prepare($query->queryString());
        $stmt->execute($query->getBindingParameters());
        return $stmt->fetchAll();
    }

    public function executeAndReturnOne(QueryBuilder $query)
    {
        $stmt = $this->pdo->prepare($query->queryString());
        $stmt->execute($query->getBindingParameters());
        return $stmt->fetch();
    }
}
