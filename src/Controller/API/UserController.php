<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{

  #[Route('/api/me')]
  public function me()
  {
    return $this->json(['message' => 'Bonjour']);
  }
}
