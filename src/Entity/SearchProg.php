<?php

namespace App\Entity;

class SearchProg
{
    private $search;
    private $client;
    private $machine;

    public function getSearch()
    {
        return $this->search;
    }

    public function setSearch($search)
    {
        $this->search = $search;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setClient($client)
    {
        $this->client = $client;
    }

    public function getMachine()
    {
        return $this->machine;
    }

    public function setMachine($machine)
    {
        $this->machine = $machine;
    }
}
