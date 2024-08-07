<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class HomeController extends AbstractController
{
  #[route("/", name: "home")]
  function index(TranslatorInterface $translator): Response
  // Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $hasher
  {
    // $user = new User();
    // $user->setEmail('john@doe.fr')
    //   ->setUsername('johnDoe')
    //   ->setPassword($hasher->hashPassword($user, '0000'))
    //   ->setRoles([]);
    // $em->persist($user);
    // $em->flush();
    return $this->render("home/index.html.twig");
  }
}
