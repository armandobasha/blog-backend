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

class PostController extends FOSRestController
{
    // ToDo: add post voter to check if the user has the right to view, create, edit or delete posts
    // ToDo: add pagination

    /**
     * @View()
     */
    public function getPostsAction(Request $request)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');
        $postRepository = $em->getRepository('AppBundle:Post');

        $posts = $postRepository->findAll();

        return $posts;
    }

    /**
     * @View()
     */
    public function getPostAction(Request $request, Post $post)
    {
        return $post;
    }

    public function postPostAction(Request $request)
    {
        $data = $request->request->all();
        $entity = new Post();
        $form = $this->createForm(new PostType(), $entity);
        $form->submit($data);

        if ($form->isValid()) {
            $entity = $form->getData();
            $em = $this->get('doctrine.orm.default_entity_manager');
            $em->persist($entity);
            $em->flush();

            return new Response("", 201);
        }

        return array(
            'form' => $form,
        );
    }

    /**
     * @View()
     * @param Request $request
     * @return array|\FOS\RestBundle\View\View
     */
    public function patchPostAction(Request $request, Post $post)
    {
        $data = $request->request->all();
        $entity = $post;
        $form = $this->createForm(new PostType(), $entity);
        $form->submit($data);

        if ($form->isValid()) {
            $entity = $form->getData();
            $em = $this->get('doctrine.orm.default_entity_manager');
            $em->persist($entity);
            $em->flush();

            return $this->redirectView(
                $this->generateUrl(
                    'api_get_post',
                    array('post' => $entity->getId())
                ), 201
            );
        }

        return array(
            'form' => $form,
        );
    }

    /**
     * @param Post $post
     * @return Response
     */
    public function deletePostAction(Post $post)
    {
        //
        $em = $this->get('doctrine.orm.default_entity_manager');
        $em->remove($post);
        $em->flush();

        $view = $this->view("OK", 200);
        return $this->handleView($view);
    }

    /**
     * @View()
     * @param Post $post
     * @return Response
     */
    public function getPostCommentsAction(Post $post)
    {
        $em = $this->get('doctrine.orm.default_entity_manager');
        $commentRepository = $em->getRepository("AppBundle:Comment");
        $comments = $commentRepository->findBy(["post" =>$post]);

        return $comments;
    }

    /**
     * @View()
     * @param Post $post
     * @return Response
     */
    public function getPostCommentAction(Post $post, Comment $comment)
    {
        return $comment;
    }

    public function postPostCommentsAction(Request $request, Post $post)
    {
        $data = $request->request->all();
        $entity = new Comment();
        $entity->setPost($post);
        $form = $this->createForm(new CommentType(), $entity);
        $form->submit($data);

        if ($form->isValid()) {
            $entity = $form->getData();
            $em = $this->get('doctrine.orm.default_entity_manager');
            $em->persist($entity);
            $em->flush();

            return new Response("", 201);
        }

        return array(
            'form' => $form,
        );
    }

    /**
     * @View()
     * @param Request $request
     * @param Post $post
     * @param Comment $comment
     * @return Comment|array|mixed
     */
    public function patchPostCommentsAction(Request $request, Post $post, Comment $comment)
    {
        $data = $request->request->all();
        $entity = $comment;
        $entity->setPost($post);
        $form = $this->createForm(new CommentType(), $entity);
        $form->submit($data);

        if ($form->isValid()) {
            $entity = $form->getData();
            $em = $this->get('doctrine.orm.default_entity_manager');
            $em->persist($entity);
            $em->flush();

            return $entity;
        }

        return array(
            'form' => $form,
        );
    }

    /**
     * @param Post $post
     * @return Response
     */
    public function deletePostsCommentAction(Post $post, Comment $comment)
    {
        //
        $em = $this->get('doctrine.orm.default_entity_manager');
        $em->remove($comment);
        $em->flush();

        $view = $this->view("OK", 200);
        return $this->handleView($view);
    }
}