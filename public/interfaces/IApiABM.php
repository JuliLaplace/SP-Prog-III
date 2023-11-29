<?php
interface IApiABM
{
	public function crearUno();
	public static function obtenerTodos();
	public static function obtenerUno($parametro);
	public static function borrarUno($parametro);
}
