<?php

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Blog\Model\PostRepositoryInterface;
use Zend\View\Model\ViewModel;

class ListController extends AbstractActionController
{
    private $postRepository;
    
    public function __construct(PostRepositoryInterface $postResposistory)
    {
        $this->postRepository = $postRepository;
    }

    public function indexAction()
    {
        return new ViewModel([
            'posts' => $this->postRepository->findAllPosts(),
        ]);
    }
}