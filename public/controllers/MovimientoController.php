<?php

require_once './models/Cuenta.php';
require_once './models/Movimiento.php';

class MovimientoController
{
    public function CargarDeposito($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $numeroDeCuenta = $parametros['numeroDeCuenta'];
        $tipoDeCuenta = $parametros['tipoDeCuenta'];
        //$moneda = $parametros['moneda'];
        $monto = $parametros['monto'];
        $foto = $_FILES['foto'];

        $cuenta = Cuenta::obtenerUno($numeroDeCuenta, $tipoDeCuenta);
        if($cuenta!= null){

            $movimiento = new Movimiento();
            
            $movimiento->numeroDeCuenta = $numeroDeCuenta;
            $movimiento->monto = $monto;
            $movimiento->tipoDeMovimiento = "deposito";
            
            $movimiento->crearUno();
            
            if (Movimiento::GuardarImagen($foto, $cuenta, $movimiento)) {
                $payload = json_encode(array("mensaje" => "Deposito creado con exito"));
            } else {
                $payload = json_encode(array("mensaje" => "Error en carga de imagen - Deposito creado con exito"));
            }
            Cuenta::actualizarSaldo($numeroDeCuenta, $monto);

        }else{
            $payload = json_encode(array("mensaje" => "La cuenta ingresada no existe"));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    
    public function CargarRetiro($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $numeroDeCuenta = $parametros['numeroDeCuenta'];
        $tipoDeCuenta = $parametros['tipoDeCuenta'];
        $moneda = $parametros['moneda'];
        $monto = $parametros['monto'];

        $cuenta = Cuenta::obtenerUno($numeroDeCuenta, $tipoDeCuenta);
        if($cuenta!= null){
            if($cuenta->saldo<$monto){
                $payload = json_encode(array("mensaje" => "No es posible realizar el retiro ya que el saldo es insuficiente"));
            }else{
                $movimiento = new Movimiento();
                $movimiento->numeroDeCuenta = $numeroDeCuenta;
                $movimiento->monto = $monto;
                $movimiento->tipoDeMovimiento = "retiro";
                
                $movimiento->crearUno();
                $payload = json_encode(array("mensaje" => "Retiro creado con exito"));
            
                Cuenta::actualizarSaldo($numeroDeCuenta, -$monto);
            }
            

        }else{
            $payload = json_encode(array("mensaje" => "La cuenta ingresada no existe"));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
}

?>