<?php
require_once './models/Cuenta.php';


class Consulta{
    
    public static function TotalRetiradoPorTipoYMonedaEnFecha($tipoDeCuenta, $fecha)
    {

        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("
            SELECT SUM(movimientos.monto) as total_retirado
            FROM movimientos
            JOIN cuentas ON movimientos.numeroDeCuenta = cuentas.numeroDeCuenta
            WHERE movimientos.tipoDeMovimiento = 'retiro' 
            AND cuentas.tipoDeCuenta = :tipoDeCuenta
            AND DATE(movimientos.fechaAlta) = :fecha
            AND movimientos.fechaBaja IS NULL
        ");
        $consulta->bindValue(':tipoDeCuenta', $tipoDeCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $fecha, PDO::PARAM_STR);
        $consulta->execute();

        $resultado = $consulta->fetch(PDO::FETCH_ASSOC);

        return $resultado['total_retirado'];
    }

    public static function ListadoDepositosPorUsuario($numeroDeCuenta) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("
            SELECT movimientos.*, cuentas.nombre
            FROM movimientos
            JOIN cuentas ON movimientos.numeroDeCuenta = cuentas.numeroDeCuenta
            WHERE movimientos.tipoDeMovimiento = 'deposito' 
            AND movimientos.numeroDeCuenta = :numeroDeCuenta
            AND movimientos.fechaBaja IS NULL
        ");
        $consulta->bindValue(':numeroDeCuenta', $numeroDeCuenta, PDO::PARAM_INT);
        $consulta->execute();

        $resultados = $consulta->fetchAll(PDO::FETCH_OBJ);

        return $resultados;
    }


    public static function ListadoDepositosPorFechaOrdenados($fInicial, $fFinal) {
        $fechaInicial = new DateTime($fInicial);
        $fechaFinal = new DateTime($fFinal);
    
        $fInicialFormateada = $fechaInicial->format('Y-m-d H:i:s');
        $fFinalFormateada = $fechaFinal->format('Y-m-d H:i:s');
    
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("
            SELECT movimientos.*, cuentas.nombre
            FROM movimientos
            JOIN cuentas ON movimientos.numeroDeCuenta = cuentas.numeroDeCuenta
            WHERE movimientos.tipoDeMovimiento = 'deposito' 
            AND DATE(movimientos.fechaAlta) BETWEEN :fechaInicio AND :fechaFin 
            AND movimientos.fechaBaja IS NULL
            ORDER BY cuentas.nombre
        ");
        $consulta->bindValue(':fechaInicio', $fInicialFormateada, PDO::PARAM_STR);
        $consulta->bindValue(':fechaFin', $fFinalFormateada, PDO::PARAM_STR);
        $consulta->execute();
    
        $resultados = $consulta->fetchAll(PDO::FETCH_OBJ);
    
        return $resultados;
    }

    public static function ListadoDepositosPorTipo($tipoDeCuenta) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("
            SELECT * FROM movimientos
            JOIN cuentas ON movimientos.numeroDeCuenta = cuentas.numeroDeCuenta
            WHERE movimientos.tipoDeMovimiento = 'deposito' 
            AND cuentas.tipoDeCuenta = :tipoDeCuenta
            AND movimientos.fechaBaja IS NULL
        ");
        $consulta->bindValue(':tipoDeCuenta', $tipoDeCuenta, PDO::PARAM_STR);
        $consulta->execute();
    
        $resultados = $consulta->fetchAll(PDO::FETCH_OBJ);
    
        return $resultados;
    }

    public static function ListadoDepositosPorMoneda($tipoDeMoneda) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("
            SELECT *
            FROM movimientos
            JOIN cuentas ON movimientos.numeroDeCuenta = cuentas.numeroDeCuenta
            WHERE movimientos.tipoDeMovimiento = 'deposito' 
            AND cuentas.moneda = :tipoDeMoneda
            AND movimientos.fechaBaja IS NULL
        ");
        $consulta->bindValue(':tipoDeMoneda', $tipoDeMoneda, PDO::PARAM_STR);
        $consulta->execute();
    
        $resultados = $consulta->fetchAll(PDO::FETCH_OBJ);
    
        return $resultados;
    }

    public static function ListadoMovimientosPorCuenta($numeroDeCuenta) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM movimientos WHERE numeroDeCuenta = :numeroDeCuenta");
        $consulta->bindValue(':numeroDeCuenta', $numeroDeCuenta, PDO::PARAM_STR);
        $consulta->execute();

        $resultados = $consulta->fetchAll(PDO::FETCH_OBJ);

        return $resultados;
    }



}
?>