<?php

namespace AppBundle\Form;

use AppBundle\Entity\Person;
use AppBundle\Entity\Role;
use AppBundle\Repository\PersonRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('movie')
            ->add('person', EntityType::class, [
                'class' => 'AppBundle:Person',
                'choice_label' => 'fullname',

//                'class' => Person::class,
//                'query_builder' => function (PersonRepository $pr) {
//                    return $pr
//                        ->createQueryBuilder('p')
//                        ->orderBy('p.lastname', 'ASC')
//                    ;
//                },
//                'choice_label' => 'lastname',
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    Role::ROLE_ACTOR => Role::ROLE_ACTOR,
                    Role::ROLE_PRODUCER => Role::ROLE_PRODUCER,
                    Role::ROLE_REALISATOR => Role::ROLE_REALISATOR
                ]
            ])
            ->add('character')
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Role'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_role';
    }


}
