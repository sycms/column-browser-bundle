<?php

namespace Symfony\Cmf\Bundle\ColumnBrowserBundle\Controller;

use Puli\Repository\Api\ResourceRepository;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Cmf\Bundle\ResourceBundle\Registry\ContainerRepositoryRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Cmf\Bundle\ColumnBrowserBundle\Column\ColumnBuilder;

class BrowserController
{
    private $templating;
    private $registry;

    public function __construct(
        ContainerRepositoryRegistry $registry,
        EngineInterface $templating
    )
    {
        $this->templating = $templating;
        $this->registry = $registry;
    }

    public function indexAction(Request $request)
    {
        $repositoryName = $request->get('repository', null);
        $repository = $this->registry->get($repositoryName);
        $path = $request->query->get('path') ?: '/';

        $columnBuilder = new ColumnBuilder($repository);
        $columns = $columnBuilder->build($path);

        return $this->templating->renderResponse(
            'CmfColumnBrowserBundle::index.html.twig',
            [
                'selectedPath' => $path,
                'columns' => $columns
            ],
            new Response()
        );
    }
}
