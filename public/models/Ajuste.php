<?php

    class Ajuste{
        public $id;
        public $numeroDeCuenta;
        public $montoAjustado;
        public $idMovimiento;
        public $motivo;
        public $fechaAlta;
        public $fechaBaja;

        public function __construct() 
        {

        }

        public function crearUno()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO ajustes (numeroDeCuenta, montoAjustado, idMovimiento, motivo, fechaAlta) VALUES (:numeroDeCuenta, :montoAjustado, :idMovimiento, :motivo, :fechaAlta)");
            
            $consulta->bindValue(':numeroDeCuenta', $this->numeroDeCuenta, PDO::PARAM_INT);
            $consulta->bindValue(':montoAjustado', $this->montoAjustado, PDO::PARAM_STR);
            $consulta->bindValue(':idMovimiento', $this->idMovimiento, PDO::PARAM_INT);
            $consulta->bindValue(':motivo', $this->motivo, PDO::PARAM_STR);
            $consulta->bindValue(':fechaAlta', date('Y-m-d H:i:s'));

            $consulta->execute();

            return $objAccesoDatos->obtenerUltimoId();
        }


        public static function obtenerTodos()
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM ajustes WHERE fechaBaja IS NULL");
            $consulta->execute();

            return $consulta->fetchAll(PDO::FETCH_CLASS, 'Ajuste');
        }

        public static function obtenerUno($id)
        {
            $objAccesoDatos = AccesoDatos::obtenerInstancia();
            $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM ajustes WHERE id = :id AND fechaBaja IS NULL");
            $consulta->bindValue(':id', $id, PDO::PARAM_INT);
            $consulta->execute();

            $cuenta = $consulta->fetchObject('Ajuste');
            return $cuenta;
        }


        public static function borrarUno($id)
        {
            $mensaje = "";
            $objAccesoDato = AccesoDatos::obtenerInstancia();
            $depositoExistente = self::obtenerUno($id);

            if (!$depositoExistente) {
                $mensaje = "No se encontró el ajuste.";
            } else {
                $consultaVerificacion = $objAccesoDato->prepararConsulta("SELECT fechaBaja FROM ajustes WHERE id = :id");
                $consultaVerificacion->bindValue(':id', $id, PDO::PARAM_INT);
                $consultaVerificacion->execute();
                $fechaExiste = $consultaVerificacion->fetchColumn();

                if ($fechaExiste != NULL) {
                    $mensaje = "El ajuste fue eliminado.";
                } else {
                    $consulta = $objAccesoDato->prepararConsulta("UPDATE ajustes SET fechaBaja = :fechaBaja WHERE id = :id");
                    $consulta->bindValue(':id', $id, PDO::PARAM_INT);
                    $consulta->bindValue(':fechaBaja', date('Y-m-d H:i:s'));
                    $consulta->execute();
                    $mensaje = "Ajuste eliminado.";
                }
            }

            return $mensaje;
        }

        

    }
?>