<?php

namespace App\Lib;

use PDO;

/**
 * Класс работы с базой данных через PDO 
 * @var \PDO $db переменная в которой хранится обьект PDO 
 * @var string $CHARSET кодировка для настройки PDO 
 * @var int[] $PDO_OPTIONS массив настроек для PDO
 */

class Db
{
    protected $db;
    private $CHARSET = 'utf8';
    private $PDO_OPTIONS = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ];

    
    /**
     * Подключаем файл конфига и создаем подключение к базе данных
     */
    function __construct() {
        $config = require $_SERVER["DOCUMENT_ROOT"].'/../src/config/db.php';
		$this->db = new PDO('mysql:host='.$config['host'].';dbname='.$config['name'].';charset='.$this->CHARSET.'', $config['user'], $config['password'],$this->PDO_OPTIONS);
    }

    /**
     * Отладочная функция для вывода ошибок
     * @param mixed $query обьект запроса
     * return bool true
     */

    private function dbCheckError($query){
        $errInfo = $query->errorInfo();
        if ($errInfo[0] !== PDO::ERR_NONE){
            echo $errInfo[0];
            exit();
        }
        return true;
    }

    /**
     * Отправить sql запрос
     * @param string $sql sql запрос командой
     * @param array $params параметры для подготовки запроса
     */

    public function SendSqlRequest($sql,$params=[])
    {
        $query = $this->db->prepare($sql);
        $result = $query->execute($params);
        $this->dbCheckError($query);

        $id = $this->db->lastInsertId();
        $pattern = "/INSERT INTO\s+([^\s]+)/i";

        preg_match($pattern, $sql, $matches);
        
        if (isset($matches[1])) {
            $tableName = $matches[1];
            $selectQuery = $this->db->prepare("SELECT * FROM {$tableName} WHERE id = :id");
            $selectQuery->execute(["id" => $id]);

            return $selectQuery->fetch(PDO::FETCH_ASSOC);;
        }else {
            return $result;
        }
    }

    /**
     * Отправить sql запрос и получить все элемемнеты из базы
     * @param string $sql sql запрос командой
     * @param array $params параметры для подготовки запроса
     * return array $query->fetchAll()
     */

    public function GetAllSqlRequest($sql,$params=[])
    {
        $query = $this->db->prepare($sql);

        $query->execute($params);
        $this->dbCheckError($query);
        
        return $query->fetchAll();
    }

    /**
     * Отправить sql запрос и получить один элемемнет из базы
     * @param string $sql sql запрос командой
     * @param array $params параметры для подготовки запроса
     * return array $query->fetch()
     */

    public function GetOneSqlRequest($sql,$params=[])
    {
        $query = $this->db->prepare($sql);

        $query->execute($params);
        $this->dbCheckError($query);
        return $query->fetch();
    }
}
