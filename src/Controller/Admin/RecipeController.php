<?php

namespace App\Controller\Admin;

use App\Demo;
use App\Entity\Recipe;
use App\Entity\Category;
use App\Form\RecipeType;
use App\Repository\CategoryRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Validator\Validator\ValidatorInterface;


#[Route('/admin/recettes', name: 'admin.recipe.')]
class RecipeController extends AbstractController
{


  #[Route('/', name: 'index')]
  public function index(RecipeRepository $repository, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager): Response // ou + EntityManagerInterface $em
  {
    // $platPrincipal = $categoryRepository->findOneBy(['slug' => 'plat-principal']);
    // $pates = $repository->findOneBy(['slug' => 'pates-bolognaise']);
    // $pates->setCategory($platPrincipal);
    // $entityManager->flush();
    $recipes = $repository->findWithDurationLowerThan(20);
    // $category = (new Category())
    //   ->setUpdatedAt(new \DateTimeImmutable())
    //   ->setCreatedAt(new \DateTimeImmutable())
    //   ->setName('demo')
    //   ->setName('demo');
    // $recipes[0]->setCategory($category);
    // $entityManager->flush();
    // $recipes[0]->getCategory()->getName();
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
    return $this->render('admin/recipe/index.html.twig', ['recipes' => $recipes]);
  }

  #[Route("/create", name: "create")]
  public function create(Request $request, EntityManagerInterface $em)
  {
    $recipe = new Recipe();
    $form = $this->createForm(RecipeType::class, $recipe);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $em->persist($recipe);
      $em->flush();
      $this->addFlash(
        'success',
        'La recette a bien été créée'
      );
      return $this->redirectToRoute('admin.recipe.index');
    }
    return $this->render('admin/recipe/create.html.twig', ['form' => $form]);
  }

  #[Route("/{id}", name: "edit", methods: ['GET', 'POST'], requirements: ['id' => Requirement::DIGITS])]
  public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em)
  {
    $form = $this->createForm(RecipeType::class, $recipe);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      /** @var UploadedFlie $file */
      $file = $form->get('thumbnailFile')->getData();
      $fileName = $recipe->getId() . '.' . $file->getClientOriginalExtension();
      $file->move($this->getParameter('kernel.project_dir') . '/public/recettes/images', $fileName);
      $recipe->setThumbnail($fileName);
      $em->flush();
      $this->addFlash(
        'success',
        'La recette a bien été modifié'
      );
      return $this->redirectToRoute('admin.recipe.index');
    }
    return $this->render('admin/recipe/edit.html.twig', ['recipe' => $recipe, 'form' => $form]);
  }

  #[Route("/{id}", name: "delete", methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
  public function remove(Recipe $recipe, EntityManagerInterface $em)
  {
    $em->remove($recipe);
    $em->flush();
    $this->addFlash('success', 'La recette a bien été supprimée');
    return $this->redirectToRoute('admin.recipe.index');
  }
}
