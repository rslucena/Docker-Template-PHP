<?php

namespace App\Services;

use PDO;
use Exception;
use PDOException;

/**
 * @method lastInsertId($name = null)
 * @method fetchAll($name = null)
 */
class MySqlService
{

    private PDO $PDO;

    /**
     * Building Mysql class
     *
     * @throws \Exception
     */
    function __construct()
    {
        try {
            $this->PDO = new PDO(
                "mysql:host=".getenv('MYSQL_SERVER').";port=3306;dbname=" . getenv('MYSQL_DATABASE'),
                getenv('MYSQL_USER'),
                getenv('MYSQL_PASSWORD')
            );
            $this->PDO->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
            $this->PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->PDO->setAttribute(PDO::ATTR_ORACLE_NULLS, PDO::NULL_EMPTY_STRING);
            $this->PDO->setAttribute(PDO::ATTR_AUTOCOMMIT, false);
            $this->PDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $Exception) {
            throw new Exception($Exception->getMessage());
        }
    }

    /**
     * Execute a function on the database
     *
     * @param $sql
     * @param null $args
     *
     * @return array|false
     */
    public function execute($sql, $args = null):array|false
    {
        if (!$args) {
            $this->PDO->query($sql);
        }

        $statement = $this->PDO->prepare($sql);

        $statement->execute($args);

        return $statement->fetchAll();
    }

    /**
     * @param string $method
     * @param array $arguments
     *
     * @return mixed
     * @package \Redis
     *
     */
    public function __call(string $method, array $arguments)
    {
        return call_user_func_array(array($this->PDO, $method), $arguments);
    }

}