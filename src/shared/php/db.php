<?php

enum QueryType
{
    case SELECT;
    case INSERT;
    case DELETE;
}

class QueryBuilder
{
    private ?QueryType $queryType;
    private string $operationString = '';

    private $bindingValues = [];
    private string $whereString = '';

    private string $joinString = '';
    private string $groupByString = '';

    private function __construct(private string $table, private ?string $alias) {}

    public static function table(string $table, ?string $alias = null): QueryBuilder
    {
        return new QueryBuilder($table, $alias);
    }

    private static function createBindingParameterName(string $name): string
    {
        $randomNumber = rand();
        return ":{$name}{$randomNumber}";
    }

    public function select(array $columns = ['*']): self
    {
        $this->queryType = QueryType::SELECT;

        $this->operationString = 'SELECT ';

        $count = count($columns);

        for ($i = 0; $i < $count; $i++) {
            $column = $columns[$i];
            $this->operationString .=  $column;

            if ($i !== $count - 1) {
                $this->operationString .= ', ';
            }
        }

        $this->operationString .= " FROM {$this->table}";

        if ($this->alias !== null) {
            $this->operationString .= " AS {$this->alias}";
        }

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
            $paramName = QueryBuilder::createBindingParameterName($keys[$i]);

            $keysSQL .= $keys[$i];
            $valuesSQL .= $paramName;

            $this->bindingValues[$paramName] = $values[$i];

            if ($i !== $count - 1) {
                $keysSQL .= ', ';
                $valuesSQL .= ', ';
            }
        }

        $keysSQL .= ')';
        $valuesSQL .= ')';

        $this->operationString =
            "INSERT INTO {$this->table} {$keysSQL} VALUES {$valuesSQL}";

        return $this;
    }

    public function delete(): self
    {
        $this->queryType = QueryType::DELETE;
        $this->operationString = "DELETE FROM {$this->table}";
        return $this;
    }

    public function where(array $where): self
    {
        $this->whereString = ' WHERE ';

        $count = count($where);
        $keys = array_keys($where);
        $values = array_values($where);

        for ($i = 0; $i < $count; $i++) {
            $paramName = QueryBuilder::createBindingParameterName($keys[$i]);
            $this->whereString .= "{$keys[$i]} = {$paramName}";

            $this->bindingValues[$paramName] = $values[$i];

            if ($i !== $count - 1) {
                $this->whereString .= ' AND ';
            }
        }

        return $this;
    }

    public function groupBy(array $columns): self
    {
        $this->groupByString = ' GROUP BY ';

        $count = count($columns);

        for ($i = 0; $i < $count; $i++) {
            $column = $columns[$i];
            $this->groupByString .=  $column;

            if ($i !== $count - 1) {
                $this->groupByString .= ', ';
            }
        }

        return $this;
    }

    private function join(string $joinType, string $table, string $column1, string $column2, ?string $alias): self
    {
        $this->joinString .= " {$joinType} JOIN {$table}";

        if ($alias !== null) {
            $this->joinString .= " AS {$alias}";
        }

        $this->joinString .= " ON {$column1} = {$column2}";

        return $this;
    }

    public function leftJoin(string $table, string $column1, string $column2, ?string $alias): self
    {
        return $this->join('LEFT', $table, $column1, $column2, $alias);
    }

    public function rightJoin(string $table, string $column1, string $column2, ?string $alias): self
    {
        return $this->join('RIGHT', $table, $column1, $column2, $alias);
    }

    public function innerJoin(string $table, string $column1, string $column2, ?string $alias): self
    {
        return $this->join('INNER', $table, $column1, $column2, $alias);
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

        return "{$this->operationString}{$this->joinString}{$this->whereString}{$this->groupByString}";
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

    public function getPDO(): PDO
    {
        return $this->pdo;
    }

    private function executeImpl(QueryBuilder $query): PDOStatement
    {
        $queryString = $query->queryString();
        $bindingParameters = $query->getBindingParameters();

        $stmt = $this->pdo->prepare($queryString);
        $stmt->execute($bindingParameters);
        return $stmt;
    }

    public function execute(QueryBuilder $query): bool
    {
        try {
            $this->executeImpl($query);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function executeAndReturnAll(QueryBuilder $query)
    {
        try {
            $stmt = $this->executeImpl($query);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public function executeAndReturnOne(QueryBuilder $query)
    {
        try {
            $stmt = $this->executeImpl($query);
            return $stmt->fetch();
        } catch (PDOException $e) {
            return [];
        }
    }
}
