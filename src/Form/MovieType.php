<?php

namespace Randomovies\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MovieType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $posterRequired = (null === $options['data']->getPoster()) ?? false;

        $builder
        	->add('hooverLink', TextType::class, [
        			'label' => 'Récupérer les informations à partir de Wikipedia, lien :',
        			'mapped' => false,
        			'required' => false,
        	])
        	->add('aspire', SubmitType::class, [
					'attr' => [
						'class' => 'btn btn-secondary',
					],
        			'label' => 'Aspirer',
        	])
			->add('title', TextType::class, ['required' => false])
			->add('director', TextType::class, ['required' => false])
			->add('actors', TextType::class, [
			    'required' => false,
            ])
			->add('roles', CollectionType::class, [
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
			    'entry_type' => RoleType::class,
                'prototype' => true,
				'required' => false,
            ])
			->add('year', IntegerType::class, ['required' => false])
			->add('duration', IntegerType::class, ['required' => false])
			->add('synopsis', TextareaType::class, ['required' => false])
			->add('rating', IntegerType::class, ['required' => false])
			->add('review', TextareaType::class, ['required' => false])
			->add('genre', TextType::class, ['required' => false])

            ->add('tags', EntityType::class, [
                'class' => 'Randomovies:Tag',
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true,
            	'required' => false,
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
                	'mapped' => false,
                	'required' => false,                    
                ])
            ;
        } else {
            $builder
                ->add('poster', FileType::class, [
                    'label' => 'Poster',
                    'required' => false,
                ])
            ;
        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'edit'
        ]);

        $resolver->setDefaults([
            'data_class' => 'Randomovies\Entity\Movie',
            'edit' => false
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'randomovies_movie';
    }
}
