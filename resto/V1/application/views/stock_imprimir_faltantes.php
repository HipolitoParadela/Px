<body onLoad="javascript:window.print();" onafterprint="this.close();">


<h1>Listado de productos en falta</h1>
    <p>Fecha: <?php echo date("d/m/Y"); ?> </p>
    <p> Hora: <?php echo date("H:i"); ?>hs </p>
    <table WIDTH="100%" >
        <thead>
            <tr>
                <td><b>   Producto</b</td>
                <td><b> Faltante</b</td>
                <td><b> Actual</b</td>
                <td><b> Ideal</b</td>
                <td><b> Costo</b</td>
            </tr>
        </thead>
        <tbody>
            <?php
                foreach ($infoComandas as $item) 
                {
                    
                    $faltante = $item["Cant_ideal"]- $item["Cant_actual"];
                    $precioCosto = $item["Precio_costo"] * $faltante;
                    echo '<tr>
                            <td>' . $item["Nombre_item"] . '</td>
                            <td>' .  $faltante . '</td>
                            <td>' . $item["Cant_actual"] . '</td>
                            <td>' . $item["Cant_ideal"] . '</td>
                                <td>$' . $precioCosto . '</td>
                        </tr>';
                }
            ?>
        </tbody>
    </table>
    <hr>
<h5>Px Resto - Un software de www.pxsistemas.com</h5>
</body>