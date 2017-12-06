<?php

namespace Database\Controller;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;
use Database\Model\AddonProducts;


class DatabaseController extends AbstractActionController
{
    private $addonProducts;

    public function __construct()
    {
    //    $this->addonProducts = $addonProducts; 
    }

    public function indexAction()
    {
        $addon = new AddonProducts();
        $rowset = $addon->select(['addon_id' => 1]);
        foreach ($rowset as $row) {
            var_dump($row->addon_productcode);
            die;
        }
        return new ViewModel();

    }
}