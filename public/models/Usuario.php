<?php

class Usuario 
{
    public $id;
    public $usuario;
    public $rol;
    public $clave;
    public $fechaCreacion;
    public $fechaBaja;




    public function crearUno()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (usuario, rol, clave, fechaCreacion) VALUES (:usuario, :rol, :clave, :fechaCreacion)");
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':rol', $this->rol, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $this->clave, PDO::PARAM_STR);
        $consulta->bindValue(':fechaCreacion', date('Y-m-d H:i:s'));


        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }


    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT usuarios.id, usuarios.usuario, usuarios.rol, usuarios.clave, usuarios.fechaCreacion, usuarios.fechaBaja, usuarios.fechaModificacion FROM usuarios WHERE usuarios.fechaBaja IS NULL"); //ACA MODIFICAR
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUno($usuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT usuarios.id, usuarios.usuario, usuarios.rol, usuarios.clave, usuarios.fechaCreacion, usuarios.fechaBaja, usuarios.fechaModificacion FROM usuarios WHERE usuario = :usuario AND usuarios.fechaBaja IS NULL");
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->execute();
        $empleado = $consulta->fetchObject('Usuario');
        return $empleado;
    }


    public static function borrarUno($usuario)
    {
        $mensaje = "";
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $empleadoExistente = self::obtenerUno($usuario);

        if (!$empleadoExistente) {
            $mensaje = "No se encontró el usuario.";
        } else {
            $consultaVerificacion = $objAccesoDato->prepararConsulta("SELECT fechaBaja FROM usuarios WHERE usuario = :usuario");
            $consultaVerificacion->bindValue(':usuario', $usuario, PDO::PARAM_STR);
            $consultaVerificacion->execute();
            $fechaExiste = $consultaVerificacion->fetchColumn();

            if ($fechaExiste != NULL) {
                $mensaje = "El usuario ya fue dado de baja.";
            } else {
                $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET fechaBaja = :fechaBaja WHERE usuario = :usuario");
                $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
                $consulta->bindValue(':fechaBaja', date('Y-m-d H:i:s'));
                $consulta->execute();
                $mensaje = "Usuario dado de baja.";
            }
        }


        return $mensaje;
    }



    //verifica que este logueado lo que me mandan por parametros
    public static function UsuarioContrasenaExiste($usuario, $clave)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM usuarios WHERE usuario = :usuario AND clave = :clave");
        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $clave, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

}
