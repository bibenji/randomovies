<?php

namespace Randomovies\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class TagType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('name', ChoiceType::class, [
//
//            ])

            ->add('name', EntityType::class, [
                'class' => 'Randomovies:Tag',
                'choice_label' => 'name',

//                'query_builder' => function (PersonRepository $pr) {
//                    return $pr
//                        ->createQueryBuilder('p')
//                        ->orderBy('p.lastname', 'ASC')
//                    ;
//                },

            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'randomovies_tag';
    }
}
