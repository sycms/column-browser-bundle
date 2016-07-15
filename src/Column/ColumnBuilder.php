<?php

namespace Sycms\Bundle\ColumnBrowserBundle\Column;

use Puli\Repository\Api\ResourceRepository;
use Sycms\Bundle\ColumnBrowserBundle\Column\Column;

class ColumnBuilder
{
    private $repository;

    public function __construct(ResourceRepository $repository)
    {
        $this->repository = $repository;
    }

    public function build($path)
    {
        $paths = [];
        $columns = [];

        if ($path !== '/') {
            $columnNames = explode('/', ltrim($path, '/'));
            array_unshift($columnNames, '/');
        } else {
            $columnNames = [ '/' ];
        }

        $elements = [];
        foreach ($columnNames as $columnName) {
            if ($columnName !== '/') {
                $elements[] = $columnName;
            }

            $columnPath = empty($elements) ? '/' : '/' . implode('/', $elements);

            $resource = $this->repository->get($columnPath);
            $children = $resource->listChildren();
            
            if (0 === $children->count()) {
                continue;
            }

            $columns[] = $resource;
        }

        return $columns;
    }
}
