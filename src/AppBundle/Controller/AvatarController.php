<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AvatarController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return new JsonResponse([]);
    }

    /**
     * @Route("/avatars/{hash}")
     * @Method("GET")
     *
     * @param $hash
     * @return JsonResponse
     */
    public function getAction($hash)
    {
        return new JsonResponse([$hash]);
    }

    /**
     * @Route("/avatars")
     * @Method("POST")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function postAction(Request $request)
    {
        return new JsonResponse([], JsonResponse::HTTP_CREATED);
    }

}
