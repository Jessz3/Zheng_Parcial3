<?php
// Verifica que $cat esté definida
if (!isset($cat)) {
    die("Error: Los catálogos no se cargaron correctamente.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro de Colaborador</title>
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
        input[type="email"],
        input[type="number"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            box-sizing: border-box;
        }
        input:focus,
        select:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 5px rgba(52, 152, 219, 0.3);
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
        .error {
            color: #e74c3c;
            font-size: 12px;
            margin-top: 3px;
        }
    </style>
</head>
<body>

<div class="container">

<h2>Registro de Colaborador</h2>

<form method="POST" action="Index.php?accion=guardar" onsubmit="return validarFormulario()">

<div class="grid">

    <div class="form-group">
        <label>Identidad <span style="color: red;">*</span></label>
        <input name="identidad" type="text" placeholder="Ej: 8-123-456" required>
    </div>

    <div class="form-group">
        <label>Nombre <span style="color: red;">*</span></label>
        <input name="nombre" type="text" placeholder="Nombre" required>
    </div>

    <div class="form-group">
        <label>Apellido <span style="color: red;">*</span></label>
        <input name="apellido" type="text" placeholder="Apellido" required>
    </div>

    <div class="form-group">
        <label>Edad <span style="color: red;">*</span></label>
        <input name="edad" type="number" placeholder="Edad" min="18" max="70" required>
    </div>

    <div class="form-group">
        <label>Sexo</label>
        <select name="sexo_id">
            <option value="">Seleccione</option>
            <?php if (!empty($cat["sexo"])): ?>
                <?php foreach ($cat["sexo"] as $s): ?>
                    <option value="<?= htmlspecialchars($s['id'] ?? '') ?>"><?= htmlspecialchars($s['nombre'] ?? $s['Nombre'] ?? '') ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Tipo de Sangre</label>
        <select name="tipo_sangre_id">
            <option value="">Seleccione</option>
            <?php if (!empty($cat["sangre"])): ?>
                <?php foreach ($cat["sangre"] as $t): ?>
                    <option value="<?= htmlspecialchars($t['id'] ?? '') ?>"><?= htmlspecialchars($t['Nombre'] ?? $t['nombre'] ?? '') ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Ruta</label>
        <select name="ruta_id">
            <option value="">Seleccione</option>
            <?php if (!empty($cat["rutas"])): ?>
                <?php foreach ($cat["rutas"] as $r): ?>
                    <option value="<?= htmlspecialchars($r['id'] ?? '') ?>"><?= htmlspecialchars($r['Nombre'] ?? $r['nombre'] ?? '') ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Ocupación</label>
        <select name="ocupacion_id">
            <option value="">Seleccione</option>
            <?php if (!empty($cat["ocupaciones"])): ?>
                <?php foreach ($cat["ocupaciones"] as $o): ?>
                    <option value="<?= htmlspecialchars($o['C_OCUP'] ?? '') ?>"><?= htmlspecialchars($o['OCUPACION'] ?? '') ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Estado Civil</label>
        <select name="estado_civil_id">
            <option value="">Seleccione</option>
            <?php if (!empty($cat["estadoCivil"])): ?>
                <?php foreach ($cat["estadoCivil"] as $e): ?>
                    <option value="<?= htmlspecialchars($e['id'] ?? '') ?>"><?= htmlspecialchars($e['nombre'] ?? '') ?></option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Nacionalidad</label>
        <input name="nacionalidad" type="text" placeholder="Nacionalidad">
    </div>

    <div class="form-group">
        <label>Email <span style="color: red;">*</span></label>
        <input name="email" type="email" placeholder="correo@ejemplo.com" required>
    </div>

    <div class="form-group">
        <label>Celular <span style="color: red;">*</span></label>
        <input name="celular" type="text" placeholder="Ej: 61234567" required>
    </div>

</div>

<button type="submit">Guardar Colaborador</button>
<a href="Index.php?accion=listar" style="margin-left: 10px; padding: 12px 30px; background: #95a5a6; color: white; border-radius: 5px; text-decoration: none; display: inline-block;">Cancelar</a>

</form>

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

<script>
function validarFormulario() {
    const edad = document.querySelector('input[name="edad"]').value;
    
    if (edad < 18 || edad > 70) {
        alert("La edad debe estar entre 18 y 70 años");
        return false;
    }
    
    const celular = document.querySelector('input[name="celular"]').value;
    if (!/^[0-9]{7,8}$/.test(celular)) {
        alert("El celular debe tener entre 7 y 8 dígitos");
        return false;
    }
    
    return true;
}
</script>

</body>
</html>