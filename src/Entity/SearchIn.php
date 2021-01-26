<?php

namespace App\Entity;

class SearchIn
{
    private $searchin;

    private $infield;

    public function getSearchin()
    {
        return $this->searchin;
    }

    public function setSearchin($searchin)
    {
        $this->searchin = $searchin;
    }

    public function getInfield()
    {
        return $this->infield;
    }

    public function setInfield($infield)
    {
        $this->infield = $infield;
    }
}
