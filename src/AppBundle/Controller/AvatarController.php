<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Avatar;
use AppBundle\Factory\AvatarFactory;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
     * @Route("/avatars/{hash}/{s}")
     * @Method("GET")
     *
     * @param $hash
     * @param $s
     * @return JsonResponse
     */
    public function getAction($hash, $s = null)
    {
        $em = $this->getDoctrine()->getManager();
        $avatar = $em->getRepository('AppBundle:Avatar')->findOneBy(['hash' => $hash]);
        if ($avatar instanceof Avatar) {
            $serverImage = $this->get('avatar_service')->getServer();
            $dimension = !is_null($s) ? $s : $this->get('avatar_service')->getDefaultDimension();

            return $serverImage->getImageResponse($avatar->getHash(), ['w' => $dimension, 'h' => $dimension]);
        } else {
            return new JsonResponse([
                'code' => 400000,
                'message' => 'avatar not found',
                'link' => ''
            ], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Route("/avatars")
     * @Method("POST")
     *
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function postAction(Request $request)
    {
        $avatar = AvatarFactory::create($request->request->all());

        $error = $this->isValidRequest($avatar);

        if (count($error) > 0) {
            return new JsonResponse($error, JsonResponse::HTTP_BAD_REQUEST);
        }

        $this->handlerImage($avatar);

        $token = md5(uniqid($avatar->getEmail(), true));
        $body = 'For image upload validation please click on <a href="' . $this->generateUrl(
                'avatar.confirm',
                ['action' => 'upload', 'token' => $token],
                UrlGeneratorInterface::ABSOLUTE_URL
            ) . '">confirm</a>';
        $this->sendMail('Confirm image upload', $avatar->getEmail(), $body);
        $avatar->setConfirmationToken($token);
        $em = $this->getDoctrine()->getManager();
        $em->persist($avatar);
        $em->flush();

        return new Response('', Response::HTTP_CREATED);
    }

    /**
     * @Route("/avatars/{hash}")
     * @Method("DELETE")
     *
     * @param $hash
     * @return JsonResponse
     */
    public function deleteAction($hash)
    {
        $em = $this->getDoctrine()->getManager();
        $avatar = $em->getRepository('AppBundle:Avatar')->findOneBy(['hash' => $hash]);

        if ($avatar instanceof Avatar) {
            $token = md5(uniqid($avatar->getEmail(), true));
            $avatar->setDeleteToken($token);
            $em->flush();

            $body = 'For image delete validation please click on <a href="' . $this->generateUrl(
                    'avatar.confirm',
                    ['action' => 'delete', 'token' => $token],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ) . '">confirm</a>';
            $this->sendMail('Confirm image delete', $avatar->getEmail(), $body);

            return new JsonResponse(['email' => $avatar->getEmail()]);
        } else {
            return new JsonResponse([
                'code' => 400000,
                'message' => 'avatar not found',
                'link' => ''
            ], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Route("/confirmation/{action}/{token}", name="avatar.confirm")
     * @Method("GET")
     *
     * @param $action
     * @param $token
     * @return JsonResponse
     */
    public function confirmationAction($action, $token)
    {
        $em = $this->getDoctrine()->getManager();
        $notFound = [
            'code' => 400000,
            'message' => 'avatar not found',
            'link' => ''
        ];

        switch ($action) {
            case 'upload':
                $avatar = $em->getRepository('AppBundle:Avatar')->findOneBy(['confirmationToken' => $token]);
                if ($avatar instanceof Avatar) {
                    $email = $avatar->getEmail();
                    $avatar->setActive(true);
                    $avatar->setConfirmationToken(null);
                    $em->flush();
                } else {
                    return new JsonResponse($notFound, JsonResponse::HTTP_NOT_FOUND);
                }
                break;
            case 'delete':
                $avatar = $em->getRepository('AppBundle:Avatar')->findOneBy(['deleteToken' => $token]);
                if ($avatar instanceof Avatar) {
                    $email = $avatar->getEmail();
                    $em->remove($avatar);
                    $em->flush();
                } else {
                    return new JsonResponse($notFound, JsonResponse::HTTP_NOT_FOUND);
                }
                break;
            default:
                break;
        }

        return new JsonResponse(['email' => $email]);
    }

    /**
     * @param Avatar $avatar
     * @return array
     */
    protected function isValidRequest(Avatar $avatar)
    {
        $validator = $this->get('validator');
        $errors = $validator->validate($avatar);

        if (count($errors) > 0) {
            $_messages = [];

            foreach ($errors as $error) {
                $_messages[] = $error->getMessage();
            }

            return [
                'code' => 400001,
                'message' => implode(' | ', $_messages),
                'link' => ''
            ];
        }

        return [];
    }

    /**
     * @param Avatar $avatar
     */
    protected function handlerImage(Avatar $avatar)
    {
        $path = $this->getParameter('kernel.root_dir') . '/../var/avatars/source/';
        $file = fopen($path . $avatar->getHash(), 'wb');
        $data = explode(',', $avatar->getImageString());
        $content = count($data) > 1 ? $data[1] : $data[0];
        fwrite($file, base64_decode($content));
        fclose($file);
    }

    /**
     * @param $subject
     * @param $recipient
     * @param $body
     */
    protected function sendMail($subject, $recipient, $body)
    {
       $message = \Swift_Message::newInstance()
           ->setSubject($subject)
           ->setFrom('service@avatar.com')
           ->setTo($recipient)
           ->setBody($body)
       ;

        $this->get('mailer')->send($message);
    }

}
