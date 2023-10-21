<?php 
    include_once 'conexion.php';
    $totales = isset($_POST["totales"]) ? $_POST["totales"] : "";
    $anios = isset($_POST["anios"]) ? $_POST["anios"] : "";

    $consulta="SELECT SUM(venta) as ventas, YEAR(fecha) as anio FROM detalle_fac 
        INNER JOIN encabezado_fac on detalle_fac.codigo=encabezado_fac.codigo
        GROUP BY anio";

    if ($totales && $totales != "" && @floatval($totales)) {
        $consulta= $consulta . " HAVING ventas >= $totales";

        if ($anios && $anios != "") {
            $consulta= $consulta . " AND anio IN(" . implode(',', $anios) . ")";
        }
    } else {
        if ($anios && $anios != "") {
            $consulta= $consulta . " HAVING anio IN(" . implode(',', $anios) . ")";
        }
    }

    $ejecucion= mysqli_query($conexion,$consulta);
    $datos = array();
    
    while($dato=mysqli_fetch_assoc($ejecucion)) {
        $dato_ventas = number_format($dato["ventas"],2,'.','');
        $dato_fecha = $dato["anio"];
        array_push($datos, array($dato_fecha, $dato_ventas));
    }

    echo $resultado = json_encode($datos, JSON_NUMERIC_CHECK);
?>