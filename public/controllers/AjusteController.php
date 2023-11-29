<?php

require_once './models/Ajuste.php';
require_once './models/Movimiento.php';

class AjusteController
{


    public function AjustarMovimiento($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $idMovimiento = $parametros['idMovimiento'];
        $motivo = $parametros['motivo'];
        $monto = $parametros['monto'];

        $movimiento = Movimiento::obtenerUno($idMovimiento);
        if ($movimiento != null && ($monto <= $movimiento->monto)) {
            $tipo = $movimiento->tipoDeMovimiento;

            if ($tipo == "deposito") {
                if(Cuenta::actualizarSaldo($movimiento->numeroDeCuenta, -$monto)>0){
                    $ajuste = new Ajuste();
                    $ajuste->numeroDeCuenta = $movimiento->numeroDeCuenta;
                    $ajuste->montoAjustado = $monto;
                    $ajuste->motivo = $motivo;
                    $ajuste->idMovimiento = $movimiento->id;
                    $ajuste->crearUno();
                    Movimiento::actualizarMovimiento($idMovimiento, -$monto);
                    $payload = json_encode(array("mensaje" => "Ajuste realizado"));
                }else{
                    $payload = json_encode(array("mensaje" => "Cuenta inactiva"));
                }
                
            } else {


                if(Cuenta::actualizarSaldo($movimiento->numeroDeCuenta, $monto)>0){
                    $ajuste = new Ajuste();
                    $ajuste->numeroDeCuenta = $movimiento->numeroDeCuenta;
                    $ajuste->montoAjustado = $monto;
                    $ajuste->motivo = $motivo;
                    $ajuste->idMovimiento = $movimiento->id;
                    $ajuste->crearUno();
                    Movimiento::actualizarMovimiento($idMovimiento, -$monto);
                    $payload = json_encode(array("mensaje" => "Ajuste realizado"));
                }else{
                    $payload = json_encode(array("mensaje" => "Cuenta inactiva"));
                }

                
            }
        } else if ($movimiento != null && $monto > $movimiento->monto) {
            $payload = json_encode(array("mensaje" => "El monto ingresado es mayor al monto del movimiento"));
        } else if ($movimiento == null) {
            $payload = json_encode(array("mensaje" => "El movimiento seleccionado no existe"));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
 
}
