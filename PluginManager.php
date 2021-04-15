<?php


namespace Plugin\SiteAuthPlugin;

use Eccube\Plugin\AbstractPluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;

class PluginManager extends AbstractPluginManager
{
    public function install(array $meta, ContainerInterface $container)
    {
        $this->generateRouteFile($container);
    }

    public function uninstall(array $meta, ContainerInterface $container)
    {
        $this->removeRouteFile($container);
    }

    private function generateRouteFile(ContainerInterface $container)
    {
        $dir = $container->getParameter('kernel.project_dir').'/app/PluginData/SiteAuthPlugin';
        $file = 'routes_generated.yaml';

        $fs = new Filesystem();
        $fs->remove($dir);
        $fs->mkdir($dir);
        $fs->dumpFile($dir.'/'.$file, '');
    }

    private function removeRouteFile(ContainerInterface $container)
    {
        $dir = $container->getParameter('kernel.project_dir').'/app/PluginData/SiteAuthPlugin';

        $fs = new Filesystem();
        $fs->remove($dir);
    }
}
