<body onLoad="javascript:window.print();" onafterprint="this.close();">



    <p>Fecha: <?php echo $infoComandas['Fecha']; ?> </p>
    <p> Hora: <?php echo date("H:i"); ?>hs </p>
    <p> Mesa: <?php echo $infoComandas['Identificador']; ?> </p>
    <p> Moso: <?php echo $infoComandas['Nombre_moso']; ?></p>


    <table width="100%" border="1" cellspacing="0">
        <thead>
            <tr>
                <td bgcolor="#CCCCCC"><b>Cantidad</b></td>
                <td bgcolor="#CCCCCC"><b>Nombre</b></td>

            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($Items as $item) {
                echo '<tr>
                                        <td align="center">1</td>
                                        <td>' . $item["Nombre_item"] . '</td>
                                        
                                    </tr>
                                    ';
            }
            ?>

        </tbody>

    </table>

</body>