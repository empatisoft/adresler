<?php
class Database {
    /**
     * @return null|PDO
     *
     * Veritabanı bağlantısı
     */
    private function connect()
    {
        $db = null;
        if ($db === null) {
            try
            {
                $dsn = 'mysql:host='.DB_SERVER.';dbname='.DB_NAME.';port='.DB_PORT.';charset=utf8';
                $db = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch (PDOException $e)
            {
                echo $e->getMessage();
            }
        }
        return $db;
    }

    public function result($query_string, $params = array()) {
        try {
            $query = $this->connect()->prepare($query_string);
            if(!empty($params)) {
                foreach ($params as $key => $value) {
                    $query->bindParam(':'.$key, $value);
                }
            }
            $query->execute();
            return $query->fetchAll(PDO::FETCH_OBJ);

        } catch(PDOException $e) {
            return $e->getMessage();
        }
    }

}