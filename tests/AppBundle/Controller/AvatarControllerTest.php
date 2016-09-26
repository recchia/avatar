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
        /**$faker = Factory::create();
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
        );**/
    }

    public function testAvatarPostFailEmailBlank()
    {
        $faker = Factory::create();
        $client = static::createClient();

        $client->request('POST', '/avatars', [
            'email' => '',
            'image' => $faker->md5,
            'mime-type' => 'image/jpeg'
        ]);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"'
        );
        $this->assertJsonStringEqualsJsonString(
            json_encode(['code' => 400001, 'message' => 'You must provide email', 'link' => '']),
            $client->getResponse()->getContent()
        );
    }

    public function testAvatarPostFailEmailInvalid()
    {
        $faker = Factory::create();
        $client = static::createClient();

        $client->request('POST', '/avatars', [
            'email' => 'holamundo',
            'image' => $faker->md5,
            'mime-type' => 'image/jpeg'
        ]);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"'
        );
        $this->assertJsonStringEqualsJsonString(
            json_encode(['code' => 400001, 'message' => 'You must provide a valid email', 'link' => '']),
            $client->getResponse()->getContent()
        );
    }

    public function testAvatarPostFailImageBlank()
    {
        $faker = Factory::create();
        $client = static::createClient();

        $client->request('POST', '/avatars', [
            'email' => $faker->email,
            'image' => '',
            'mime-type' => 'image/jpeg'
        ]);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"'
        );
        $this->assertJsonStringEqualsJsonString(
            json_encode(['code' => 400001, 'message' => 'You must provide encoded image', 'link' => '']),
            $client->getResponse()->getContent()
        );
    }

    public function testAvatarPostFailMimeTypeInvalid()
    {
        $faker = Factory::create();
        $client = static::createClient();

        $client->request('POST', '/avatars', [
            'email' => $faker->email,
            'image' => $faker->md5,
            'mime-type' => ''
        ]);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $client->getResponse()->getStatusCode());
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header is "application/json"'
        );
        $this->assertJsonStringEqualsJsonString(
            json_encode(['code' => 400001, 'message' => 'Use a valid image type', 'link' => '']),
            $client->getResponse()->getContent()
        );
    }

    public function testAvatarPostSuccess()
    {
        $faker = Factory::create();
        $client = static::createClient();

        $client->request('POST', '/avatars', [
            'email' => $faker->email,
            'image' => $faker->md5,
            'mime-type' => 'image/jpeg'
        ]);

        $this->assertEquals(Response::HTTP_CREATED, $client->getResponse()->getStatusCode());
    }
}