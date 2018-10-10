<?php

namespace Randomovies\Form\Admin;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ImportType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', FileType::class, [
                'label' => 'Fichier',
                'constraints' => [
                    new Assert\Callback(function ($data, ExecutionContextInterface $context, $payload) {
                        if (!($data instanceof UploadedFile && $this->isDocType($data->getMimeType()))) {
                            $context->addViolation('Le format du fichier n\'est pas valide.');
                        }
                    }),
                ],
            ])
            // ->add('verification', SubmitType::class, [
                // 'attr' => [
                    // 'class' => 'btn btn-primary',
                // ],
                // 'label' => 'Vérifier le fichier',
            // ])
            ->add('importer', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary',
                ],
                'label' => 'Lancer l\'import',
            ])
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }

    /**
     * A mettre dans une class à part dans un dossier Utils or Tools si besoin.
     *
     * @param string $mimeType
     * @return bool
     */
    private function isDocType(string $mimeType): bool
    {		
        return in_array($mimeType, [
            'application/octet-stream',
			'application/vnd.ms-excel',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'application/vnd.oasis.opendocument.spreadsheet',
        ]);
    }
}