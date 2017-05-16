<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;
use AppBundle\Form\CommentType;
use AppBundle\Form\PostType;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController extends FOSRestController
{
    // ToDo: add post voter to check if the user has the right to view, create, edit or delete posts
    // ToDo: add pagination

    /**
     *  @ApiDoc(
     *     section="Posts",
     *     description="Retrieve all posts"
     * )
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
     * @ApiDoc(
     *     section="Posts",
     *     description="Retrieve a given post"
     * )
     * @View()
     */
    public function getPostAction(Request $request, Post $post)
    {
        return $post;
    }

    /**
     * @ApiDoc(
     *     section="Posts",
     *     description="Create a post"
     * )
     *
     * @param Request $request
     * @return array|Response
     */
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
     * @ApiDoc(
     *     section="Posts",
     *     description="Edit a post"
     * )
     *
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
     * @ApiDoc(
     *     section="Posts",
     *     description="Delete a post"
     * )
     *
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
     * @ApiDoc(
     *     section="Comments",
     *     description="Retrieve all comments for a post"
     * )
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
     * @ApiDoc(
     *     section="Comments",
     *     description="Retrieve a given comment for a given post"
     * )
     * @View()
     * @param Post $post
     * @return Response
     */
    public function getPostCommentAction(Post $post, Comment $comment)
    {
        return $comment;
    }

    /**
     * @ApiDoc(
     *     section="Comments",
     *     description="Create a comment for a given post"
     * )
     *
     * @param Request $request
     * @param Post $post
     * @return array|Response
     */
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
     * @ApiDoc(
     *     section="Comments",
     *     description="Edit a comment for a post"
     * )
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
     * @ApiDoc(
     *     section="Comments",
     *     description="Delete a comment for a given post"
     * )
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