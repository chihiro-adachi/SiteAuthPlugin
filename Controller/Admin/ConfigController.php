<?php

namespace Plugin\SiteAuthPlugin\Controller\Admin;

use Eccube\Controller\AbstractController;
use Eccube\Util\CacheUtil;
use Plugin\SiteAuthPlugin\Entity\Config;
use Plugin\SiteAuthPlugin\Form\Type\Admin\ConfigType;
use Plugin\SiteAuthPlugin\Repository\ConfigRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;

class ConfigController extends AbstractController
{
    /**
     * @var ConfigRepository
     */
    protected $configRepository;

    /**
     * ConfigController constructor.
     *
     * @param ConfigRepository $configRepository
     */
    public function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    /**
     *
     * @Route("/%eccube_admin_route%/site_auth_plugin/config", name="site_auth_plugin_admin_config")
     * @Template("@SiteAuthPlugin/admin/config.twig")
     */
    public function index(Request $request)
    {
        $Configs = $this->configRepository->findBy([], ['id' => 'ASC']);

        return [
            'Configs' => $Configs,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/site_auth_plugin/config/{id}/delete", requirements={"id" = "\d+"}, name="site_auth_plugin_admin_config_delete", methods={"DELETE"})
     * @Template("@SiteAuthPlugin/admin/config.twig")
     */
    public function delete($id)
    {
        $this->isTokenValid();

        $Config = $this->configRepository->find($id);

        if (null === $Config) {
            throw new NotFoundHttpException();
        }

        $this->entityManager->remove($Config);
        $this->entityManager->flush();

        $this->generateRouteFile();

        $this->addSuccess('削除しました。', 'admin');

        return $this->redirectToRoute('site_auth_plugin_admin_config');
    }

    /**
     * @Route("/%eccube_admin_route%/site_auth_plugin/config/new", name="site_auth_plugin_admin_config_new")
     * @Route("/%eccube_admin_route%/site_auth_plugin/config/{id}/edit", requirements={"id" = "\d+"}, name="site_auth_plugin_admin_config_edit")
     * @Template("@SiteAuthPlugin/admin/edit.twig")
     */
    public function createOrEdit(Request $request, $id = null, CacheUtil $cacheUtil)
    {
        $Config = new Config();
        if ($id !== null) {
            $Config = $this->configRepository->find($id);
            if ($Config === null) {
                throw new NotFoundHttpException();
            }
        }

        $form = $this->createForm(ConfigType::class, $Config);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $Config = $form->getData();
            $this->entityManager->persist($Config);
            $this->entityManager->flush();

            $this->generateRouteFile();

            $this->addSuccess('登録しました。', 'admin');

            $cacheUtil->clearCache();

            return $this->redirectToRoute('site_auth_plugin_admin_config');
        }

        return [
            'form' => $form->createView(),
        ];
    }

    private function generateRouteFile()
    {
        $Configs = $this->configRepository->findBy([], ['id' => 'ASC']);

        $ConfigArray = [];
        foreach ($Configs as $Config) {
            $ConfigArray['_plg_site_auth_plugin_routes_'.$Config->getId()] = [
                'path' => $Config->getPath(),
                'controller' => 'Plugin\SiteAuthPlugin\Controller\SiteAuthController::index'
            ];
        }

        $yaml = $ConfigArray ? Yaml::dump($ConfigArray) : '';
        $file = $this->getParameter('kernel.project_dir') . '/app/PluginData/SiteAuthPlugin/routes_generated.yaml';
        file_put_contents($file, $yaml);
    }
}
