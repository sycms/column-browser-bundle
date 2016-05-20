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
        $columnNames = explode('/', ltrim($path, '/'));
        array_unshift($columnNames, '/');

        $elements = [];
        foreach ($columnNames as $columnName) {
            if ($columnName !== '/') {
                $elements[] = $columnName;
            }

            $columnPath = empty($elements) ? '/' : '/' . implode('/', $elements);

            $column = new Column($columnName);
            $resource = $this->repository->get($columnPath);

            foreach ($resource->listChildren() as $child) {
                $column->addResource($child);
            }

            $columns[] = $column;
        }

        return $columns;
    }
}
