<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;
use AppBundle\Form\CommentType;
use AppBundle\Form\PostType;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends FOSRestController
{

    /**
     * @View()
     */
    public function getUsersAction(Request $request)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');
        $userRepository = $em->getRepository('AppBundle:User');

        $users = $userRepository->findAll();

        return $users;
    }
}