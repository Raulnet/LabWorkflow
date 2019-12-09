<?php


namespace App\Service;


use App\Entity\BlogPost;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Workflow\Registry;

class BlogPostWorkflowService
{
    private $workflows;
    private $session;

    public function __construct(Registry $workflows, SessionInterface $session)
    {
        $this->workflows = $workflows;
        $this->session = $session;
    }

    public function setToNewState(BlogPost $blogPost, string $state): void {
        $workflow = $this->workflows->get($blogPost, 'blog_publishing');

        if ($workflow->can($blogPost, $state)) {
            $workflow->apply($blogPost, $state);

            $this->session->getFlashBag()->add('success', "blog was successfully set to $state");
        } else {
            $this->session->getFlashBag()->add('danger', "blog could not be set to $state");
        }
    }
}