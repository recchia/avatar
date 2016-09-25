<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class AvatarController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return new JsonResponse([]);
    }

}
