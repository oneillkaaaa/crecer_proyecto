<?php
require 'Config/Configuracion.php';
require 'Config/Conexion.php';

$db = new Database();
$con = $db->conectar();

if (!isset($_SESSION['user_id'])) {
    header("Location: Index.php");
    exit();
}

$sql = $con->prepare("SELECT Id, Nombre, Apellido FROM psicologos");
$sql->execute();
$resultado = $sql->fetchAll(PDO::FETCH_ASSOC);

?>

<?php
$current_page = 'Asignaciones';
include 'Header.php';
?>
<main>
    <div class="container-fluid px-4">
        <div class="card border-top-rose shadow h-100 py-2 mb-4 mt-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center ">
                    <h3 class="text-purple">Asignaciones de psicólogos <img src="Img/icons/medico.png" class="small-icon"></h3>
                </div>
                <br>
                <table id="employeeTable" class="table table-bordered">
                    <thead class="table-rose text-white">
                        <tr>
                            <th>Orden</th>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Asignar Pacientes</th>
                            <th>Ver Pacientes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultado as $row) { ?>
                            <tr>
                                <td><?php echo $row['Id'] ?></td>
                                <td><?php echo $row['Nombre'] ?></td>
                                <td><?php echo $row['Apellido'] ?></td>
                                <td class="text-center">
                                    <a href="Asignacion_Psicologo.php?id=<?php echo $row['Id'] ?>&token=<?php echo hash_hmac('sha1', $row['Id'], KEY_TOKEN) ?>"
                                        class="btn btn-purpura btn-sm text-white">
                                        <i class="fa-solid fa-user-plus"></i>
                                    </a>
                                </td>
                                <td class="text-center">
                                    <a href="Detalle_Asignacion.php?id=<?php echo $row['Id'] ?>&token=<?php echo hash_hmac('sha1', $row['Id'], KEY_TOKEN) ?>"
                                        class="btn btn-primary btn-sm">
                                        <i class="fa-solid fa-people-group"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>
        </link>
</main>

<?php include 'Footer.php'; ?>