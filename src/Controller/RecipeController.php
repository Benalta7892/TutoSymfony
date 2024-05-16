<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeController extends AbstractController
{
  #[Route('/recettes', name: 'recipe.index')]
  public function index(Request $request, RecipeRepository $repository): Response
  {
    $recipes = $repository->findAll();
    // dd($recipes);
    return $this->render('recipe/index.html.twig', ['recipes' => $recipes]);
  }


  #[Route('/recettes/{slug}-{id}', name: 'recipe.show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
  public function show(Request $request, string $slug, int $id): Response
  {
    return $this->render(
      'recipe/show.html.twig',
      [
        'slug' => $slug,
        'id' => $id,
        'demo' => '<strong>Hello</strong>',
        'person' => [
          'firstname' => 'john',
          'lastname' => 'Doe'
        ]
      ]
    );
  }
}
