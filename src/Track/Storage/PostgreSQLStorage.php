<?php

namespace Track\Storage;

use \PDO;

class PostgreSQLStorage implements StorageInterface
{
    const POSTGRESQL_EVENT_TABLE = 'events';

    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(array $data)
    {
        $stmt = $this->pdo->prepare('INSERT INTO events (data) VALUES (:data)');
        $stmt->bindValue(':data', $this->toHstore($data), PDO::PARAM_STR);
        $stmt->execute();
    }

    public function runNativeQuery(array $query, callable $callback = null)
    {
        $statement = $this->pdo->prepare($query['query']);
        $statement->execute($query['parameters']);

        $data = array();

        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            if (!isset($row['data'])) {
                throw new \PDOException('Result does not contain the "data" field.');
            }

            $row = $this->fromHstore($row['data']);

            if (is_callable($callback)) {
                $row = call_user_func($callback, $row);
            }

            $data[] = $row;
        }

        return $data;
    }

    protected function toHstore(array $data)
    {
        $hstore = array();

        foreach ($data as $key => $value) {
            $hstore[] = $this->escapeHstore($key) . '=>' . $this->escapeHstore($value);
        }

        return implode(',', $hstore);
    }

    protected function fromHstore($string)
    {
        $string = (string) $string;

        if (empty($string)) {
            return null; //or empty array?
        }

        $quotedString = '"[^"\\\\]*(?:\\\\.[^"\\\\]*)*"';
        $unquotedString = '(?:\\\\.|[^\s,])[^\s=,\\\\]*(?:\\\\.[^\s=,\\\\]*|=[^,>])*';
        $regEx = '(' . $quotedString . '|' . $unquotedString . ')\s*=>\s*(' . $quotedString . '|' . $unquotedString . ')';

        if (!preg_match_all('/'.$regEx.'/', $string, $matches, PREG_SET_ORDER)) {
            return null;
        }

        $document = array();

        foreach ($matches as $item) {
            $k = preg_replace('/^"(.*)"$/m', '\1', $item[1]);
            $k = preg_replace('/\\\\(.)/', '\1', $k);

            if ('NULL' === strtoupper($item[2])) {
                $v = null;
            } else {
                $v = preg_replace('/^"(.*)"$/m', '\1', $item[2]);
                $v = preg_replace('/\\\\(.)/', '\1', $v);
            }

            $document[$k] = $v;
        }

        return $document;
    }

    protected function escapeHstore($value)
    {
        if (null === $value) {
            return 'NULL';
        } elseif ("" === $value) {
            return '""';
        } else {
            $value = preg_replace('/(["\\\])/', '\\\\\1', $value);

            return '"'.$value.'"';
        }
    }
}
