<?php
class Movimiento{

    public $id;
    public $numeroDeCuenta;
    public $monto;
    public $tipoDeMovimiento;
    public $fechaAlta;
    public $fechaBaja;
    public $nombreFoto;


    public function crearUno()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO movimientos (numeroDeCuenta, monto, tipoDeMovimiento, fechaAlta, nombreFoto) VALUES (:numeroDeCuenta, :monto, :tipoDeMovimiento, :fechaAlta, :nombreFoto)");
        
        $consulta->bindValue(':numeroDeCuenta', $this->numeroDeCuenta, PDO::PARAM_INT);
        $consulta->bindValue(':monto', $this->monto, PDO::PARAM_STR);
        $consulta->bindValue(':tipoDeMovimiento', $this->tipoDeMovimiento, PDO::PARAM_STR);
        $consulta->bindValue(':fechaAlta', date('Y-m-d H:i:s'));
        $consulta->bindValue(':nombreFoto', $this->nombreFoto, PDO::PARAM_STR);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }


    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM movimientos WHERE fechaBaja IS NULL");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Movimiento');
    }

    public static function obtenerUno($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM movimientos WHERE id = :id AND fechaBaja IS NULL");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        $cuenta = $consulta->fetchObject('Movimiento');
        return $cuenta;
    }


    public static function borrarUno($id)
    {
        $mensaje = "";
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $depositoExistente = self::obtenerUno($id);

        if (!$depositoExistente) {
            $mensaje = "No se encontró el movimiento.";
        } else {
            $consultaVerificacion = $objAccesoDato->prepararConsulta("SELECT fechaBaja FROM movimientos WHERE id = :id");
            $consultaVerificacion->bindValue(':id', $id, PDO::PARAM_INT);
            $consultaVerificacion->execute();
            $fechaExiste = $consultaVerificacion->fetchColumn();

            if ($fechaExiste != NULL) {
                $mensaje = "El movimiento fue eliminado.";
            } else {
                $consulta = $objAccesoDato->prepararConsulta("UPDATE movimientos SET fechaBaja = :fechaBaja WHERE id = :id");
                $consulta->bindValue(':id', $id, PDO::PARAM_INT);
                $consulta->bindValue(':fechaBaja', date('Y-m-d H:i:s'));
                $consulta->execute();
                $mensaje = "Movimiento eliminado.";
            }
        }

        return $mensaje;
    }

    public static function obtenerDepositosPorUsuario($numeroDeCuenta) {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM movimientos WHERE tipoDeMovimiento = 'Deposito' AND numeroDecuenta = :numeroDecuenta");
        $consulta->bindValue(':numeroDeCuenta', $numeroDeCuenta, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Movimiento');
    }

    public static function GuardarImagen($foto, $cuenta, $movimiento) 
    {
        $retorno = false;
        $directorio = './ImagenesDeDepositos2023/';
        $nombreFoto = $cuenta->tipoDeCuenta . $cuenta->numeroDeCuenta . $movimiento->id . ".jpg";
        $nombreImagen = $directorio . $nombreFoto;
        
        if (!file_exists($directorio)) {
            mkdir($directorio, 0777, true);
        }
    
        if (move_uploaded_file($foto['tmp_name'], $nombreImagen)) {
            $retorno = true;
    
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("UPDATE movimientos SET nombreFoto = :nombreFoto WHERE numeroDeCuenta = :numeroDeCuenta");
            $consulta->bindValue(':nombreFoto', $nombreFoto, PDO::PARAM_STR);
            $consulta->bindValue(':numeroDeCuenta', $movimiento->numeroDeCuenta, PDO::PARAM_INT);
            $consulta->execute();

            $movimiento->nombreFoto = $nombreFoto;
        } 
    
        return $retorno;
    }

    public static function actualizarMovimiento($id, $saldo) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE movimientos SET monto = monto + :saldo WHERE id = :id");
        $consulta->bindValue(':saldo', $saldo, PDO::PARAM_INT);
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    
}
?>