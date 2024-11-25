<?php
require 'Config/Configuracion.php';
require 'Config/Conexion.php';
require 'Class/Funciones_Paciente.php';

$db = new Database();
$con = $db->conectar();

if (!isset($_SESSION['user_id'])) {
    header("Location: Index.php");
    exit();
}

$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if ($id == '' || $token == '') {
    echo 'Error de petición';
    exit;
} else {
    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);
    if ($token == $token_tmp) {
        $sql = $con->prepare("SELECT * FROM paciente WHERE Id = ?");
        $sql->execute([$id]);
        $paciente = $sql->fetch(PDO::FETCH_ASSOC);

        if (!$paciente) {
            die("Paciente no encontrado.");
        }
    } else {
        echo 'Error de petición';
        exit;
    }
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_paciente = trim($_POST['NombrePaciente']);
    $edad_paciente = trim($_POST['EdadPaciente']);
    $sexo_paciente = trim($_POST['SexoPaciente']);
    $fecha_nacimiento = trim($_POST['FechaNacimiento']);
    $diagnostico = trim($_POST['Diagnostico']);
    $nombre_apoderado = trim($_POST['NombreApoderado']);
    $edad_apoderado = trim($_POST['EdadApoderado']);
    $dni_apoderado = trim($_POST['DNIApoderado']);
    $correo_apoderado = trim($_POST['CorreoApoderado']);
    $celular_apoderado = trim($_POST['CelularApoderado']);
    $relacion_apoderado = trim($_POST['RelacionApoderado']);

    if (Validar_Nulo([$nombre_paciente, $edad_paciente, $sexo_paciente, $fecha_nacimiento, $diagnostico, $nombre_apoderado, $edad_apoderado, $dni_apoderado, $correo_apoderado, $celular_apoderado, $relacion_apoderado])) {
        $errors[] = "Debe completar todos los campos";
    }

    if (count($errors) == 0) {
        $sql = $con->prepare("UPDATE paciente SET Nombre_paciente = ?, Edad_paciente = ?, Sexo_paciente = ?, Fecha_nacimiento = ?, Diagnostico = ?, Nombre_apoderado = ?, Edad_apoderado = ?, Dni_apoderado = ?, Correo_apoderado = ?, Celular_apoderado = ?, Relacion_apoderado = ? WHERE Id = ?");
        $result = $sql->execute([$nombre_paciente, $edad_paciente, $sexo_paciente, $fecha_nacimiento, $diagnostico, $nombre_apoderado, $edad_apoderado, $dni_apoderado, $correo_apoderado, $celular_apoderado, $relacion_apoderado, $id]);

        if ($result) {
            header("Location: Paciente.php");
            exit;
        } else {
            $errors[] = "Error al actualizar el paciente";
        }
    }
}
?>

<?php
$current_page = 'Paciente';
include 'Header.php';
?>

<main>
    <div class="container-fluid px-4">
        <div class="mt-4 text-end">
            <a href="Paciente.php" class="no-underline">/ Paciente</a>
        </div>

        <div class="card border-top-rose shadow h-100 py-2 mb-4 mt-4">
            <div class="card-body">
                <h3 class="text-purple">Editar Paciente Niño/Adolescente</h3>
                <br>
                <?php Mostrar_Error($errors); ?>
                <form class="row g-3" action="Editar_Paciente.php?id=<?php echo $id; ?>&token=<?php echo $token; ?>" method="post" autocomplete="off">

                    <div class="col-md-6">
                        <label for="NombrePaciente"><span class="text-danger">*</span>Nombres y Apellidos</label>
                        <input type="text" name="NombrePaciente" id="NombrePaciente" class="form-control" value="<?php echo htmlspecialchars($paciente['Nombre_paciente']); ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="EdadPaciente"><span class="text-danger">*</span>Edad</label>
                        <input type="number" name="EdadPaciente" id="EdadPaciente" class="form-control" value="<?php echo htmlspecialchars($paciente['Edad_paciente']); ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="SexoPaciente"><span class="text-danger">*</span>Sexo</label>
                        <select name="SexoPaciente" id="SexoPaciente" class="form-control">
                            <option value="Masculino" <?php echo $paciente['Sexo_paciente'] == 'Masculino' ? 'selected' : ''; ?>>Masculino</option>
                            <option value="Femenino" <?php echo $paciente['Sexo_paciente'] == 'Femenino' ? 'selected' : ''; ?>>Femenino</option>
                            <option value="Otro" <?php echo $paciente['Sexo_paciente'] == 'Otro' ? 'selected' : ''; ?>>Otro</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="FechaNacimiento"><span class="text-danger">*</span>Fecha de nacimiento</label>
                        <input type="date" name="FechaNacimiento" id="FechaNacimiento" class="form-control" value="<?php echo htmlspecialchars($paciente['Fecha_nacimiento']); ?>">
                    </div>

                    <div class="col-md-12">
                        <label for="Diagnostico"><span class="text-danger">*</span>Diagnóstico</label>
                        <textarea name="Diagnostico" id="Diagnostico" class="form-control" rows="4"><?php echo htmlspecialchars($paciente['Diagnostico']); ?></textarea>
                    </div>

                    <h3 class="text-purple mt-4">Datos del Apoderado</h3>

                    <div class="col-md-6">
                        <label for="NombreApoderado"><span class="text-danger">*</span>Nombres y Apellidos</label>
                        <input type="text" name="NombreApoderado" id="NombreApoderado" class="form-control" value="<?php echo htmlspecialchars($paciente['Nombre_apoderado']); ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="EdadApoderado"><span class="text-danger">*</span>Edad</label>
                        <input type="number" name="EdadApoderado" id="EdadApoderado" class="form-control" value="<?php echo htmlspecialchars($paciente['Edad_apoderado']); ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="DNIApoderado"><span class="text-danger">*</span>DNI</label>
                        <input type="number" name="DNIApoderado" id="DNIApoderado" class="form-control" value="<?php echo htmlspecialchars($paciente['Dni_apoderado']); ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="CorreoApoderado"><span class="text-danger">*</span>Correo electrónico</label>
                        <input type="email" name="CorreoApoderado" id="CorreoApoderado" class="form-control" value="<?php echo htmlspecialchars($paciente['Correo_apoderado']); ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="CelularApoderado"><span class="text-danger">*</span>Celular</label>
                        <input type="number" name="CelularApoderado" id="CelularApoderado" class="form-control" value="<?php echo htmlspecialchars($paciente['Celular_apoderado']); ?>">
                    </div>

                    <div class="col-md-6">
                        <label for="RelacionApoderado"><span class="text-danger">*</span>Relación con el paciente</label>
                        <select name="RelacionApoderado" id="RelacionApoderado" class="form-control">
                            <option value="Madre" <?php echo $paciente['Relacion_apoderado'] == 'Madre' ? 'selected' : ''; ?>>Madre</option>
                            <option value="Padre" <?php echo $paciente['Relacion_apoderado'] == 'Padre' ? 'selected' : ''; ?>>Padre</option>
                            <option value="Tutor legal" <?php echo $paciente['Relacion_apoderado'] == 'Tutor legal' ? 'selected' : ''; ?>>Tutor legal</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-purpura text-white">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include 'Footer.php'; ?>
