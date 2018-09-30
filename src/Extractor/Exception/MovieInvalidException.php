<?php

namespace Randomovies\Extractor\Exception;

class MovieInvalidException extends \Exception
{
    /**
     * @var array
     */
    private $errors;

    /**
     * @param array $errors
     *
     * @return MovieInvalidException
     */
    public function setErrors(array $errors): self
    {
        $this->errors = $errors;

        return $this;
    }
    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
