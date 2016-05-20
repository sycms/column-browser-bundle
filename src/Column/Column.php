<?php

namespace Symfony\Cmf\Bundle\ColumnBrowserBundle\Column;

use Puli\Repository\Api\Resource\Resource;

class Column
{
    private $name;
    private $resources = [];
    private $isSelected;

    public function __construct($name, $isSelected = false)
    {
        $this->name = $name;
        $this->isSelected = $isSelected;
    }

    public function addResource(Resource $resource)
    {
        $this->resources[] = $resource;
    }

    public function getResources()
    {
        return $this->resources;
    }

    public function getName()
    {
        return $this->name;
    }

    public function isSelected()
    {
        return $this->isSelected;
    }
}
