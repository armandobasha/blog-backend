<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class UserFixtures extends AbstractFixture implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function load(ObjectManager $manager)
    {
        $passwordEncoder = $this->container->get('security.password_encoder');

        $armandoAdmin = new User();
        $armandoAdmin->setFirstName('Armando');
        $armandoAdmin->setLastName('Basha');
        $armandoAdmin->setUsername('armando_admin');
        $armandoAdmin->setEmail('armandobasha1@gmail.com');
        $armandoAdmin->setRoles(['ROLE_ADMIN']);
        $encodedPassword = $passwordEncoder->encodePassword($armandoAdmin, 'test123');
        $armandoAdmin->setPassword($encodedPassword);
        $armandoAdmin->setEnabled(true);
        $manager->persist($armandoAdmin);

        $johnUser = new User();
        $johnUser->setFirstName('John');
        $johnUser->setLastName('Doe');
        $johnUser->setUsername('john_doe');
        $johnUser->setEmail('john_doe@symfony.com');
        $encodedPassword = $passwordEncoder->encodePassword($johnUser, 'test123');
        $johnUser->setPassword($encodedPassword);
        $johnUser->setEnabled(true);
        $manager->persist($johnUser);

        $manager->flush();
    }
}