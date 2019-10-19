<?php

/**
 * Clase para controlar errores
 *
 * Uso
 * $debugguer = new debugguer(true); // true (activada) false (desactivado)
 * $debugguer->log("error"); // escribir un error personalizado
 *
 * @author José Luis Rojo <jose@artegrafico.net>
 * @version 0.0.1
 * @access public
 * @see https://www.artegrafico.net
 *
 */
class debugguer {

    public $_options = [];

    public function __construct( $status ) {

        // activate debug
        ( $status == true) ? $this->init() : '';

    }

    /**
     * options
     * @link https://www.php.net/manual/es/function.ini-set.php
     * @link https://www.php.net/manual/es/errorfunc.configuration.php
     */
    public function options() {

        // init_Set options
        $this->_options = [
            //'error_reporting', -1,
            'display_errors' => 0, // muestra errores en pantalla
            'html_errors' => 1, // muestra errores con ayudas
            'log_errors' => 1, // guarda los errores en un fichero
            'log_errors_max_len' => 0, // mostrar la descripción del error al completo
            'ignore_repeated_errors' => 1, // ignorar errores que se repitan
            'error_prepend_string' => '<div style="color:#ff0000">',
            'error_append_string' => '</div>',
            'error_log' =>  'logs/error.log', // el fichero de errores
        ];

        return $this->_options;

    }

    /**
     * Init options
     */
    public function init() {

        foreach ( $this->options() as $key => $value ) {

            ini_set( $key, $value );

        }

    }

    /**
     * log custom error
     * @param string $error
     */
    public function log( $error ) {

        error_log( $error );

    }

}


// how to use
if (class_exists ('debugguer')) {

    $debugguer = new debugguer(true);
    // control de errores en ejecución

    $debugguer->log("La conexión al la DB produjo un error ...");
    $sql = " select * from productos where precio < 1 ";
    $debugguer->log("consulta SQL ".$sql);

}


