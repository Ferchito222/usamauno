<table cellpadding="0" cellspacing="0" border="1" style="width: 100%; height: auto; font-size: 10px; font-family: Arial, Helvetica, sans-serif; ">
    <tr>
        <td style="width:50%; text-align: center"><strong>COBERTURAS</strong></td>
        <td style="width:25%; text-align: center"><strong>SUMA ASEGURADA</strong></td>
        <td style="width:25%; text-align: center"><strong>FRANQUICIAS DEDUCIBLE</strong></td>
    </tr>
    <tr>
        <td style="width:50%;"><strong>SECCIÓN I:</strong> Seguro de Responsabilidad Civil Extracontractual</td>
        <td style="width:25%;text-align: center">$us. 20.000.-</td>
        <td style="width:25%;text-align: center"></td>
    </tr>
    <tr>
        <td style="width:50%;"><strong>SECCIÓN II:</strong> Seguro de Pérdida Total por Robo </td>
        <td style="width:25%;text-align: center">VALOR DEL VEHÍCULO  </td>
        <td style="width:25%;text-align: center"></td>
    </tr>
    <tr>
        <td style="width:50%;"><strong>SECCIÓN III:</strong> Seguro de Pérdida Total por Accidente</td>
        <td style="width:25%;text-align: center">VALOR DEL VEHÍCULO  </td>
        <td style="width:25%;text-align: center"></td>
    </tr>

    <!--Liviano particular-->
    @if($value->category->category=='CA' && $value->use=='PR')
        <tr>
            <td style="width:50%;"><strong>SECCIÓN IV:</strong> Seguro de Daños Propios </td>
            <td style="width:25%;text-align: center">VALOR DEL VEHÍCULO  </td>
            <td style="width:25%;text-align: center">50 $US.</td>
        </tr>
        <tr>
            <td style="width:50%;"><strong>SECCIÓN V:</strong> Seguro de Conmoción Civil, Huelgas y Daño Malicioso.</td>
            <td style="width:25%;text-align: center">VALOR DEL VEHÍCULO  </td>
            <td style="width:25%;text-align: center">50 $US.</td>
        </tr>
        <tr>
            <td style="width:50%;"><strong>SECCIÓN VI:</strong> Seguro de Robo Parcial </td>
            <td style="width:25%;text-align: center">VALOR DEL VEHÍCULO  </td>
            <td style="width:25%;text-align: center">80%</td>
        </tr>
        <tr>
            <td style="width:50%;"><strong>SECCIÓN VII:</strong> Seguro de Accidentes Personales a ocupantes de vehículos: </td>
            <td style="width:25%;text-align: center">Conductor y máximo 8 ocupantes</td>
            <td style="width:25%;text-align: center"></td>
        </tr>
        <tr>
            <td style="width:50%;">- Muerte Accidental e Invalidez permanente</td>
            <td style="width:25%;text-align: center">$us. 10.000,00  c/u</td>
            <td style="width:25%;text-align: center"></td>
        </tr>
        <tr>
            <td style="width:50%;">- Gastos Médicos</td>
            <td style="width:25%;text-align: center">$us. 2.000,00  c/u</td>
            <td style="width:25%;text-align: center"></td>
        </tr>
        <tr>
            <td style="width:50%;">EXTRATERRITORIALIDAD</td>
            <td style="width:25%;text-align: center">VALOR DEL VEHÍCULO</td>
            <td style="width:25%;text-align: center"></td>
        </tr>
        <tr>
            <td style="width:50%;">RESPONSABILIDAD CIVIL CONSECUENCIAL</td>
            <td style="width:25%;text-align: center">$us. 3.000.-</td>
            <td style="width:25%;text-align: center"></td>
        </tr>
        <tr>
            <td style="width:50%;">TERRORISMO</td>
            <td style="width:25%;text-align: center">VALOR DEL VEHÍCULO</td>
            <td style="width:25%;text-align: center"></td>
        </tr>
    @endif

    <!--Liviano public-->
    @if($value->category->category=='CA' && $value->use=='PU')
        <tr>
            <td style="width:50%;"><strong>SECCIÓN IV:</strong> Seguro de Daños Propios </td>
            <td style="width:25%;text-align: center">VALOR DEL VEHÍCULO  </td>
            <td style="width:25%;text-align: center">La Paz, Santa Cruz $us. 100.- <br />Resto del País $us. 50.- </td>
        </tr>
        <tr>
            <td style="width:50%;"><strong>SECCIÓN V:</strong> Seguro de Conmoción Civil, Huelgas y Daño Malicioso.</td>
            <td style="width:25%;text-align: center">VALOR DEL VEHÍCULO  </td>
            <td style="width:25%;text-align: center">La Paz, Santa Cruz $us. 100.- <br />Resto del País $us. 50.- </td>
        </tr>
        <tr>
            <td style="width:50%;"><strong>SECCIÓN VI:</strong> Seguro de Robo Parcial </td>
            <td style="width:25%;text-align: center">VALOR DEL VEHÍCULO  </td>
            <td style="width:25%;text-align: center">80%</td>
        </tr>
        <tr>
            <td style="width:50%;"><strong>SECCIÓN VII:</strong> Seguro de Accidentes Personales a ocupantes de vehículos: </td>
            <td style="width:25%;text-align: center">Sólo para el conductor</td>
            <td style="width:25%;text-align: center"></td>
        </tr>
        <tr>
            <td style="width:50%;">- Muerte Accidental e Invalidez permanente</td>
            <td style="width:25%;text-align: center">$us. 10.000,00  c/u</td>
            <td style="width:25%;text-align: center"></td>
        </tr>
        <tr>
            <td style="width:50%;">- Gastos Médicos</td>
            <td style="width:25%;text-align: center">$us. 2.000,00  c/u</td>
            <td style="width:25%;text-align: center"></td>
        </tr>
        <tr>
            <td style="width:50%;">EXTRATERRITORIALIDAD</td>
            <td style="width:25%;text-align: center">VALOR DEL VEHÍCULO</td>
            <td style="width:25%;text-align: center"></td>
        </tr>
        <tr>
            <td style="width:50%;">RESPONSABILIDAD CIVIL CONSECUENCIAL</td>
            <td style="width:25%;text-align: center">$us. 3.000.-</td>
            <td style="width:25%;text-align: center"></td>
        </tr>
        <tr>
            <td style="width:50%;">TERRORISMO</td>
            <td style="width:25%;text-align: center">VALOR DEL VEHÍCULO</td>
            <td style="width:25%;text-align: center"></td>
        </tr>
    @endif

    <!--semipesado particular-->
    @if($value->category->category!='CA' && $value->use=='PR')
        <tr>
            <td style="width:50%;"><strong>SECCIÓN IV:</strong> Seguro de Daños Propios </td>
            <td style="width:25%;text-align: center">VALOR DEL VEHÍCULO  </td>
            <td style="width:25%;text-align: center">La Paz, Santa Cruz $us. 250 .- <br />Resto del País $us. 200.- </td>
        </tr>
        <tr>
            <td style="width:50%;"><strong>SECCIÓN V:</strong> Seguro de Conmoción Civil, Huelgas y Daño Malicioso.</td>
            <td style="width:25%;text-align: center">VALOR DEL VEHÍCULO  </td>
            <td style="width:25%;text-align: center">La Paz, Santa Cruz $us. 250.- <br />Resto del País $us. 200.- </td>
        </tr>
        <tr>
            <td style="width:50%;"><strong>SECCIÓN VI:</strong> Seguro de Robo Parcial </td>
            <td style="width:25%;text-align: center">VALOR DEL VEHÍCULO  </td>
            <td style="width:25%;text-align: center">80%</td>
        </tr>
        <tr>
            <td style="width:50%;"><strong>SECCIÓN VII:</strong> Seguro de Accidentes Personales a ocupantes de vehículos: </td>
            <td style="width:25%;text-align: center">Conductor y 1 Ocupante</td>
            <td style="width:25%;text-align: center"></td>
        </tr>
        <tr>
            <td style="width:50%;">- Muerte Accidental e Invalidez permanente</td>
            <td style="width:25%;text-align: center">$us. 5.000,00  c/u</td>
            <td style="width:25%;text-align: center"></td>
        </tr>
        <tr>
            <td style="width:50%;">- Gastos Médicos</td>
            <td style="width:25%;text-align: center">$us. 1.500,00  c/u</td>
            <td style="width:25%;text-align: center"></td>
        </tr>
        <tr>
            <td style="width:50%;">EXTRATERRITORIALIDAD</td>
            <td style="width:25%;text-align: center">VALOR DEL VEHÍCULO</td>
            <td style="width:25%;text-align: center"></td>
        </tr>
        <tr>
            <td style="width:50%;">RESPONSABILIDAD CIVIL CONSECUENCIAL</td>
            <td style="width:25%;text-align: center">$us. 3.000.-</td>
            <td style="width:25%;text-align: center"></td>
        </tr>
        <tr>
            <td style="width:50%;">TERRORISMO</td>
            <td style="width:25%;text-align: center">VALOR DEL VEHÍCULO</td>
            <td style="width:25%;text-align: center"></td>
        </tr>
    @endif

    <!--semipesado publico-->
    @if($value->category->category!='CA' && $value->use=='PU')
        <tr>
            <td style="width:50%;"><strong>SECCIÓN IV:</strong> Seguro de Daños Propios </td>
            <td style="width:25%;text-align: center">VALOR DEL VEHÍCULO  </td>
            <td style="width:25%;text-align: center">La Paz, Santa Cruz $us. 300.- <br />Resto del País $us. 200.-</td>
        </tr>
        <tr>
            <td style="width:50%;"><strong>SECCIÓN V:</strong> Seguro de Conmoción Civil, Huelgas y Daño Malicioso.</td>
            <td style="width:25%;text-align: center">VALOR DEL VEHÍCULO  </td>
            <td style="width:25%;text-align: center">La Paz, Santa Cruz $us. 300.- <br />Resto del País $us. 200.-</td>
        </tr>
        <tr>
            <td style="width:50%;"><strong>SECCIÓN VI:</strong> Seguro de Robo Parcial </td>
            <td style="width:25%;text-align: center">VALOR DEL VEHÍCULO  </td>
            <td style="width:25%;text-align: center">80%</td>
        </tr>
        <tr>
            <td style="width:50%;"><strong>SECCIÓN VII:</strong> Seguro de Accidentes Personales a ocupantes de vehículos: </td>
            <td style="width:25%;text-align: center">Conductor y 1 Ocupante</td>
            <td style="width:25%;text-align: center"></td>
        </tr>
        <tr>
            <td style="width:50%;">- Muerte Accidental e Invalidez permanente</td>
            <td style="width:25%;text-align: center">$us. 3.000,00  c/u</td>
            <td style="width:25%;text-align: center"></td>
        </tr>
        <tr>
            <td style="width:50%;">- Gastos Médicos</td>
            <td style="width:25%;text-align: center">$us. 600,00  c/u</td>
            <td style="width:25%;text-align: center"></td>
        </tr>
        <tr>
            <td style="width:50%;">EXTRATERRITORIALIDAD</td>
            <td style="width:25%;text-align: center">VALOR DEL VEHÍCULO</td>
            <td style="width:25%;text-align: center"></td>
        </tr>
        <tr>
            <td style="width:50%;">RESPONSABILIDAD CIVIL CONSECUENCIAL</td>
            <td style="width:25%;text-align: center">$us. 3.000.-</td>
            <td style="width:25%;text-align: center"></td>
        </tr>
        <tr>
            <td style="width:50%;">TERRORISMO</td>
            <td style="width:25%;text-align: center">VALOR DEL VEHÍCULO</td>
            <td style="width:25%;text-align: center"></td>
        </tr>
    @endif
</table>