<?php

namespace AppBundle\Form;

use AppBundle\Entity\Movie;
use AppBundle\Entity\MovieSearch;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MovieSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, array(
                'required' => false,
            ))
            ->add('synopsis', null, array(
                'required' => false,
            ))
            ->add('genre', null, array(
                'required' => false,
            ))
            ->add('genre', ChoiceType::class, [
                'choices' => [
                    "Tous genres confondus" => "",
                    Movie::SCIENCE_FICTION => Movie::SCIENCE_FICTION,
                    Movie::COMEDIE_DRAMATIQUE => Movie::COMEDIE_DRAMATIQUE,
                    Movie::COMEDIE => Movie::COMEDIE,
                    Movie::DRAME => Movie::DRAME
                ],
                'required' => false
            ])
//            ->add('yearFrom', DateType::class, array(
//                 'required' => false,
//                 'widget' => 'single_text',
//             ))
//            ->add('yearTo', DateType::class, array(
//                'required' => false,
//                'widget' => 'single_text',
//            ))
//			->add('actors', null, array(
//                'required' => false,
//            ))
//			->add('director', null, array(
//                'required' => false,
//            ))
            ->add('search', SubmitType::class)
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            // avoid to pass the csrf token in the url (but it's not protected anymore)
            'csrf_protection' => false,
            'data_class' => 'AppBundle\Entity\MovieSearch'
        ));
    }

    public function getName()
    {
        return 'movie_search_type';
    }
}