<?php

namespace Database\Controller;

use Zend\View\Model\ViewModel;
use Zend\Mvc\Controller\AbstractActionController;


class DatabaseController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }
}