<?php
/**
 * Created by PhpStorm.
 * User: recchia
 * Date: 25/09/16
 * Time: 20:12
 */

namespace AppBundle\Factory;


use AppBundle\Entity\Avatar;

class AvatarFactory
{
    public static function create($data)
    {
        $avatar = new Avatar();
        $avatar->setEmail($data['email']);
        $avatar->setHash(md5($data['email']));
        $avatar->setImageString($data['image']);
        $avatar->setMimeType($data['mime-type']);
        $avatar->setActive(false);

        return $avatar;
    }
}