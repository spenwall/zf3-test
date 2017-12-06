<?php

namespace Database\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Adapter\Adapter;


abstract class TableAbstract extends AbstractTableGateway
{

    public $table;
    
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }
}