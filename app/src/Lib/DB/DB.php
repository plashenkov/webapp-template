<?php

namespace App\Lib\DB;

use PDO;

class DB extends PDO
{
    /** @var array */
    protected $defaultOptions = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
        PDO::ATTR_STATEMENT_CLASS => [Statement::class]
    ];

    /**
     * @inheritdoc
     */
    public function __construct($dsn, $username, $passwd, $options = [])
    {
        parent::__construct(
            $dsn,
            $username,
            $passwd,
            array_replace($this->defaultOptions, $options)
        );
    }

    /**
     * @inheritdoc
     * @return Statement
     */
    public function prepare($statement, $driverOptions = [])
    {
        /** @var Statement $result */
        $result = parent::prepare($statement, $driverOptions);

        return $result;
    }

    /**
     * @inheritdoc
     */
    public function quote($value, $parameterType = PDO::PARAM_STR)
    {
        if (is_null($value)) {
            return 'NULL';
        }

        if (is_bool($value)) {
            return $value ? 1 : 0;
        }

        $intValue = filter_var($value, FILTER_VALIDATE_INT);
        if ($intValue !== false) {
            return $intValue;
        }

        if ($value instanceof \DateTime) {
            // MySQL:
            return parent::quote($value->format('Y-m-d H:i:s'));

            // Oracle:
            // return "TO_DATE('{$value->format('Y-m-d H:i:s')}', 'YYYY-MM-DD HH24:MI:SS')";
        }

        return parent::quote($value, $parameterType);
    }

    /**
     * Compose VALUES construction:
     *   (column1, column2, ...) VALUES (value1, value2, ...)
     * May be used with INSERT INTO table ...
     *
     * @param array $data Data to insert: ['column1' => 'value1', 'column2' => 'value2', ...]
     * @return string
     */
    public function composeValues(array $data)
    {
        $idents = implode(', ', array_keys($data));
        $values = implode(', ', array_map([$this, 'quote'], $data));

        return " ($idents) VALUES ($values) ";
    }

    /**
     * Compose SET construction:
     *   SET column1 = value1, column2 = value2, ...
     * May be used with UPDATE table ...
     *
     * @param array $data Data to update: ['column1' => 'value1', 'column2' => 'value2', ...]
     * @return string
     */
    public function composeSet(array $data)
    {
        $pairs = [];
        foreach ($data as $key => $value) {
            $pairs[] = $key . ' = ' . $this->quote($value);
        }

        return ' SET ' . implode(', ', $pairs) . ' ';
    }
}
