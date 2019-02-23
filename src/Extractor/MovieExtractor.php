<?php

namespace Randomovies\Extractor;

use Randomovies\Dto\MovieDto;
use Randomovies\Extractor\Exception\MovieInvalidException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MovieExtractor
{
    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function handle(Spreadsheet $reader)
    {
        $i = 0;
        $errors = [];
        $movies = [];

        $sheetData = $reader->getActiveSheet()->toArray();

        unset($sheetData[0]);

        foreach ($sheetData as $data) {

            $row = array_map('trim', $data);
            if ('' === $row[0]) {
                continue;
            }

            $movieDto = new MovieDto($row);
            $movies[] = $movieDto;

            $violations = $this->validator->validate($movieDto);

            foreach ($violations as $violation) {
                $errors[$i][] = $violation;
            }

            if (count($errors) > 0) {
                $movieInvalidException = new MovieInvalidException();
                $movieInvalidException->setErrors($errors);
                throw $movieInvalidException;
            }
        }

        return $movies;
    }
}
