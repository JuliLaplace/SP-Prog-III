<?php



class Cuenta{
    public $nombre;
    public $apellido;
    public $tipoDocumento;
    public $documento;
    public $email;
    public $tipoDeCuenta;
    public $moneda;
    public $saldo;
    public $numeroDeCuenta;
    public $nombreFoto;
    public $fechaAlta;
    public $fechaBaja;
    public $fechaModificacion;

    public function __construct() {

    }

    public function crearUno()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO cuentas (nombre, apellido, tipoDocumento, documento, email, tipoDeCuenta, moneda, saldo, nombreFoto, fechaAlta) VALUES (:nombre, :apellido, :tipoDocumento, :documento, :email, :tipoDeCuenta, :moneda, :saldo, :nombreFoto, :fechaAlta)");
        
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':tipoDocumento', $this->tipoDocumento, PDO::PARAM_STR);
        $consulta->bindValue(':documento', $this->documento, PDO::PARAM_STR);
        $consulta->bindValue(':email', $this->email, PDO::PARAM_STR);
        $consulta->bindValue(':tipoDeCuenta', $this->tipoDeCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':moneda', $this->moneda, PDO::PARAM_STR);
        $consulta->bindValue(':saldo', $this->saldo, PDO::PARAM_INT);
        $consulta->bindValue(':nombreFoto', $this->nombreFoto, PDO::PARAM_STR);
        $consulta->bindValue(':fechaAlta', date('Y-m-d H:i:s'));

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM cuentas WHERE fechaBaja IS NULL");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Cuenta');
    }

    public static function obtenerUno($numeroDeCuenta, $tipoDeCuenta)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM cuentas WHERE numeroDeCuenta = :numeroDeCuenta AND tipoDeCuenta = :tipoDeCuenta AND fechaBaja IS NULL");
        $consulta->bindValue(':numeroDeCuenta', $numeroDeCuenta, PDO::PARAM_INT);
        $consulta->bindValue(':tipoDeCuenta', $tipoDeCuenta, PDO::PARAM_STR);
        $consulta->execute();

        $cuenta = $consulta->fetchObject('Cuenta');
        return $cuenta;
    }


    public static function borrarUno($numeroDeCuenta, $tipoDeCuenta)
    {
        $mensaje = "";
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $cuentaExistente = self::obtenerUno($numeroDeCuenta, $tipoDeCuenta);

        if (!$cuentaExistente) {
            $mensaje = "No se encontró la cuenta.";
        } else {

            if (!file_exists('./ImagenesBackupCuentas/2023/')) {
                mkdir('./ImagenesBackupCuentas/2023/', 0777, true);
            }
            

            $consultaVerificacion = $objAccesoDato->prepararConsulta("SELECT fechaBaja FROM cuentas WHERE numeroDeCuenta = :numeroDeCuenta AND tipoDeCuenta =:tipoDeCuenta");
            $consultaVerificacion->bindValue(':numeroDeCuenta', $numeroDeCuenta, PDO::PARAM_INT);
            $consultaVerificacion->bindValue(':tipoDeCuenta', $tipoDeCuenta, PDO::PARAM_STR);
            $consultaVerificacion->execute();
            $fechaExiste = $consultaVerificacion->fetchColumn();

            if ($fechaExiste != NULL) {
                $mensaje = "La cuenta ya fue dada de baja.";
            } else {
                $consulta = $objAccesoDato->prepararConsulta("UPDATE cuentas SET fechaBaja = :fechaBaja WHERE numeroDeCuenta = :numeroDeCuenta");
                $consulta->bindValue(':numeroDeCuenta', $numeroDeCuenta, PDO::PARAM_STR);
                $consultaVerificacion->bindValue(':tipoDeCuenta', $tipoDeCuenta, PDO::PARAM_STR);
                $consulta->bindValue(':fechaBaja', date('Y-m-d H:i:s'));
                $consulta->execute();
                

                $nombreFoto = $cuentaExistente->nombreFoto;

                $rutaActualFoto = './ImagenesDeCuentas/' . $nombreFoto; 
                $rutaNuevaFoto = './ImagenesBackupCuentas/2023/' . $nombreFoto;

                if (rename($rutaActualFoto, $rutaNuevaFoto)) { // Si pudo cambiar de lugar la foto
                                            
                    $mensaje = "Cuenta dada de baja.";
                } else {

                    $mensaje = "Error en mover la imagen - Cuenta dada de baja.";
                }
            }
        }

        return $mensaje;
    }

    public function GuardarImagen($foto) 
    {
        $retorno = false;
        $directorio = './ImagenesDeCuentas/';
        $nombreFoto = $this->documento . $this->tipoDeCuenta. ".jpg";
        $nombreImagen = $directorio . $nombreFoto;
        
        if (!file_exists($directorio)) {
            mkdir($directorio, 0777, true);
        }
    
        if (move_uploaded_file($foto['tmp_name'], $nombreImagen)) {
            $retorno = true;
    
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("UPDATE cuentas SET nombreFoto = :nombreFoto WHERE numeroDeCuenta = :numeroDeCuenta");
            $consulta->bindValue(':nombreFoto', $nombreFoto, PDO::PARAM_STR);
            $consulta->bindValue(':numeroDeCuenta', $this->numeroDeCuenta, PDO::PARAM_INT);
            $consulta->execute();

            $this->nombreFoto = $nombreFoto;
        } 
    
        return $retorno;
    }
    
    public static function obtenerCuentaExistente($nombre, $apellido, $tipoDocumento, $documento, $email, $tipoDeCuenta, $moneda)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM cuentas WHERE
            nombre = :nombre AND
            apellido = :apellido AND
            tipoDocumento = :tipoDocumento AND
            documento = :documento AND
            email = :email AND
            tipoDeCuenta = :tipoDeCuenta AND
            moneda = :moneda AND
            fechaBaja IS NULL");

        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $apellido, PDO::PARAM_STR);
        $consulta->bindValue(':tipoDocumento', $tipoDocumento, PDO::PARAM_STR);
        $consulta->bindValue(':documento', $documento, PDO::PARAM_STR);
        $consulta->bindValue(':email', $email, PDO::PARAM_STR);
        $consulta->bindValue(':tipoDeCuenta', $tipoDeCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':moneda', $moneda, PDO::PARAM_STR);

        $consulta->execute();

        return $consulta->fetchObject('Cuenta');
    }

    public static function actualizarSaldo($numeroDeCuenta, $monto) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE cuentas SET saldo = saldo + :monto WHERE numeroDeCuenta = :numeroDeCuenta AND fechaBaja IS NULL");
        $consulta->bindValue(':monto', $monto, PDO::PARAM_INT);
        $consulta->bindValue(':numeroDeCuenta', $numeroDeCuenta, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->rowCount();
    }


    


    static function obtenerCuentasPorTipo($tipoDeCuenta, $fecha) {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM cuentas WHERE tipoDeCuenta = :tipoDeCuenta AND fechaAlta = :fechaAlta ");
        $consulta->bindValue(':tipoDeCuenta', $tipoDeCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':fechaAlta', $fecha);
        $consulta->execute();
    
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Cuenta');
    }

  



    public static function modificarUno($nombre, $apellido, $tipoDocumento, $documento, $email, $tipoDeCuenta, $moneda, $numeroDeCuenta)
    {
        $mensaje = "";
        $objAccesoDatos = AccesoDatos::obtenerInstancia();

        $cuenta = self::obtenerUno($numeroDeCuenta, $tipoDeCuenta);

        if (!$cuenta) {
            $mensaje = "No existe la cuenta";
        } else {
            $consulta = $objAccesoDatos->prepararConsulta("UPDATE cuentas SET nombre = :nombre, apellido = :apellido, tipoDocumento = :tipoDocumento, documento = :documento, email = :email, tipoDeCuenta = :tipoDeCuenta, moneda = :moneda, fechaModificacion = :fechaModificacion WHERE numeroDeCuenta = :numeroDeCuenta");

            $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
            $consulta->bindValue(':apellido', $apellido, PDO::PARAM_STR);
            $consulta->bindValue(':tipoDocumento', $tipoDocumento, PDO::PARAM_STR);
            $consulta->bindValue(':documento', $documento, PDO::PARAM_STR);
            $consulta->bindValue(':email', $email, PDO::PARAM_STR);
            $consulta->bindValue(':tipoDeCuenta', $tipoDeCuenta, PDO::PARAM_STR);
            $consulta->bindValue(':moneda', $moneda, PDO::PARAM_STR);
            $consulta->bindValue(':fechaModificacion', date('Y-m-d H:i:s'));
            $consulta->bindValue(':numeroDeCuenta', $numeroDeCuenta, PDO::PARAM_STR);

            $consulta->execute();
            $mensaje = "Cuenta modificada.";
        }

        return $mensaje;
    }

    public static function ObtenerCuentaPorDocumento($documento) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("
            SELECT * FROM cuentas
            WHERE documento = :documento
        ");
        $consulta->bindValue(':documento', $documento, PDO::PARAM_STR);
        $consulta->execute();
    
        $cuenta = $consulta->fetch(PDO::FETCH_OBJ);
    
        return $cuenta;
    }

    

}
?>