<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(PostRepository $postRepo): Response
    {
        
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'posts' => $postRepo->findAll()
        ]);
    }

    /**
     * @Route("/post/{id}", name="show_post")
     */
    public function show(Post $post, Request $request, EntityManagerInterface $em) {

        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $comment->setCreatedAt(new \DateTime());
        $comment->setPost($post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($comment);
            $em->flush();
        }

        return $this->render('home/post.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }
}
