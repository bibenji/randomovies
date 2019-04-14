<?php

namespace Randomovies\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {	
        $builder
            ->add('movie_id', HiddenType::class, [
				'data' => $options['movie_id'],
                'empty_data' => $options['movie_id'],
                'mapped' => false,
            ])
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire',
            ])
            ->add('note', ChoiceType::class, [
                'choices' => [
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5
                ],
                'label' => 'Note',
            ])
            ->add('referer', HiddenType::class, [
            	'data' => $options['referer'],
            	'empty_data' => $options['referer'],
            	'mapped' => false,
            	'required' => false,
            ])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Randomovies\Entity\Comment',
            'movie_id' => null,
        	'referer' => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'randomovies_comment';
    }
}
