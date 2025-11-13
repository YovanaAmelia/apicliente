<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . '/../config/database.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'views/login.php');
    exit();
}
require_once __DIR__ . '/include/header.php';
?>
<style>
    .dashboard-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 20px;
        transition: transform 0.3s;
    }
    

    .card.full-width {
        grid-column: 1 / -1;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .card h3 {
        margin-top: 0;
        color: #2c3e50;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }

    .card h3 i {
        margin-right: 10px;
        color: #3498db;
    }

    .card p {
        margin: 10px 0;
    }

    .card strong {
        color: #e74c3c;
    }

    .quick-actions {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .quick-actions a {
        display: block;
        padding: 10px;
        background: #3498db;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        text-align: center;
        transition: background 0.3s;
    }

    .quick-actions a:hover {
        background: #2980b9;
    }

    .quick-actions a i {
        margin-right: 8px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    table th, table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    table th {
        background: #3498db;
        color: white;
    }

    table th i {
        margin-right: 8px;
    }

    table tr:hover {
        background: #f5f5f5;
    }

    .recent-hotels-container {
        margin-top: 15px;
    }

    .recent-hotels-row {
        display: flex;
        justify-content: space-between;
        gap: 15px;
        margin-bottom: 15px;
        flex-wrap: wrap;
    }

    .hotel-card {
        flex: 1;
        min-width: 200px;
        max-width: 24%;
        background: #f9f9f9;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s;
    }

    .hotel-card:hover {
        transform: translateY(-5px);
    }

    .hotel-card h4 {
        margin-top: 0;
        color: #2c3e50;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
        font-size: 16px;
    }

    .hotel-card h4 i {
        margin-right: 8px;
        color: #3498db;
    }

    .hotel-card p {
        margin: 8px 0;
        font-size: 14px;
    }

    .hotel-card p i {
        margin-right: 8px;
        color: #7f8c8d;
    }
    .nombre {
    display: block;
    text-align: center;
   font-size: 40px;
    font-weight: bold;
    unicode-bidi: isolate;
}
    
</style>


<div class="container fade-in">
    <div class="dashboard-container">
        <h2 class="nombre"><i class="fas fa-hotel"></i> ¡Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre_completo'] ?? $_SESSION['username']); ?>!</h2>
     <div class="card">
        <h3><i class="fas fa-user-shield"></i> Información de la Sesión</h3>
        <p><strong>ID:</strong> <?php echo $_SESSION['user_id']; ?></p>
        <p><strong>Usuario:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
        <p><strong>Rol:</strong> <?php echo strtoupper($_SESSION['rol'] ?? 'ADMIN'); ?></p>
    </div>

         <div class="card">
        <h3><i class="fas fa-rocket"></i> Acciones Rápidas</h3>
        <div class="quick-actions">
             <a href="<?php echo BASE_URL; ?>views/tokens_list.php" class="btn btn-primary">
                    <i class="fas fa-key"></i> Mis Tokens API
                </a>
                 <a href="<?php echo BASE_URL; ?>api_cliente/" class="btn btn-success" target="_blank">
            <i class="fas fa-external-link-alt"></i> Ir al API Cliente
        </a>
            </div>
    </div>
        <!-- Acciones rápidas -->
        

      
    </div>
</div>

<?php require_once __DIR__ . '/include/footer.php'; ?>
