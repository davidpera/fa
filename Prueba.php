<?php

class Prueba
{
    public const PUBLICA_DEFAULT = 'publica';

    public $publica;

    public static $estatica = 30;

    protected $protegida = 'protegida';

    private $_privada = 25;

    public function __construct($valor = self::PUBLICA_DEFAULT)
    {
        $this->publica = $valor;
    }

    public function getPrivada()
    {
        return $this->_privada;
    }

    public function setPrivada($privada):void
    {
        $this->_privada = $privada;
    }

    public function mostrar()
    {
        echo self::PUBLICA_DEFAULT;
    }

    public function incrementaEstatica()
    {
        self::$estatica++;
    }

    public static function metodoEstatico()
    {
        echo 'El valor de la propiedad estatica es '.self::$estatica;
    }

}
