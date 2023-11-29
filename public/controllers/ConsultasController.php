<?php

require_once './models/Consulta.php';
require_once './models/Cuenta.php';

class ConsultasController
{
    public function TotalRetiradoPorTipoYMonedaEnFecha($request, $response, $args)
    {
       

        $tipoDeCuenta = $args['tipoDeCuenta'];
        if (isset($args['fecha'])) {
            $fecha = $args['fecha'];
        } else {
            $fechaAnterior = new DateTime();
            $fechaAnterior->modify('-1 day');
            $fecha = $fechaAnterior->format('Y-m-d');
        }
    

        $totalRetirado = Consulta::TotalRetiradoPorTipoYMonedaEnFecha($tipoDeCuenta, $fecha);

        if($totalRetirado==null){
            $payload = json_encode(array("mensaje" => "No se encontraron retiros"));
        }else{
            $payload = json_encode(array("totalRetirado" => $totalRetirado));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ListarDepositosPorUsuario($request, $response, $args)
    {

        $documento = $args['documento'];

        $cuenta = Cuenta::ObtenerCuentaPorDocumento($documento);
        if($cuenta){
            $lista = Consulta::ListadoDepositosPorUsuario($cuenta->numeroDeCuenta);
            if(empty($lista)){
                $payload = json_encode(array("mensaje" => "No se encontraron depositos de la cuenta seleccionada"));
            }else{
                $payload = json_encode(array("listaDepositos" => $lista));
            }
        }else{
            $payload = json_encode(array("mensaje" => "No se encontraron cuentas ligadas al mail proporcionado"));
        }
        

        


        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function ListarDepositosPorFechas($request, $response, $args)
    {
        $fechaInicio = $args['fechaInicio'];
        $fechaFin = $args['fechaFin'];
        

        $lista = Consulta::ListadoDepositosPorFechaOrdenados($fechaInicio, $fechaFin);
        if(empty($lista)){
            $payload = json_encode(array("mensaje" => "No se encontraron depositos entre las fechas $fechaInicio y $fechaFin"));
        }else{
            $payload = json_encode(array("listaDepositos" => $lista));
        }


        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
        
    }

    public function ListarDepositosPorTipoDeCuenta($request, $response, $args)
    {
        $tipoDeCuenta = $args['tipoDeCuenta'];
        

        $lista = Consulta::ListadoDepositosPorTipo($tipoDeCuenta);
        if(empty($lista)){
            $payload = json_encode(array("mensaje" => "No se encontraron depositos con el tipo de cuenta $tipoDeCuenta"));
        }else{
            $payload = json_encode(array("listaDepositos" => $lista));
        }


        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
        
    }

    public function ListarDepositosPorTipoDeMoneda($request, $response, $args)
    {
        $tipoDeMoneda = $args['tipoDeMoneda'];
        

        $lista = Consulta::ListadoDepositosPorMoneda($tipoDeMoneda);
        if(empty($lista)){
            $payload = json_encode(array("mensaje" => "No se encontraron depositos con el tipo de moneda $tipoDeMoneda"));
        }else{
            $payload = json_encode(array("listaDepositos" => $lista));
        }


        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
        
    }

    public function ListarMovimientosPorUsuario($request, $response, $args)
    {
        $documento = $args['documento'];
        
        $cuenta = Cuenta::ObtenerCuentaPorDocumento($documento);

        if($cuenta){
            $lista = Consulta::ListadoMovimientosPorCuenta($cuenta->numeroDeCuenta);
            if(empty($lista)){
                $payload = json_encode(array("mensaje" => "No se encontraron movimientos asociados al usuario ingresado"));
            }else{
                $payload = json_encode(array("lista Movimientos" => $lista));
            }
        }else{
            $payload = json_encode(array("mensaje" => "No se encontraron cuentas ligadas al mail proporcionado"));
        }


        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
        
    }
    
}

?>