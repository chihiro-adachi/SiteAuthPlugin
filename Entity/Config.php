<?php

namespace Plugin\SiteAuthPlugin\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Config
 *
 * @ORM\Table(name="plg_site_auth_plugin_config")
 * @ORM\Entity(repositoryClass="Plugin\SiteAuthPlugin\Repository\ConfigRepository")
 */
class Config
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="string", length=255)
     */
    private $content;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $name
     *
     * @return $this;
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $path
     *
     * @return $this;
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @param string $content
     *
     * @return $this;
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }
}
