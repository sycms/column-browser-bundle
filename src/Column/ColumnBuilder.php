<?php

namespace Symfony\Cmf\Bundle\ColumnBrowserBundle\Column;

use Puli\Repository\Api\ResourceRepository;
use Symfony\Cmf\Bundle\ColumnBrowserBundle\Column\Column;

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

            $column = new Column($columnName !== '/' ? $columnName : 'root');
            $resource = $this->repository->get($columnPath);
            $children = $resource->listChildren();
            
            if (0 === $children->count()) {
                continue;
            }

            foreach ($children as $child) {
                $column->addResource($child);
            }

            $columns[] = $column;
        }

        return $columns;
    }
}
