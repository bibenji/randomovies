<?php

namespace AppBundle\Form;

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
        $posterRequired = (null === $options['data']->getPoster()) ?? false;

        $builder
			->add('title')
			->add('director')
			->add('actors')
			->add('roles', CollectionType::class, [
			    'entry_type' => RoleType::class,
                'allow_add' => true,
                'prototype' => true,
                'allow_delete' => true,
                'by_reference' => false,
            ])
			->add('year')
			->add('duration')
			->add('synopsis')
			->add('rating')
			->add('review')
			->add('genre')
			// ->add('poster')
			->add('poster', FileType::class, [
				'label' => 'New Poster',
				'required' => $posterRequired,
				 'mapped' => $posterRequired,
			])
		;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Movie'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_movie';
    }


}
