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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AvatarService
{
    protected $container;
    protected $server;
    protected $defaultDimension;

    /**
     * AvatarService constructor.
     * @param ContainerInterface $container
     */
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

    /**
     * @param $hash
     * @param $dimension
     *
     * @return Response
     */
    public function getImageResponse($hash, $dimension)
    {
        $wh = !is_null($dimension) ? $dimension : $this->defaultDimension;

        return $this->server->getImageResponse($hash, ['w' => $wh, 'h' => $wh]);
    }

    /**
     * @param $dimension
     */
    protected function createTransparentImage($dimension)
    {
        $wh = !is_null($dimension) ? $dimension : $this->defaultDimension;

        if (!file_exists($this->server->getSourcePath() . '/transparent.png')) {
            $image = imagecreatetruecolor($wh, $wh);
            imagecolortransparent($image);
            imagepng($image, $this->server->getSourcePath() . '/transparent.png');
        }
    }

    public function getAlternative($s, $d)
    {
        if (!filter_var($d, FILTER_VALIDATE_URL) === false) {
            return $this->getFromUrl($d);
        }

        if ($d == 'blank') {
            $this->createTransparentImage($s);
            return $this->server->getImageResponse('/transparent.png', ['w' => $s, 'h' => $s]);
        }

        if (preg_match('/^#(?:[0-9a-f]{3}){1,2}$/i', $d)) {
            return $this->getHexImage($d, $s);
        }

        return new JsonResponse([
            'code' => 400000,
            'message' => 'avatar not found',
            'link' => ''
        ], JsonResponse::HTTP_NOT_FOUND);
    }

    /**
     * @param $url
     * @return Response
     */
    protected function getFromUrl($url)
    {
        $response = new Response();
        $disposition = $response->headers->makeDisposition(\Symfony\Component\HttpFoundation\ResponseHeaderBag::DISPOSITION_INLINE, 'avatar.png');
        $response->headers->set('Content-Disposition', $disposition);
        $response->headers->set('Content-Type', 'image/png');
        $response->setContent(file_get_contents($url));

        return $response;
    }

    /**
     * @param $hex
     * @param $dimension
     *
     * @return Response
     */
    protected function getHexImage($hex, $dimension)
    {
        $wh = !is_null($dimension) ? $dimension : $this->defaultDimension;

        $name = str_replace('#', '', $hex);

        if (!file_exists($this->server->getSourcePath() . '/' . $name)) {
            $image = imagecreatetruecolor($wh, $wh);
            list($r, $g, $b) = sscanf($hex, "#%02x%02x%02x");
            imagecolorallocate( $image, $r, $g, $b );
            imagepng($image, $this->server->getSourcePath() . '/' . $name);
        }

        return $this->server->getImageResponse($name, ['w' => $wh, 'h' => $wh]);
    }

}