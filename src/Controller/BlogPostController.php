<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Form\BlogPostType;
use App\Form\BlogPostTypeEdit;
use App\Repository\BlogPostRepository;
use App\Service\BlogPostWorkflowService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogPostController extends AbstractController
{
    /**
     * @Route("/", name="blog_post_index", methods={"GET"})
     */
    public function index(BlogPostRepository $blogPostRepository): Response
    {
        return $this->render('blog_post/index.html.twig', [
            'blog_posts' => $blogPostRepository->findAll(),
        ]);
    }

    /**
     * @Route("/blog/new", name="blog_post_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $blogPost = new BlogPost();
        $form = $this->createForm(BlogPostType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($blogPost);
            $entityManager->flush();

            return $this->redirectToRoute('blog_post_index');
        }

        return $this->render('blog_post/new.html.twig', [
            'blog_post' => $blogPost,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/graph-workflow", name="graph-workflow", methods={"GET"})
     */
    public function graph()
    {
        return $this->render('graph/index.html.twig');
    }

    /**
     * @Route("/blog/{id}", name="blog_post_show", methods={"GET"})
     */
    public function show(BlogPost $blogPost): Response
    {
        return $this->render('blog_post/show.html.twig', [
            'blog_post' => $blogPost,
        ]);
    }

    /**
     * @Route("/blog/{id}/edit", name="blog_post_edit", methods={"GET","POST"})
     */
    public function edit(
        BlogPostWorkflowService $blogPostWorkflowService,
        Request $request,
        BlogPost $blogPost
    ): Response {

        $form = $this->createForm(BlogPostTypeEdit::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($form->get('reviewed')->isClicked()) {
                $state = 'to_review';
            }
            if($form->get('publish')->isClicked()) {
               $state = 'publish';
            }
            if($form->get('reject')->isClicked()) {
                $state = 'reject';
            }
            if($form->get('draft')->isClicked()) {
                $state = 'draft';
            }
            if($state) {
                $blogPostWorkflowService->setToNewState($blogPost, $state);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('blog_post_index');
        }

        return $this->render('blog_post/edit.html.twig', [
            'blog_post' => $blogPost,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/blog/{id}", name="blog_post_delete", methods={"DELETE"})
     */
    public function delete(Request $request, BlogPost $blogPost): Response
    {
        if ($this->isCsrfTokenValid('delete' . $blogPost->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($blogPost);
            $entityManager->flush();
        }

        return $this->redirectToRoute('blog_post_index');
    }
}
