<?php


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as Response;


class AuthMiddleware
{
    public static function verificarToken(Request $request, RequestHandler $handler): Response
    {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        try {
            AutentificadorJWT::VerificarToken($token);
            $response = $handler->handle($request);
        } catch (Exception $e) {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'ERROR: Hubo un error con el TOKEN'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ValidarSupervisor($request, $handler): Response
    {   
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        try {
            AutentificadorJWT::VerificarToken($token);
            $data = AutentificadorJWT::ObtenerData($token);

            if ($data->rol == 'supervisor') {
                $request = $request->withAttribute('rol', $data->rol); //le paso un atributo para obetenr el sector
                $response = $handler->handle($request);
               
            } else {
                $response = new Response();
                $payload = json_encode(array("mensaje" => "No sos supervisor"));
                $response->getBody()->write($payload);
            }



        } catch (Exception $e) {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'ERROR: Hubo un error con el TOKEN'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ValidarCajero($request, $handler): Response
    {   
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        try {
            AutentificadorJWT::VerificarToken($token);
            $data = AutentificadorJWT::ObtenerData($token);

            if ($data->rol == 'cajero') {
                $request = $request->withAttribute('rol', $data->rol); //le paso un atributo para obetenr el sector
                $response = $handler->handle($request);
               
            } else {
                $response = new Response();
                $payload = json_encode(array("mensaje" => "No sos Cajero"));
                $response->getBody()->write($payload);
            }



        } catch (Exception $e) {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'ERROR: Hubo un error con el TOKEN'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function ValidarOperador($request, $handler): Response
    {   
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        try {
            AutentificadorJWT::VerificarToken($token);
            $data = AutentificadorJWT::ObtenerData($token);

            if ($data->rol == 'operador') {
                $request = $request->withAttribute('rol', $data->rol); //le paso un atributo para obetenr el sector
                $response = $handler->handle($request);
               
            } else {
                $response = new Response();
                $payload = json_encode(array("mensaje" => "No sos operador"));
                $response->getBody()->write($payload);
            }



        } catch (Exception $e) {
            $response = new Response();
            $payload = json_encode(array('mensaje' => 'ERROR: Hubo un error con el TOKEN'));
            $response->getBody()->write($payload);
        }
        return $response->withHeader('Content-Type', 'application/json');
    }


}

?>