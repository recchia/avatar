<?php
/**
 * Created by PhpStorm.
 * User: recchia
 * Date: 25/09/16
 * Time: 17:57
 */

namespace AppBundle\Service;


use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Glide\Responses\SymfonyResponseFactory;
use League\Glide\ServerFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

class AvatarService
{
    protected $container;
    protected $server;
    protected $defaultDimension;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->defaultDimension = $this->container->getParameter('avatar_dimension_default');
        $maxSize = $this->container->getParameter('avatar_max_image_size');
        $this->server = ServerFactory::create([
            'source' => new Filesystem(new Local(
                $this->container->getParameter('kernel.root_dir') . '/../var/avatars/source'
            )),
            'cache' => new Filesystem(new Local(
                $this->container->getParameter('kernel.cache_dir')
            )),
            'max_image_size' => $maxSize * $maxSize,
            'response' => new SymfonyResponseFactory()
        ]);
    }

    public function getServer()
    {
        return $this->server;
    }

    public function getDefaultDimension()
    {
        return $this->defaultDimension;
    }
}