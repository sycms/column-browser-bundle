<?php

namespace Symfony\Cmf\Bundle\ColumnBrowserBundle\Tests\Unit\Column;

use Puli\Repository\Api\ResourceRepository;
use Puli\Repository\Api\Resource\Resource;
use Puli\Repository\FilesystemRepository;
use Symfony\Cmf\Bundle\ColumnBrowserBundle\Column\ColumnBuilder;

class ColumnBuilderTest extends \PHPUnit_Framework_TestCase
{
    private $builder;
    private $repository;

    public function setUp()
    {
        $this->repository = new FilesystemRepository(__DIR__ . '/filesystem');
        $this->builder = new ColumnBuilder($this->repository);
    }

    /**
     * It should build serveral columns for a target path.
     */
    public function testBuild()
    {
        $columns = $this->builder->build('/dir1/dir2');

        $this->assertCount(3, $columns);
        $this->assertEquals('/', $columns[0]->getName());
        $this->assertCount(2, $columns[0]->getResources());

        $this->assertEquals('dir1', $columns[1]->getName());
        $this->assertCount(3, $columns[1]->getResources());
    }
}
