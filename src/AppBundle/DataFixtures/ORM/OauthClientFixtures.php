<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Client;

class OauthClientFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $client = new Client();

        $client->setAllowedGrantTypes(array('password', 'refresh_token'));
        $client->setRandomId("k9wl4xgu4pskowkgwkww8gs80woowkoc4w04488soockw4g80");
        $client->setSecret("2yqyuymr6rk00w408ss0sgogscwso0g44kcs8kc4cow4ggowos");

        $manager->persist($client);
        $manager->flush();
    }
}