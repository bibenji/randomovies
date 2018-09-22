<?php

namespace Randomovies\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class MovieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $posterRequired = (null === $options['data']->getPoster()) ?? false;

        $builder
			->add('title')
			->add('director')
			->add('actors')
			->add('roles', CollectionType::class, [
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
			    'entry_type' => RoleType::class,
                'prototype' => true,
            ])
			->add('year')
			->add('duration')
			->add('synopsis')
			->add('rating')
			->add('review')
			->add('genre')

            ->add('tags', EntityType::class, [
                'class' => 'Randomovies:Tag',
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
            ])
//            ->add('tags', CollectionType::class, [
//                'allow_add' => true,
//                'allow_delete' => true,
//                'by_reference' => false,
//                'entry_type' => TagType::class,
//                'prototype' => true,
//            ])
		;

        if ($options['edit']) {
            $builder
                ->add('newPoster', FileType::class, [
                    'label' => 'New Poster',
                    'required' => false,
                    'mapped' => false,
                ])
            ;
        } else {
            $builder
                ->add('poster', FileType::class, [
                    'label' => 'Poster',
                    'required' => true,
                ])
            ;
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'edit'
        ));

        $resolver->setDefaults(array(
            'data_class' => 'Randomovies\Entity\Movie',
            'edit' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'randomovies_movie';
    }
}
