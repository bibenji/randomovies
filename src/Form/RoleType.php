<?php

namespace Randomovies\Form;

use Randomovies\Entity\Person;
use Randomovies\Entity\Role;
use Randomovies\Repository\PersonRepository;
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
            ->add('person', EntityType::class, [
                'class' => 'Randomovies:Person',
                'choice_label' => 'fullname',
            ])
            ->add('role', ChoiceType::class, [
                'choices' => [
                    Role::ROLE_ACTOR => Role::ROLE_ACTOR,
                    Role::ROLE_PRODUCER => Role::ROLE_PRODUCER,
                    Role::ROLE_REALISATOR => Role::ROLE_REALISATOR
                ]
            ])
            ->add('personnage')
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Randomovies\Entity\Role'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'randomovies_role';
    }
}
