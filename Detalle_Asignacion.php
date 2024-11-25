<?php
require 'Config/Configuracion.php';
require 'Config/Conexion.php';
require 'Class/Funciones_Psicologo.php';

$db = new Database();
$con = $db->conectar();

if (!isset($_SESSION['user_id'])) {
    header("Location: Index.php");
    exit();
}

$errors = [];

$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';
if ($id == '' || $token == '') {
    echo 'Error de petición';
    exit;
} else {
    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);
    if ($token == $token_tmp) {

        $sql_psicologo = $con->prepare("SELECT u.Nombre AS Nombre_psicologo 
                                        FROM usuario u
                                        INNER JOIN psicologoS p ON u.Id_Psicologo = p.Id
                                        WHERE u.Id_Psicologo = ?");
        $sql_psicologo->execute([$id]);
        $psicologo = $sql_psicologo->fetch(PDO::FETCH_ASSOC);

        if (!$psicologo) {
            $errors[] = "Psicólogo no encontrado.";
        } else {
            $nombre_psicologo = $psicologo['Nombre_psicologo'];
        }

        $sql = $con->prepare("SELECT Id, Nombre_paciente, Diagnostico FROM paciente WHERE Psicologo_Asignado = ?");
        $sql->execute([$nombre_psicologo]);
        $pacientes = $sql->fetchAll(PDO::FETCH_ASSOC);

    } else {
        $errors[] = 'Error de petición';
        exit;
    }
}

if (empty($pacientes)) {
    $errors[] = "No tiene pacientes asignados";
}
?>

<?php
$current_page = 'Asignaciones';
include 'Header.php';
?>

<main>
    <div class="container-fluid px-4">
        <div class="mt-4 text-end">
            <a href="Asignacion.php" class="no-underline">
                Asignaciones
            </a>
        </div>

        <div class="card border-top-rose shadow h-100 py-2 mb-4 mt-4">
            <div class="card-body">
                <h3 class="text-purple">Pacientes a cargo del psicólogo: <?php echo $nombre_psicologo ?></h3>
                <br>
                <?php Mostrar_Error($errors); ?>
                <div class="row">
                    <?php foreach ($pacientes as $paciente): ?>
                        <div class="col-xl-6 col-md-6 mb-6 mt-4">
                            <div class="card border-left-rose shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-purple text-uppercase mb-1">
                                                <b><?php echo $paciente['Nombre_paciente']; ?></b>
                                            </div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                <?php echo"Diagnostico: ", $paciente['Diagnostico']; ?>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <img src="Img/icons/familia.png" class="small-icon">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'Footer.php'; ?>