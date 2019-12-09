<?php

namespace App\Form;

use App\Entity\BlogPost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BlogPostTypeEdit extends BlogPostType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('content', null, [
                'attr' => [
                    'rows' => '15',
                    'cols' => '33'
                ]
            ])
            ->add('update', SubmitType::class, [
                'attr' => [
                    'class' => 'btn-success'
                ]
            ])
            ->add('draft', SubmitType::class, [
                'attr' => [
                    'class' => 'btn-outline-success'
                ]
            ])
            ->add('reviewed', SubmitType::class, [
                'attr' => [
                    'class' => 'btn-info'
                ]
            ])
            ->add('reject', SubmitType::class, [
                'attr' => [
                    'class' => 'btn-danger'
                ]
            ])
            ->add('publish', SubmitType::class, [
                'attr' => [
                    'class' => 'btn-primary'
                ]
            ])
        ;
    }
}
