<?php
// Arquivo para testar a conexão e verificar se a tabela existe
require_once 'conexao.php';

echo "<h2>Teste de Conexão - PsicoSystem</h2>";

try {
    // Testar conexão
    echo "<p style='color: green;'>✓ Conexão com banco de dados estabelecida!</p>";
    
    // Verificar se a tabela usuarios existe
    $stmt = $pdo->query("SHOW TABLES LIKE 'usuarios'");
    if ($stmt->rowCount() > 0) {
        echo "<p style='color: green;'>✓ Tabela 'usuarios' existe!</p>";
        
        // Mostrar estrutura da tabela
        $stmt = $pdo->query("DESCRIBE usuarios");
        echo "<h3>Estrutura da tabela:</h3>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Nulo</th><th>Chave</th></tr>";
        while ($row = $stmt->fetch()) {
            echo "<tr>";
            echo "<td>" . $row['Field'] . "</td>";
            echo "<td>" . $row['Type'] . "</td>";
            echo "<td>" . $row['Null'] . "</td>";
            echo "<td>" . $row['Key'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        // Contar registros
        $stmt = $pdo->query("SELECT COUNT(*) as total FROM usuarios");
        $total = $stmt->fetch();
        echo "<p>Total de usuários cadastrados: <strong>" . $total['total'] . "</strong></p>";
        
    } else {
        echo "<p style='color: red;'>✗ Tabela 'usuarios' NÃO existe!</p>";
        echo "<p>Execute o seguinte SQL para criar a tabela:</p>";
        echo "<pre>
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
        </pre>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Erro: " . $e->getMessage() . "</p>";
}
?>