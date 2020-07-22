<?php

namespace App\Api;

/**
 * .class [ TIPO ]
 *
 * @copyright (c) 2018, Carlos Junior
 */
class ApiMessages
{
    /**
     * @var string
     */
    private $message = [];

    public function __construct(string $message, array $errors = [])
    {
        $this->message['message'] = $message;
        $this->message['errors'] = $errors;
    }

    /*     * ************************************************ */
    /*     * ************* METODOS PRIVADOS ***************** */
    /*     * ************************************************ */


    /*     * ************************************************ */
    /*     * ************* METODOS PUBLICOS ***************** */
    /*     * ************************************************ */

    public function getMessage()
    {
        return $this->message;
    }
}
