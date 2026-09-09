<?php

enum QueryType
{
    case SELECT;
}

class QueryBuilder
{

    private array $columns = ['*'];
    private ?QueryType $queryType;
    private string $table;
    private ?string $where = null;

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
        $this->columns = $columns;
        return $this;
    }

    public function where(string $where): self
    {
        $this->where = $where;
        return $this;
    }

    public function queryString(): string
    {
        if (is_null($this->queryType)) {
            throw new Exception("No query type has been specified");
        }

        $sql = '';

        if ($this->queryType === QueryType::SELECT) {
            $sql .= 'SELECT';

            foreach ($this->columns as $column) {
                $sql .= ' ' . $column;
            }

            $sql .= ' FROM ';
            $sql .= $this->table;

            if (!is_null($this->where)) {
                $sql .= ' WHERE ' . $this->where;
            }
        }

        return $sql;
    }
}

class RelationalDatabase
{
    private $pdo = null;

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
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function executeAndReturnOne(QueryBuilder $query)
    {
        $stmt = $this->pdo->prepare($query->queryString());
        $stmt->execute();
        return $stmt->fetch();
    }
}
