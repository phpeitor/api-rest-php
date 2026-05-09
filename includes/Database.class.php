<?php
    class Database{
        private static $envLoaded = false;

        private function loadEnv(){
            if (self::$envLoaded) {
                return;
            }

            $autoload = __DIR__ . '/../vendor/autoload.php';
            if (file_exists($autoload)) {
                require_once($autoload);
            }

            if (class_exists('Dotenv\\Dotenv')) {
                $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
                $dotenv->safeLoad();
            }

            self::$envLoaded = true;
        }

        private function env($key, $default = null){
            if (array_key_exists($key, $_ENV)) {
                return $_ENV[$key];
            }

            if (array_key_exists($key, $_SERVER)) {
                return $_SERVER[$key];
            }

            $value = getenv($key);
            if ($value !== false) {
                return $value;
            }

            return $default;
        }

        public function getConnection(){
            $this->loadEnv();

            $host = $this->env('DB_HOST', 'localhost');
            $user = $this->env('DB_USER', 'root');
            $password = $this->env('DB_PASSWORD', '');
            $database = $this->env('DB_NAME');
            $charset = $this->env('DB_CHARSET', 'utf8mb4');

            if (!$database) {
                die('ERROR: Falta configurar DB_NAME en el archivo .env');
            }

            $hostDB = "mysql:host=".$host.";dbname=".$database.";charset=".$charset.";";

            try{
                $connection = new PDO($hostDB, $user, $password);
                $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $connection;
            } catch(PDOException $e){
                die("ERROR: ".$e->getMessage());
            }

        }
    }
?>