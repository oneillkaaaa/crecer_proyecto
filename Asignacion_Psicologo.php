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

        $sql = $con->prepare("SELECT Id, Nombre_paciente FROM paciente WHERE Psicologo_Asignado IS NULL");
        $sql->execute();
        $pacientes = $sql->fetchAll(PDO::FETCH_ASSOC);

        if (!$pacientes) {
            $errors[] = "Todos los pacientes ya están asignados a un psicólogo.";
        }
    } else {
        $errors[] = 'Error de petición';
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['paciente_id'])) {
    $paciente_id = $_POST['paciente_id'];

    $update_sql = $con->prepare("UPDATE paciente SET Id_Psicologo = ?, Psicologo_Asignado = ? WHERE Id = ?");
    if ($update_sql->execute([$id, $nombre_psicologo, $paciente_id])) {
        $errors[] = "Paciente asignado exitosamente.";
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=$id&token=$token");
        exit;
    } else {
        $errors[] = "Error al asignar paciente.";
    }
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
                <h3 class="text-purple">Asignar pacientes al psicólogo:
                    <?php echo $nombre_psicologo; ?></h3>
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
                                                <form method="POST">
                                                    <input type="hidden" name="paciente_id"
                                                        value="<?php echo $paciente['Id']; ?>">
                                                    <button type="submit" class="btn btn-primary">Asignar al
                                                        psicólogo</button>
                                                </form>
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