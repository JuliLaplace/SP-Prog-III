<?php

require_once './models/Usuario.php';

class LoginController 
{
    public function LoginUsuario($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombreUsuario = $parametros['usuario']; 
        $clave = $parametros['clave'];


        $usuario = new Usuario();
        $respuesta = $usuario->UsuarioContrasenaExiste($nombreUsuario, $clave);

        if ($respuesta == null) {
            $payload = json_encode(array("Error" => "Usuario-contrasena incorrecta. Reintente"));
        } else {
            $datos = ["usuario" => $respuesta->usuario, "rol" => $respuesta->rol];
            $token = AutentificadorJWT::CrearToken($datos);
            $payload = json_encode(array('jwt' => $token));
        }

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }
}