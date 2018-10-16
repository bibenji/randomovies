<?php

namespace Randomovies\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotBlank;

class ReviewType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   
    	$fieldsOptions = [];
    	if (!$options['edit']) {
    		$fieldsOptions = [
    			'constraints' => [
    					new NotBlank([
    						'message' => 'Une review doit être renseignée à la création du film.',
    					])
    			],
    			'required' => true,
    		];    		
    	}
    	
        $builder
        	->add('rating', IntegerType::class, $fieldsOptions)
        	->add('review', TextareaType::class)
        ;
        
        $builder->addEventListener(FormEvents::POST_SET_DATA, function($event) use ($options) {
        	$builder = $event->getForm(); // The FormBuilder
        	$review = $event->getData(); // The Form Object
        	
        	if ($review->getUser() !== $options['current_user']) {
        		$builder
        			->add('rating', IntegerType::class, [
        				'attr' => ['readonly' => true],
        			])
        			->add('review', TextareaType::class, [
        					'attr' => ['readonly' => true],
        			])
        		;
        	}
        });
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Randomovies\Entity\Review',
        	'current_user' => NULL,
        	'edit' => FALSE,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'randomovies_review';
    }
}
