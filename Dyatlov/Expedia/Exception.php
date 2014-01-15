<?php

/**
 * @author Vitaly Dyatlov <md.xytop@gmail.com>
 */

namespace Dyatlov\Expedia;

class Exception extends \Exception
{
    protected $data;

    public function __construct($data)
    {
        parent::__construct($data['verboseMessage']);

        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }
}
