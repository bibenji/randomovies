<?php

namespace Randomovies\Twig\Extension;

use Doctrine\Common\Collections\Collection;

class TotalUsersNotesExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('totalUsersNotes', array($this, 'getTotalUsersNotes'))
        );
    }

    public function getTotalUsersNotes(Collection $movieComments)
    {
        if ($movieComments->count() === 0) {
            return null;
        }

        $total = 0;

        foreach ($movieComments as $comment) {
            $total += $comment->getNote();
        }

        return $total / $movieComments->count();
    }
}