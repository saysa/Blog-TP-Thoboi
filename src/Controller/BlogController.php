<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController.
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request): Response
    {
        $limit = (int) $request->get('limit', 10);
        $page = (int) $request->get('page', 1);
        $total = $this->getDoctrine()->getRepository(Post::class)->count([]);
        $posts = $this->getDoctrine()->getRepository(Post::class)->getPaginatedPosts(
            $page,
            $limit
        );
        $pages = ceil($total / $limit);
        $range = range(
            max($page - 3, 1),
            min($page + 3, $pages)
        );


        return $this->render('index.html.twig', [
            'posts' => $posts,
            'pages' => $pages,
            'page' => $page,
            'limit' => $limit,
            'range' => $range,
        ]);
    }

    /**
     * @Route("/article-{id}", name="blog_read")
     */
    public function read(Post $post, Request $request): Response
    {
        $comment = new Comment();
        $comment->setPost($post);
        $form = $this->createForm(CommentType::class, $comment)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($comment);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('blog_read', ['id' => $post->getId()]);
        }

        return $this->render('read.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }
}
