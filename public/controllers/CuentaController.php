<?php

require_once './models/Cuenta.php';

class CuentaController
{
    
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        
        $foto = $_FILES['foto'];
        $nombre = strtolower($parametros['nombre']);
        $apellido = strtolower($parametros['apellido']);
        $tipoDocumento = strtolower($parametros['tipoDocumento']);
        $documento = $parametros['documento'];
        $email = $parametros['email'];
        $tipoDeCuenta = strtoupper($parametros['tipoDeCuenta']);
        $moneda = strtoupper(substr($tipoDeCuenta, 2));
        $saldo = $parametros['saldo'];

        $cuenta = Cuenta::obtenerCuentaExistente($nombre, $apellido, $tipoDocumento, $documento, $email, $tipoDeCuenta, $moneda);
        if($cuenta!= null){
            Cuenta::actualizarSaldo($cuenta->numeroDeCuenta, $saldo);
            $payload = json_encode(array("mensaje" => "Cuenta existente - Saldo actualizado"));
        }else{
            $cuenta = new Cuenta();
            
            $cuenta->nombre = $nombre;
            $cuenta->apellido = $apellido;
            $cuenta->tipoDocumento = $tipoDocumento;
            $cuenta->documento = $documento;
            $cuenta->email = $email;
            $cuenta->tipoDeCuenta = $tipoDeCuenta;
            $cuenta->moneda = $moneda;
            $cuenta->saldo = $saldo;

            if ($cuenta->GuardarImagen($foto)) {
                $payload = json_encode(array("mensaje" => "Cuenta creada con exito"));
            } else {
                $payload = json_encode(array("mensaje" => "Error en carga de imagen - Cuenta creada con exito"));
            }

            $cuenta->crearUno();

        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }


    public function TraerUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        
        $tipoDeCuenta = $parametros['tipoDeCuenta'];
        $numeroDeCuenta = $parametros['numeroDeCuenta'];

        $cuenta = Cuenta::obtenerUno($numeroDeCuenta, $tipoDeCuenta);
        if (!$cuenta) {
            $payload = json_encode(array("mensaje" => "No existe la cuenta seleccionada"));
        } else {
            if ($cuenta->tipoDeCuenta != $tipoDeCuenta) {
                $payload = json_encode(array("mensaje" => "Tipo de cuenta incorrecto"));
            } else {
                $mensaje = "Moneda: " . $cuenta->moneda . " - Saldo: " . $cuenta->saldo;
                $payload = json_encode(array("mensaje" => $mensaje));
            }
        }


        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }



    public function TraerTodos($request, $response, $args)
    {
        $lista = Cuenta::obtenerTodos();
        $payload = json_encode(array("listaCuenta" => $lista));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        if (isset($parametros['numeroDeCuenta']) && isset($parametros['tipoDeCuenta'])) {
            $numeroDeCuenta = $parametros['numeroDeCuenta'];
            $tipoDeCuenta = $parametros['tipoDeCuenta'];

            $mensaje = Cuenta::borrarUno($numeroDeCuenta, $tipoDeCuenta);
            $payload = json_encode(array("mensaje" => $mensaje));
        } else {
            $payload = json_encode(array("mensaje" => "Parámetros incorrectos en la solicitud."));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }


    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $apellido = $parametros['apellido'];
        $tipoDocumento = $parametros['tipoDocumento'];
        $documento = $parametros['documento'];
        $email = $parametros['email'];
        $tipoDeCuenta = $parametros['tipoDeCuenta'];
        $moneda = $parametros['moneda'];
        $numeroDeCuenta = $parametros['numeroDeCuenta'];
        $mensaje = Cuenta::modificarUno($nombre, $apellido, $tipoDocumento, $documento, $email, $tipoDeCuenta, $moneda, $numeroDeCuenta);
         
       
        $payload = json_encode(array("mensaje" => $mensaje));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }



    
}
