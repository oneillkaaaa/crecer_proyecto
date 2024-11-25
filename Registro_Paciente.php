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

$errors = [];

if (!empty($_POST)) {

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

        $id = Registrar_Paciente([
            $nombre_paciente,
            $edad_paciente,
            $sexo_paciente,
            $fecha_nacimiento,
            $diagnostico,
            $nombre_apoderado,
            $edad_apoderado,
            $dni_apoderado,
            $correo_apoderado,
            $celular_apoderado,
            $relacion_apoderado
        ], $con);

        if ($id > 0) {
            header("Location: Paciente.php");
            exit;
        } else {
            $errors[] = "Error al registrar paciente";
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
            <a href="Paciente.php" class="no-underline">Paciente</a>
        </div>

        <div class="card border-top-rose shadow h-100 py-2 mb-4 mt-4">
            <div class="card-body">
                <h3 class="text-purple">Datos del Paciente Niño/Adolescente</h3>
                <br>
                <?php Mostrar_Error($errors); ?>
                <form class="row g-3" action="Registro_Paciente.php" method="post" autocomplete="off">

                    <div class="col-md-6">
                        <label for="NombrePaciente"><span class="text-danger">*</span>Nombres y Apellidos</label>
                        <input type="text" name="NombrePaciente" id="NombrePaciente" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="EdadPaciente"><span class="text-danger">*</span>Edad</label>
                        <input type="number" name="EdadPaciente" id="EdadPaciente" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="SexoPaciente"><span class="text-danger">*</span>Sexo</label>
                        <select name="SexoPaciente" id="SexoPaciente" class="form-control">
                            <option value="">Seleccione</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Otro">Otro</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="FechaNacimiento"><span class="text-danger">*</span>Fecha de nacimiento</label>
                        <input type="date" name="FechaNacimiento" id="FechaNacimiento" class="form-control">
                    </div>

                    <div class="col-md-12">
                        <label for="Diagnostico"><span class="text-danger">*</span>Diagnóstico</label>
                        <textarea name="Diagnostico" id="Diagnostico" class="form-control" rows="4"></textarea>
                    </div>

                    <h3 class="text-purple mt-4">Datos del Apoderado</h3>

                    <div class="col-md-6">
                        <label for="NombreApoderado"><span class="text-danger">*</span>Nombres y Apellidos</label>
                        <input type="text" name="NombreApoderado" id="NombreApoderado" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="EdadApoderado"><span class="text-danger">*</span>Edad</label>
                        <input type="number" name="EdadApoderado" id="EdadApoderado" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="DNIApoderado"><span class="text-danger">*</span>DNI</label>
                        <input type="number" name="DNIApoderado" id="DNIApoderado" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="CorreoApoderado"><span class="text-danger">*</span>Correo electrónico</label>
                        <input type="email" name="CorreoApoderado" id="CorreoApoderado" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="CelularApoderado"><span class="text-danger">*</span>Celular</label>
                        <input type="number" name="CelularApoderado" id="CelularApoderado" class="form-control">
                    </div>

                    <div class="col-md-6">
                        <label for="RelacionApoderado"><span class="text-danger">*</span>Relación con el
                        paciente</label>
                        <select name="RelacionApoderado" id="RelacionApoderado" class="form-control">
                            <option value="">Seleccione</option>
                            <option value="Madre">Madre</option>
                            <option value="Padre">Padre</option>
                            <option value="Tutor legal">Tutor legal</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-purpura text-white">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php include 'Footer.php'; ?>