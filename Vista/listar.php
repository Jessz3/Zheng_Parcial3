<?php
// Vista para listar colaboradores
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Colaboradores</title>
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background: #2c3e50;
            color: white;
        }
        tr:hover {
            background: #f5f5f5;
        }
        .btn {
            padding: 8px 15px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-nuevo {
            background: #27ae60;
            color: white;
        }
        .btn-nuevo:hover {
            background: #229954;
        }
        .btn-editar {
            background: #3498db;
            color: white;
        }
        .btn-eliminar {
            background: #e74c3c;
            color: white;
        }
        .mensaje {
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .mensaje-exito {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .sin-datos {
            text-align: center;
            padding: 30px;
            color: #666;
        }
    </style>
</head>
<body>

<div class="container">

<h2>Listado de Colaboradores</h2>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'ok'): ?>
    <div class="mensaje mensaje-exito">
        ✓ Colaborador guardado exitosamente
    </div>
<?php endif; ?>

<a href="Index.php?accion=nuevo" class="btn btn-nuevo">+ Nuevo Colaborador</a>
<a href="Index.php?accion=perfil_nuevo" class="btn btn-editar">+ Nuevo Perfil Laboral</a>
<a href="Index.php?accion=exportar" class="btn btn-editar">⬇ Exportar Excel</a>

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'perfil_ok'): ?>
    <div class="mensaje mensaje-exito">
        ✓ Perfil laboral guardado exitosamente
    </div>
<?php endif; ?>

<?php if (empty($colaboradores)): ?>
    <div class="sin-datos">
        <p>No hay colaboradores registrados. <a href="Index.php?accion=nuevo">Agregar uno ahora</a></p>
    </div>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Identidad</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Celular</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($colaboradores as $colab): ?>
                <tr>
                    <td><?= htmlspecialchars($colab['id'] ?? '') ?></td>
                    <td><?= htmlspecialchars($colab['identidad'] ?? '') ?></td>
                    <td><?= htmlspecialchars($colab['nombre'] ?? '') ?></td>
                    <td><?= htmlspecialchars($colab['apellido'] ?? '') ?></td>
                    <td><?= htmlspecialchars($colab['email'] ?? '') ?></td>
                    <td><?= htmlspecialchars($colab['celular'] ?? '') ?></td>
                    <td>
                        <a href="Index.php?accion=eliminar&id=<?= $colab['id'] ?>" class="btn btn-eliminar" onclick="return confirm('¿Está seguro?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<!-- FOOTER -->
<footer style="
    margin-top: 30px;
    padding: 15px;
    text-align: center;
    background: #2c3e50;
    color: white;
    border-radius: 8px;
">
    <p>Sistema Colaboradores © <?= date("Y") ?> ITECH Contrataciones. All rights reserved.</p>
</footer>

</div>

</body>
</html>
