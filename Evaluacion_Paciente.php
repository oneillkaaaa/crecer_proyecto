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
        $sql = $con->prepare("SELECT ph.Id, ph.IdPaciente, ph.titulo, ph.comentario, ph.Fecha_registro, ps.Nombre AS Nombre_psicologo, ps.Apellido AS Apellido_psicologo FROM paciente_historia ph INNER JOIN psicologos ps on idPsicologo = ps.Id WHERE IdPaciente = ? ORDER BY ph.Fecha_registro DESC");
        $sql->execute([$id]);
        $evaluaciones = $sql->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo 'Error de petición';
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = trim($_POST['Titulo']);
    $comentario = trim($_POST['Comentario']);
    $fecha = trim($_POST['Fecha']);
    $evaluacion_id = isset($_POST['EvaluacionId']) ? trim($_POST['EvaluacionId']) : null;

    if (Validar_Nulo([$titulo, $comentario, $fecha])) {
        $errors[] = "Debe completar todos los campos obligatorios.";
    }

    if (count($errors) == 0) {
        if ($evaluacion_id) {
            // Actualizar evaluación existente
            $sql = $con->prepare("UPDATE paciente_historia SET titulo = ?, comentario = ?, Fecha_registro = ? WHERE Id = ?");
            $result = $sql->execute([$titulo, $comentario, $fecha, $evaluacion_id]);
        } else {
            // Crear nueva evaluación
            $sql = $con->prepare("INSERT INTO paciente_historia (IdPaciente, titulo, comentario, Fecha_registro, idPsicologo) VALUES (?,?,?,?,?)");
            $result = $sql->execute([$id, $titulo, $comentario, $fecha, $_SESSION['user_id']]);
        }

        if ($result) {
            header("Location: Evaluacion_Paciente.php?id=$id&token=$token");
            exit();
        } else {
            $errors[] = "Error al guardar los datos.";
        }
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
            <a href="Asignacion.php?id=<?php echo $id; ?>&token=<?php echo $token; ?>" class="no-underline">
                Asignaciones / Asignación
            </a>
        </div>
        <div class="card border-top-rose shadow h-100 py-2 mb-4 mt-4">
            <div class="card-body">
                <h3 class="text-purple">Historial de evaluaciones</h3>
                <br>
                <div class="accordion" id="accordionExample">
                    <?php
                    if (empty($evaluaciones)) {
                        echo '<p>No hay evaluaciones disponibles.</p>';
                    } else {
                        for ($i = 0; $i < count($evaluaciones); $i++) {
                            $id_evaluacion = $evaluaciones[$i]['Id'];
                            $id_paciente = $evaluaciones[$i]['IdPaciente'];
                            $titulo = $evaluaciones[$i]['titulo'];
                            $comentario = $evaluaciones[$i]['comentario'];
                            $fecha_registro = $evaluaciones[$i]['Fecha_registro'];
                            $nombre_psicologo = $evaluaciones[$i]['Nombre_psicologo'];
                            $apellido_psicologo = $evaluaciones[$i]['Apellido_psicologo'];
                    ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading_<?php echo $i; ?>">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse_<?php echo $i; ?>" aria-expanded="false"
                                        aria-controls="collapse_<?php echo $i; ?>">
                                        <?php echo $titulo . " - " . date("d/m/Y", strtotime($fecha_registro)); ?>
                                    </button>
                                </h2>
                                <div id="collapse_<?php echo $i; ?>" class="accordion-collapse collapse"
                                    aria-labelledby="heading_<?php echo $i; ?>" data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        <p><?php echo $comentario; ?></p>
                                        <p><b>Psicologo: </b><?php echo $nombre_psicologo . " " . $apellido_psicologo; ?></p>
                                        <button class="btn btn-purpura text-white edit-button" data-id="<?php echo $id_evaluacion; ?>"
                                            data-title="<?php echo $titulo; ?>" data-comment="<?php echo $comentario; ?>"
                                            data-date="<?php echo $fecha_registro; ?>">Editar</button>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

        <div class="card border-top-rose shadow h-100 py-2 mb-4 mt-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <h3 id="formTitle" class="text-purple">Agregar evaluación</h3>
                    <button type="button" id="cancelButton" class="d-none btn btn-purpura text-white edit-button"> X </button>
                </div>
                <br>
                <?php Mostrar_Error($errors); ?>
                <form class="row g-3" id="evaluationForm"
                    action="Evaluacion_Paciente.php?id=<?php echo $id; ?>&token=<?php echo $token; ?>" method="post"
                    autocomplete="off">
                    <input type="hidden" name="EvaluacionId" id="EvaluacionId">
                    <input type="hidden" name="id" id="id" value="<?php echo $id; ?>">
                    <input type="hidden" name="token" id="token" value="<?php echo $token; ?>">
                    <div class="col-md-8">
                        <label for="Titulo"><span class="text-danger">*</span> Resumen: </label>
                        <input type="text" name="Titulo" id="Titulo" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="Fecha"><span class="text-danger">*</span> Fecha: </label>
                        <input type="date" name="Fecha" id="Fecha" class="form-control">
                    </div>
                    <div class="col-md-12">
                        <label for="Comentario"> Evaluación</label>
                        <textarea type="text" name="Comentario" id="Comentario" class="form-control" maxlength="400"> </textarea>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-purpura text-white">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    document.querySelectorAll('.edit-button').forEach(button => {
        button.addEventListener('click', function() {
            // Cambiar el formulario al modo edición
            document.getElementById('formTitle').textContent = 'Editar evaluación';
            document.getElementById('EvaluacionId').value = this.dataset.id;
            document.getElementById('Titulo').value = this.dataset.title;
            document.getElementById('Comentario').value = this.dataset.comment;
            document.getElementById('Fecha').value = this.dataset.date.substring(0, 10);
            document.getElementById('cancelButton').classList.replace('d-none', 'd-block');
        });
    });

    // Resetear formulario al estado inicial
    document.getElementById('cancelButton').addEventListener('click', function() {
        document.getElementById('formTitle').textContent = 'Agregar evaluación';
        document.getElementById('Titulo').value = '';
        document.getElementById('Comentario').value = '';
        document.getElementById('Fecha').value = '';
        document.getElementById('EvaluacionId').value = '';
        document.getElementById('cancelButton').classList.replace('d-block', 'd-none');
    });

    // Validación del formulario
    document.getElementById('evaluationForm').addEventListener('submit', function(event) {
        if (!document.getElementById('Titulo').value || !document.getElementById('Comentario').value || !document.getElementById('Fecha').value) {
            event.preventDefault();
            alert('Debe completar todos los campos obligatorios.');
        }
    });
</script>

<?php include 'Footer.php'; ?>