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
use Symfony\Component\HttpFoundation\Session\Session;

class BrowserController
{
    const SESSION_PATH = 'session.path';

    private $templating;
    private $registry;
    private $session; 

    public function __construct(
        RepositoryRegistry $registry,
        EngineInterface $templating,
        Session $session
    )
    {
        $this->templating = $templating;
        $this->registry = $registry;
        $this->session = $session;
    }

    public function indexAction(Request $request)
    {
        $repositoryName = $request->get('repository', null);
        $repository = $this->registry->get($repositoryName);
        $path = $request->query->get('path') ?: null;
        $template = $request->get('template', 'CmfColumnBrowserBundle::index.html.twig');

        // resolve the repository name (it may have been determined automatically)
        $repositoryName = $this->registry->getRepositoryAlias($repository);

        if ($this->session->has(self::SESSION_PATH)) {
            $paths = $this->session->get(self::SESSION_PATH);
        }

        if (null === $path && isset($paths[$repositoryName])) {
            $path = $paths[$repositoryName];
        }

        if (null !== $path) {
            $paths[$repositoryName] = $path;
            $this->session->set(self::SESSION_PATH, $paths);
        }

        $path = $path ?: '/';

        $columnBuilder = new ColumnBuilder($repository);
        $columns = $columnBuilder->build($path);
        $repositories = $this->registry->names();

        return $this->templating->renderResponse(
            $template,
            [
                'repositories' => $repositories,
                'repositoryName' => $repositoryName,
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
        $operations = $request->request->get('operations', []);
        $repository = $this->registry->get($repositoryName);

        foreach ($operations as $operation) {
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
