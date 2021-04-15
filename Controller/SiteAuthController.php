<?php

namespace Plugin\SiteAuthPlugin\Controller;

use Eccube\Controller\AbstractController;
use Plugin\SiteAuthPlugin\Repository\ConfigRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SiteAuthController extends AbstractController
{
    /**
     * @var ConfigRepository
     */
    protected $configRepository;

    public function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $path = $request->getPathInfo();
        $Config = $this->configRepository->findOneBy(['path' => $path]);

        if ($Config === null) {
            throw new NotFoundHttpException();
        }

        return new Response($Config->getContent());
    }
}
