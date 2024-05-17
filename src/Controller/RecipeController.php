<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeController extends AbstractController
{
  #[Route('/recettes', name: 'recipe.index')]
  public function index(Request $request, RecipeRepository $repository): Response // ou + EntityManagerInterface $em
  {
    $recipes = $repository->findWithDurationLowerThan(20);
    // Supprimer une recette
    // $em->remove($recipes[0]);
    // $em->flush();

    // Ajouter une nouvelle recette
    // $recipes = $repository->findWithDurationLowerThan(20);
    // $recipe = new Recipe();
    // $recipe->setTitle('Barbe à papa')
    //   ->setSlug('barbe-papa')
    //   ->setContent('Pour préparer une délicieuse barbe à papa, commencez par faire fondre du sucre dans une machine à barbe à papa. Ensuite, utilisez un bâton pour enrouler les filaments de sucre autour jusqu\'à obtenir une belle forme. C\'est une friandise parfaite pour les fêtes foraines ou les anniversaires.')
    //   ->setDuration(2)
    //   ->setCreatedAt(new \DateTimeImmutable())
    //   ->setCreatedAt(new \DateTimeImmutable());
    // $em->persist($recipe);
    // $em->flush();
    return $this->render('recipe/index.html.twig', ['recipes' => $recipes]);
  }


  #[Route('/recettes/{slug}-{id}', name: 'recipe.show', requirements: ['id' => '\d+', 'slug' => '[a-z0-9-]+'])]
  public function show(Request $request, string $slug, int $id, RecipeRepository $repository): Response
  {
    $recipe = $repository->find($id);
    if ($recipe->getSlug() != $slug) {
      return $this->redirectToRoute('recipe.show', ['slug' => $recipe->getSlug(), 'id' => $recipe->getId()]);
    }
    // dd($recipe);
    return $this->render(
      'recipe/show.html.twig',
      [
        'recipe' => $recipe,
      ]
    );
  }

  #[Route("/recettes/{id}/edit", name: "recipe.edit")]
  public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em)
  {
    $form = $this->createForm(RecipeType::class, $recipe);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $em->flush();
      $this->addFlash(
        'success',
        'La recette a bien été modifié'
      );
      return $this->redirectToRoute('recipe.index');
    }
    return $this->render('recipe/edit.html.twig', ['recipe' => $recipe, 'form' => $form]);
  }
}
