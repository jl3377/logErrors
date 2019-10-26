<?php
/**
 * Classe to log custom errors
 *
 ** How To Use
 * $debugguer = new debugguer(true); // true (activada) false (desactivado)
 *
 * Log a default error
 * $debugguer->log("Se ha producido un error1 ...");
 * $debugguer->log("Se ha producido un error2 ...", "error");
 *
 * Log error to file info.log, critical.log, fatal.log
 * $debugguer->log("Pedido inferior a 5€ ...", "info");
 * $debugguer->log("Se ha producido un error crítico ...", "critical");
 * $debugguer->log("No se pudo conectar con la Base de Datos ...", "fatal"); *
 *
 * Send Log to email
 * $debugguer->log_to_mail("La conexión hacia la DB ha fallado", "jose@artegrafico.net"); *
 *
 * @author José Luis Rojo <jose@artegrafico.net>
 * @version 0.0.1
 * @access public
 * @see https://www.artegrafico.net
 * @link https://github.com/jl3377/logErrors
 *
 */
class debugguer extends Exception {

    public $_options = [];
    public $_logs_dir = "logs/";

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
            'error_reporting', E_ALL,
            'display_errors' => 0, // muestra errores en pantalla (no recomendable)
            'html_errors' => 0, // muestra errores con ayudas
            'log_errors' => 1, // guarda los errores en un fichero
            'log_errors_max_len' => 0, // mostrar la descripción del error al completo
            'ignore_repeated_errors' => 1, // ignorar errores que se repitan
            'error_prepend_string' => '<div style="color:#ff0000">',
            'error_append_string' => '</div>',
            'error_log' =>  'logs/error.log', // el fichero de errores
        ];

        //echo print_r($this->_options);

        return $this->_options;

    }

    /**
     * send mail
     */
    public function log_to_mail( $error, $email ) {

        $headers = 'Content-Type: text/html; charset=utf-8' . "\r\n";
        $headers .= 'From: jose@aregrafico.net' . "\r\n";
        $headers .= 'Reply-To: jose@artegrafico.net' . "\r\n";
        $headers .= 'X-Mailer: PHP/' . phpversion();
        error_log( $error, 1, $email, $headers);

    }

    /**
     * Init options
     * @link https://www.php.net/manual/es/function.ini-set.php
     */
    public function init() {

        foreach ( $this->options() as $key => $value ) {

            ini_set( $key, $value );

        }

    }

    /**
     * return actual date
     * @link https://www.php.net/manual/es/class.datetime.php
     */
    public function date() {

        $datetimeFormat = 'd-m-Y H:i:s';
        $date = new \DateTime('now', new \DateTimeZone('Europe/Madrid'));
        $this->_date = $date->format($datetimeFormat);
        return '['.$this->_date.']';

    }

    /**
     * log custom error
     * @param string $error
     * @param string $type
     * @link https://www.php.net/manual/es/function.error-log.php
     */
    public function log( $error, $type = null ) {

        // custom level 3
        $level = 3;

        // default log type
        ( empty($type) ) ?  $type =  'error' : '';

        // file
        $this->_file = $type.".log";

        // log error
        error_log(
            $this->date().' '.$error."\n",
            $level,
            $this->_logs_dir.$this->_file
        );

    }

    /**
     * control custom expceptions
     */
    public function error() {

        $error = 'Error línea '.$this->getLine();
        $error .= ' en '.$this->getFile();
        $error .= ' <strong>'.$this->getMessage().'</strong>';
        return $error;
    }

}

