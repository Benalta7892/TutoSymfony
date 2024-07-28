<?php

namespace App\Controller\API;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RecipesController extends AbstractController
{

  #[Route("/api/recipes", methods: ['GET'])]
  public function index(RecipeRepository $repository)
  {
    $recipes = $repository->findAll();
    return $this->json($recipes, 200, [], [
      'groups' => ['recipes.index']
    ]);
  }
}
