<?php

namespace App\Entity;

class Search
{
    private $search;

    public function getSearch()
    {
        return $this->search;
    }

    public function setSearch($search)
    {
        $this->search = $search;
    }
}
