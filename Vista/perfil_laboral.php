<?php
// Vista para crear un perfil laboral
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil Laboral</title>
    <link rel="stylesheet" href="css/estilos.css">
    <style>
        .form-group {
            margin-bottom: 15px;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #2c3e50;
        }
        input[type="text"],
        input[type="number"],
        input[type="date"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }
        button {
            background: #27ae60;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        button:hover {
            background: #229954;
        }
    </style>
</head>
<body>

<div class="container">

<h2>Nuevo Perfil Laboral</h2>

<form method="POST" action="Index.php?accion=guardar_perfil" onsubmit="return validarPerfil()">

<div class="grid">

    <div class="form-group">
        <label>Colaborador <span style="color: red;">*</span></label>
        <select name="colaborador_id" required>
            <option value="">Seleccione</option>
            <?php foreach ($colaboradores as $colab): ?>
                <option value="<?= htmlspecialchars($colab['id']) ?>"><?= htmlspecialchars($colab['identidad'] . ' - ' . $colab['nombre'] . ' ' . $colab['apellido']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Puesto (Ocupación) <span style="color: red;">*</span></label>
        <input type="text" name="puesto" placeholder="Puesto" required>
    </div>

    <div class="form-group">
        <label>Salario <span style="color: red;">*</span></label>
        <input type="number" name="salario" placeholder="Salario" min="0" step="0.01" required>
    </div>

    <div class="form-group">
        <label>Tipo de Empleado <span style="color: red;">*</span></label>
        <select name="tipo_empleado_id" required>
            <option value="">Seleccione</option>
            <?php foreach ($catalogos['tipoEmpleado'] as $tipo): ?>
                <option value="<?= htmlspecialchars($tipo['id']) ?>"><?= htmlspecialchars($tipo['Nombre']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Planilla <span style="color: red;">*</span></label>
        <select name="planilla_id" required>
            <option value="">Seleccione</option>
            <?php foreach ($catalogos['planillas'] as $planilla): ?>
                <option value="<?= htmlspecialchars($planilla['id']) ?>"><?= htmlspecialchars($planilla['Nombre']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Fecha Inicio <span style="color: red;">*</span></label>
        <input type="date" name="fecha_inicio" required>
    </div>

    <div class="form-group">
        <label>Fecha Fin</label>
        <input type="date" name="fecha_fin">
    </div>

    <div class="form-group">
        <label>Motivo de Terminación</label>
        <select name="motivo_terminacion">
            <option value="">Seleccione si aplica</option>
            <?php foreach ($catalogos['motivosTerminacion'] as $motivo): ?>
                <option value="<?= htmlspecialchars($motivo['MOTIVO']) ?>"><?= htmlspecialchars($motivo['MOTIVO']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

</div>

<button type="submit">Guardar Perfil Laboral</button>
<a href="Index.php?accion=listar" style="margin-left: 10px; padding: 12px 30px; background: #95a5a6; color: white; border-radius: 5px; text-decoration: none; display: inline-block;">Cancelar</a>

</form>

</div>

<script>
function validarPerfil() {
    const salario = document.querySelector('input[name="salario"]').value;
    if (parseFloat(salario) <= 0) {
        alert('El salario debe ser mayor a cero.');
        return false;
    }

    const fechaInicio = document.querySelector('input[name="fecha_inicio"]').value;
    const fechaFin = document.querySelector('input[name="fecha_fin"]').value;
    if (fechaFin && fechaFin < fechaInicio) {
        alert('La fecha fin no puede ser anterior a la fecha inicio.');
        return false;
    }

    return true;
}
</script>

</body>
</html>
