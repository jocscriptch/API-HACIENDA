<!DOCTYPE html>
<html>
<head>
    <title>API HACIENDA CR</title>
</head>
<body>

<h1>Consulta de Información Personal</h1>

<form method="GET">
    <label for="cedula">Número de Cédula:</label>
    <input type="text" id="cedula" name="cedula" pattern="[1-7][0-9]{8}" title="El primer dígito debe estar entre 1 y 7, y la cédula debe tener 9 dígitos." required>
    <button type="submit" name="buscar">Buscar</button>
</form>

<?php
if (isset($_GET['buscar'])) {
    $cedula = $_GET['cedula'];

    if (preg_match('/^[1-7][0-9]{8}$/', $cedula)) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.hacienda.go.cr/fe/ae?identificacion={$cedula}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $data = json_decode($response, true);

            echo "<h2>Información Personal:</h2>";
            echo "<p>Nombre: " . $data['nombre'] . "</p>";
            echo "<p>Tipo de Identificación: " . $data['tipoIdentificacion'] . "</p>";
            echo "<p>Regimen: " . $data['regimen']['descripcion'] . "</p>";
            echo "<p>Situación Morosa: " . $data['situacion']['moroso'] . "</p>";
            echo "<p>Situación Omisa: " . $data['situacion']['omiso'] . "</p>";
            echo "<p>Estado: " . $data['situacion']['estado'] . "</p>";
            echo "<p>Administración Tributaria: " . $data['situacion']['administracionTributaria'] . "</p>";
            echo "<p>Mensaje: " . $data['situacion']['mensaje'] . "</p>";
        }
    } else {
        echo "<p>La cédula ingresada no cumple con el formato válido.</p>";
    }
}
?>

</body>
</html>
