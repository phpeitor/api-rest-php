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

            // Validar variables obligatorias
            $required = ['DB_HOST', 'DB_USER', 'DB_NAME'];
            $missing = [];
            foreach ($required as $key) {
                $val = $this->env($key);
                if ($val === null || $val === '') {
                    $missing[] = $key;
                }
            }

            if (count($missing) > 0) {
                $msg = 'ERROR: Faltan variables obligatorias en el archivo .env: ' . implode(', ', $missing) . ".\n";
                $msg .= "Copia .env.example a .env y ajusta los valores, por ejemplo: DB_NAME=bd_test";
                // Mostrar y terminar ejecución
                if (PHP_SAPI === 'cli') {
                    fwrite(STDERR, $msg . PHP_EOL);
                    exit(1);
                }
                header('Content-Type: text/plain; charset=utf-8');
                echo $msg;
                exit(1);
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