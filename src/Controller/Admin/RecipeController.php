<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\CategoryRepository;
use App\Repository\RecipeRepository;
use App\Security\Voter\RecipeVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Symfony\UX\Turbo\TurboBundle;
use App\Message\RecipePDFMessage;



#[Route('/admin/recettes', name: 'admin.recipe.')]
// #[IsGranted('ROLE_ADMIN')]
class RecipeController extends AbstractController
{


  #[Route('/', name: 'index')]
  #[IsGranted(RecipeVoter::LIST)]
  public function index(RecipeRepository $repository, Request $request, Security $security): Response // ou + EntityManagerInterface $em
  {
    // $this->denyAccessUnlessGranted('ROLE_USER'); ce n'est pas la solution la plus optimale
    // $platPrincipal = $categoryRepository->findOneBy(['slug' => 'plat-principal']);
    // $pates = $repository->findOneBy(['slug' => 'pates-bolognaise']);
    // $pates->setCategory($platPrincipal);
    // $entityManager->flush();
    $page = $request->query->getInt('page', 1);
    $userId = $security->getUser()->getId();
    $canListAll = $security->isGranted(RecipeVoter::LIST_ALL);
    $recipes = $repository->paginateRecipes($page, $canListAll ? null : $userId);
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
  #[IsGranted(RecipeVoter::CREATE)]
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
  #[IsGranted(RecipeVoter::EDIT, subject: 'recipe')]
  public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em, UploaderHelper $helper, MessageBusInterface $messageBus)
  {
    $form = $this->createForm(RecipeType::class, $recipe);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      // /** @var UploadedFlie $file */
      // $file = $form->get('thumbnailFile')->getData();
      // $fileName = $recipe->getId() . '.' . $file->getClientOriginalExtension();
      // $file->move($this->getParameter('kernel.project_dir') . '/public/recettes/images', $fileName);
      // $recipe->setThumbnail($fileName);
      $em->flush();
      $messageBus->dispatch(new RecipePDFMessage($recipe->getId()));
      $this->addFlash('success', 'La recette a bien été modifiée');
      return $this->redirectToRoute('admin.recipe.index');
    }
    return $this->render('admin/recipe/edit.html.twig', ['recipe' => $recipe, 'form' => $form]);
  }

  #[Route("/{id}", name: "delete", methods: ['DELETE'], requirements: ['id' => Requirement::DIGITS])]
  #[IsGranted(RecipeVoter::EDIT, subject: 'recipe')]
  public function remove(Request $request, Recipe $recipe, EntityManagerInterface $em)
  {
    $recipeId = $recipe->getId();
    $message = 'La recette a bien été supprimée';
    $em->remove($recipe);
    $em->flush();
    if ($request->getPreferredFormat() === TurboBundle::STREAM_FORMAT) {
      $request->setRequestFormat(TurboBundle::STREAM_FORMAT);
      return $this->render('admin/recipe/delete.html.twig', ['recipeId' => $recipeId, 'message' => $message]);
    }

    $this->addFlash('success', $message);
    return $this->redirectToRoute('admin.recipe.index');
  }
}
