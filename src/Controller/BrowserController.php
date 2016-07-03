<?php

namespace Symfony\Cmf\Bundle\ColumnBrowserBundle\Controller;

use Puli\Repository\Api\ResourceRepository;
use Symfony\Component\Templating\EngineInterface;
use Symfony\Cmf\Bundle\ResourceBundle\Registry\ContainerRepositoryRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Cmf\Bundle\ColumnBrowserBundle\Column\ColumnBuilder;
use Symfony\Cmf\Bundle\ResourceBundle\Registry\RepositoryRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;

class BrowserController
{
    private $templating;
    private $registry;

    public function __construct(
        RepositoryRegistry $registry,
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
        $template = $request->get('template', 'CmfColumnBrowserBundle::index.html.twig');

        $columnBuilder = new ColumnBuilder($repository);
        $columns = $columnBuilder->build($path);

        return $this->templating->renderResponse(
            $template,
            [
                'selectedPath' => $path,
                'columns' => $columns,
                'route' => $request->attributes->get('_route'),
            ],
            new Response()
        );
    }

    public function updateAction(Request $request)
    {
        $repositoryName = $request->get('repository', null);
        $repository = $this->registry->get($repositoryName);

        foreach ($request->request->get('operations') as $operation) {
            switch ($operation['type']) {
                case 'reorder':
                    $repository->reorder($operation['path'], (int) $operation['position']);
                    break;
                default:
                    throw new \InvalidArgumentException(sprintf(
                        'Invalid operation "%s"', $operation
                    ));
            }
        }

        return new JsonResponse($request->request->all());
    }
}
