<?php

namespace Randomovies\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => false
            ])
            ->add('path', FileType::class, [
                'required' => false,
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event, $options) {
            $mediasDirectory = $event->getForm()->getConfig()->getOptions()['medias_directory'];
            $media = $event->getData();
            $form = $event->getForm();

            if (null !== $media && null !== $media->getPath()) {
                $form->add('path', FileType::class, ['required' => false, 'mapped' => false]);
            } else {
                $form->add('path', FileType::class, ['required' => false]);
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Randomovies\Entity\Media',
            'medias_directory' => null,
        ));

        $resolver->setRequired(array(
            'medias_directory',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'randomovies_media';
    }
}
