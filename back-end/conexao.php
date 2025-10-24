<?php
class Config
{
    private $host = 'sql212.infinityfree.com';
    private $dbname = 'if0_40161566_psychosystem';
    private $user = 'if0_40161566';
    private $pass = 'wilsonlemos44';
    private $pdo = null;

    public function conectar()
    {
        if ($this->pdo === null) {
            try {
                // Criar conexão PDO
                $this->pdo = new PDO(
                    "mysql:host=" . $this->host . ";dbname=" . $this->dbname . ";charset=utf8mb4",
                    $this->user,
                    $this->pass,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (PDOException $e) {
                die("Erro na conexão: " . $e->getMessage());
            }
        }
        return $this->pdo;
    }
}

// Criar instância global da conexão
$config = new Config();
$pdo = $config->conectar();
?>