<?php

function Validar_Nulo(array $parametros)
{
    foreach ($parametros as $parametro) {
        if (strlen(trim($parametro)) < 1) {
            return true;
        }
    }
    return false;
}

function Registrar_Paciente(array $datos, $con)
{
    $sql = $con->prepare("INSERT INTO  paciente (Nombre_paciente, Edad_paciente, Sexo_paciente, Fecha_nacimiento, Diagnostico, Nombre_apoderado, Edad_apoderado, Dni_apoderado, Correo_apoderado, Celular_apoderado, Relacion_apoderado) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
    if ($sql->execute($datos)) {
        return $con->lastInsertId();
    }
    return 0;
}
function Mostrar_Error(array $errors)
{
    if (count($errors) > 0) {
        echo '<div class="alert alert-warning alert-dismissible fade show" role="alert"><ul>';
        foreach ($errors as $error) {
            echo '<li>' . $error . '</li>';
        }
        echo '</ul>';
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
    }
}