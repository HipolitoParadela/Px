<body onLoad="javascript:window.print();" onafterprint="this.close();">


    <table width="100%">
        <tr>
            <td width="20%">
                <p>Fecha: <?php echo $infoComandas['Fecha']; ?> </p>
                <p> Hora: <?php echo date("H:i"); ?>hs </p>
                <p> Mesa: <?php echo $infoComandas['Identificador']; ?> </p>
                <p> Moso: <?php echo $infoComandas['Nombre_moso']; ?></p>
            </td>
            <td rowspan="2">
                <table width="100%" border="1" cellspacing="0">
                    <thead>
                        <tr>
                            <td bgcolor="#CCCCCC"><b>Cantidad</b></td>
                            <td bgcolor="#CCCCCC"><b>Nombre</b></td>
                            <td bgcolor="#CCCCCC"><b>Precio</b></td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        foreach ($Items as $item) {
                            echo '<tr>
                                        <td align="center">1</td>
                                        <td>' . $item["Nombre_item"] . '</td>
                                        <td align="right">$ ' . $item["Precio_venta"] . '</td>
                                    </tr>
                                    ';
                        }

                        ?>
                        <tr>
                            <td colspan="2" align="right">Valor cuenta</td>
                            <td align="right"><b>$ <?php echo $infoComandas['Valor_cuenta']; ?></b></td>
                        </tr>


                    </tbody>
                    <?php
                    if ($infoComandas['Valor_descuento'] > 0) {
                        echo '<tfoot>
                                <tr>
                                    <td colspan="2" align="right">Valor Descuento</td>
                                    <td align="right"><b>$' .  $infoComandas["Valor_descuento"] . '</b></td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Valor final</td>
                                    <td align="right"><b>$ ' .   $Cuenta . '</b></td>
                                </tr>
                            </tfoot>';
                    }

                    ?>

                </table>
            </td>
        </tr>
        <tr>
            <td width="20%"></td>
        </tr>
    </table>
</body>