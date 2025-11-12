<?php
// Verificar se está logado antes de mostrar o dashboard
if (!isset($_SESSION['usuario_id'])) {
    return;
}
?>

<style>
body {
    font-family: "Lato", sans-serif;
    margin: 0;
}

.sidenav {
    height: 100%;
    width: 0;
    position: fixed;
    z-index: 1000;
    top: 0;
    left: 0;
    background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);
    overflow-x: hidden;
    transition: 0.3s;
    padding-top: 60px;
    box-shadow: 2px 0 10px rgba(0,0,0,0.3);
}

.sidenav-header {
    padding: 0 20px 20px 32px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    margin-bottom: 20px;
}

.sidenav-header h3 {
    color: #fff;
    margin: 0;
    font-size: 24px;
}

.sidenav-header p {
    color: #818181;
    margin: 5px 0 0 0;
    font-size: 14px;
}

.sidenav a {
    padding: 12px 8px 12px 32px;
    text-decoration: none;
    font-size: 20px;
    color: #818181;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: 0.3s;
    border-left: 3px solid transparent;
}

.sidenav a:hover {
    color: #f1f1f1;
    background-color: rgba(255,255,255,0.05);
    border-left-color: #4CAF50;
}

.sidenav a i {
    width: 25px;
    text-align: center;
}

.sidenav .closebtn {
    position: absolute;
    top: 10px;
    right: 25px;
    font-size: 36px;
    margin-left: 50px;
    color: #818181;
    padding: 0;
    border: none;
}

.sidenav .closebtn:hover {
    color: #f1f1f1;
    background: transparent;
}

.sidenav-footer {
    position: absolute;
    bottom: 0;
    width: 100%;
    border-top: 1px solid rgba(255,255,255,0.1);
    padding-top: 10px;
}

.sidenav-footer a {
    color: #ff6b6b;
}

.sidenav-footer a:hover {
    color: #ff5252;
    border-left-color: #ff5252;
}

.menu-toggle {
    position: fixed;
    top: 20px;
    left: 20px;
    font-size: 30px;
    cursor: pointer;
    color: #333;
    z-index: 999;
    background: #fff;
    border: none;
    border-radius: 5px;
    padding: 8px 15px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    transition: 0.3s;
}

.menu-toggle:hover {
    background: #f0f0f0;
    box-shadow: 0 4px 8px rgba(0,0,0,0.3);
}

.main-content {
    transition: margin-left 0.3s;
    padding: 20px;
    margin-left: 0;
}

@media screen and (max-height: 450px) {
    .sidenav {padding-top: 15px;}
    .sidenav a {font-size: 18px;}
}

@media screen and (max-width: 768px) {
    .menu-toggle {
        top: 10px;
        left: 10px;
    }
}
</style>

<!-- Menu Lateral -->
<div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    
    <div class="sidenav-header">
        <h3>PsychoSystem</h3>
        <p><?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></p>
    </div>
    
    <a href="visao_geral.php">
        <i class="fas fa-home"></i>
        <span>Visão Geral</span>
    </a>
    <a href="cadastrar_paciente.php">
        <i class="fas fa-user-plus"></i>
        <span>Novo Paciente</span>
    </a>
    <a href="listar_pacientes.php">
        <i class="fas fa-users"></i>
        <span>Pacientes</span>
    </a>
    
    <div class="sidenav-footer">
        <a href="../index.php">
            <i class="fas fa-sign-out-alt"></i>
            <span>Sair</span>
        </a>
    </div>
</div>

<!-- Botão para abrir o menu -->
<button class="menu-toggle" onclick="openNav()">
    &#9776;
</button>

<script>
function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}

// Fechar o menu ao clicar fora dele
document.addEventListener('click', function(event) {
    const sidenav = document.getElementById("mySidenav");
    const menuToggle = document.querySelector(".menu-toggle");
    
    if (sidenav && menuToggle) {
        const isClickInside = sidenav.contains(event.target) || menuToggle.contains(event.target);
        
        if (!isClickInside && sidenav.style.width === "250px") {
            closeNav();
        }
    }
});

// Fechar com ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const sidenav = document.getElementById("mySidenav");
        if (sidenav && sidenav.style.width === "250px") {
            closeNav();
        }
    }
});
</script>