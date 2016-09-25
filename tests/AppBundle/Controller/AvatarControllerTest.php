<?php
/**
 * Created by PhpStorm.
 * User: recchia
 * Date: 25/09/16
 * Time: 16:32
 */

namespace tests\AppBundle\Controller;

use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class AvatarControllerTest extends WebTestCase
{
    public function testAvatarGet()
    {
        $faker = Factory::create();
        $hash = md5($faker->email);
        $url = '/avatars/' . $hash;

        $client = static::createClient();
        $client->request('GET', $url);

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"'
        );
    }
}