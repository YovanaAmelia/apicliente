<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/TokenController.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: ' . BASE_URL . 'views/login.php');
    exit();
}
$tokenController = new TokenController();
$tokens = $tokenController->listarTokens();
require_once __DIR__ . '/include/header.php';
?>
<style>
    .container {
        margin: 20px;
        padding: 0;
    }
    .dashboard-container {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        padding: 25px;
        margin-top: 20px;
    }
    .list-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #eee;
    }
    .list-header h2 {
        color: #2c3e50;
        margin: 0;
        font-size: 24px;
    }
    .list-header h2 i {
        margin-right: 10px;
        color: #3498db;
    }
    .btn {
        padding: 10px 15px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
    }
    .btn i {
        margin-right: 8px;
    }
    .btn-warning {
        background-color: #f39c12;
        color: white;
    }
    .btn-warning:hover {
        background-color: #e67e22;
    }
    .table-container {
        overflow-x: auto;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 0;
    }
    .table th {
        background-color: #3498db;
        color: white;
        padding: 12px 15px;
        text-align: left;
        font-weight: 500;
    }
    .table th i {
        margin-right: 8px;
    }
    .table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
        color: #333;
    }
    .table tr:hover {
        background-color: #f5f5f5;
    }
    .table-actions {
        display: flex;
        gap: 10px;
    }
    .fade-in {
        animation: fadeIn 0.5s ease-in-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    code {
        font-family: 'Courier New', Courier, monospace;
        background: #f0f0f0;
        padding: 6px 10px;
        border-radius: 3px;
        font-size: 14px;
    }
    @media (max-width: 768px) {
        .list-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        .table-actions {
            flex-direction: column;
            gap: 5px;
        }
        .btn {
            width: 100%;
            justify-content: flex-start;
        }
    }
</style>
<div class="container fade-in">
    <div class="dashboard-container">
        <div class="list-header">
            <h2>
                <i class="fas fa-key"></i> Mis Tokens API
            </h2>
        </div>
        <p style="color: #7f8c8d; margin-bottom: 20px;">Aquí puedes ver y actualizar tus tokens de acceso a la API.</p>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th><i class="fas fa-key"></i> Token</th>
                        <th><i class="fas fa-cogs"></i> Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tokens as $token): ?>
                        <tr>
                            <td><code><?= substr($token['token'], 0, 55) ?></code></td>
                            <td class="table-actions">
                                <a href="<?= BASE_URL ?>views/token_form.php?edit=<?= $token['id_client_api'] ?>" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Actualizar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/include/footer.php'; ?>
