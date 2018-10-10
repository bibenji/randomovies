<?php

namespace Randomovies\Controller\Admin;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Randomovies\Dto\Mapper\MovieMapper;
use Randomovies\Entity\Movie;
use Randomovies\Extractor\Exception\MovieInvalidException;
use Randomovies\Extractor\MovieExtractor;
use Randomovies\Form\Admin\ImportType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use Randomovies\Dto\MovieDto;

class AdminImportController extends Controller
{
    /**
     * @Route("/admin/import", name="admin-import")
     */
    public function importAction(Request $request)
    {
        $form = $this->createForm(ImportType::class);
        $form->handleRequest($request);
        $errors = [];

        if (Request::METHOD_POST === $request->getMethod() && $form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('file')->getData();

            /** @var MovieExtractor $movieExtractor */
            $movieExtractor = $this->get('app.extractor.movie');
						
            try {
                $spreadsheet = IOFactory::load($file->getPathName());				
                $movies = $movieExtractor->handle($spreadsheet);
								
				$tags = $this->getDoctrine()->getRepository('Randomovies:Tag')->findAll();		
				$movieMapper = new MovieMapper($tags);
				
                /** @var Movie $movie */
                foreach ($movies as $movieDto) {
                    $movie = new Movie();
                    $movieMapper->map($movieDto, $movie);
                    $this->getDoctrine()->getManager()->persist($movie);
                }

                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success', 'Tous les films ont bien été importés !');
            }
            catch (MovieInvalidException $movieInvalidException) {
                $errors = $movieInvalidException->getErrors();
                $this->addFlash('error', 'Le fichier contient des films invalides.');
            }
        }

        return $this->render('admin/import/import.html.twig', [
            'errors' => $errors,
            'form' => $form->createView()
        ]);
    }
}