<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;

class UserController extends FOSRestController
{

    /**
     * @View()
     *  @ApiDoc(
     *     section="Users",
     *     description="Retrieve all users"
     * )
     */
    public function getUsersAction(Request $request)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');
        $userRepository = $em->getRepository('AppBundle:User');

        $users = $userRepository->findAll();

        return $users;
    }

    /**
     * @ApiDoc(
     *     section="Users",
     *     description="Retrieve a given user"
     * )
     * @View()
     */
    public function getUserAction(Request $request, User $user)
    {
        return $user;
    }
}