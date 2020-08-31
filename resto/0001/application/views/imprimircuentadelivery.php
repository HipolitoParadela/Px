<body onLoad="javascript:window.print();" onafterprint="this.close();">
   
    
   <table width="100%">
    <tr>
        <td width="30%">
            <p>Fecha: <?php echo $infoDelivery['FechaHora_pedido']; ?> </p>
            <p>Cliente: <?php echo $infoDelivery['Nombre_cliente']; ?> </p>
            <p>Dirección: <?php echo $infoDelivery['Direccion']; ?> </p>
            <p>Teléfono: <?php echo $infoDelivery['Telefono']; ?> </p>
            <p>Repartidor: <?php echo $infoDelivery['Nombre_repartidor']; ?></p>
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
                            foreach ($Items as $item) 
                            {
                                echo'<tr>
                                        <td align="center">1</td>
                                        <td>'.$item["Nombre_item"].'</td>
                                        <td align="right">$ '.$item["Precio_venta"].'</td>
                                    </tr>
                                    ';
                            }
                        ?>
                        <tr>
                            <td colspan="2" align="right">Valor cuenta</td>
                            <td align="right"><b>$ <?php echo $infoDelivery['Valor_cuenta']; ?></b></td>
                        </tr>
                    </tbody>
                    <?php 
                        if ($infoDelivery['Valor_descuento'] > 0) 
                        {
                            echo'<tfoot>
                                <tr>
                                    <td colspan="2" align="right">Valor Descuento</td>
                                    <td align="right"><b>$'.$infoDelivery["Valor_descuento"].'</b></td>
                                </tr>
                                <tr>
                                    <td colspan="2" align="right">Valor final</td>
                                    <td align="right"><b>$'. $Cuenta. '</b></td>
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