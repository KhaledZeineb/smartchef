<?php

namespace App\Controller;

use App\Entity\UserIngredient;
use App\Entity\Ingredient;
use App\Form\UserIngredientType;
use App\Repository\UserIngredientRepository;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/my-ingredients')]
#[IsGranted('ROLE_USER')]
class UserIngredientController extends AbstractController
{
    #[Route('/', name: 'app_user_ingredient_index', methods: ['GET'])]
    public function index(UserIngredientRepository $userIngredientRepository): Response
    {
        $user = $this->getUser();
        
        return $this->render('user_ingredient/index.html.twig', [
            'user_ingredients' => $userIngredientRepository->findBy(['user' => $user]),
        ]);
    }

    #[Route('/new', name: 'app_user_ingredient_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, IngredientRepository $ingredientRepository): Response
    {
        $userIngredient = new UserIngredient();
        $userIngredient->setUser($this->getUser());
        
        $form = $this->createForm(UserIngredientType::class, $userIngredient, [
            'ingredient_choices' => $ingredientRepository->findAll(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($userIngredient);
            $entityManager->flush();

            $this->addFlash('success', 'Ingrédient ajouté avec succès !');
            return $this->redirectToRoute('app_user_ingredient_index');
        }

        return $this->render('user_ingredient/new.html.twig', [
            'user_ingredient' => $userIngredient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_ingredient_show', methods: ['GET'])]
    public function show(UserIngredient $userIngredient): Response
    {
        // Vérifier que l'utilisateur est bien le propriétaire
        if ($userIngredient->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à voir cet ingrédient.');
        }
        
        return $this->render('user_ingredient/show.html.twig', [
            'user_ingredient' => $userIngredient,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_ingredient_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, UserIngredient $userIngredient, EntityManagerInterface $entityManager, IngredientRepository $ingredientRepository): Response
    {
        // Vérifier que l'utilisateur est bien le propriétaire
        if ($userIngredient->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier cet ingrédient.');
        }
        
        $form = $this->createForm(UserIngredientType::class, $userIngredient, [
            'ingredient_choices' => $ingredientRepository->findAll(),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Ingrédient mis à jour avec succès !');
            return $this->redirectToRoute('app_user_ingredient_index');
        }

        return $this->render('user_ingredient/edit.html.twig', [
            'user_ingredient' => $userIngredient,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_ingredient_delete', methods: ['POST'])]
    public function delete(Request $request, UserIngredient $userIngredient, EntityManagerInterface $entityManager): Response
    {
        // Vérifier que l'utilisateur est bien le propriétaire
        if ($userIngredient->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à supprimer cet ingrédient.');
        }
        
        if ($this->isCsrfTokenValid('delete'.$userIngredient->getId(), $request->request->get('_token'))) {
            $entityManager->remove($userIngredient);
            $entityManager->flush();
            $this->addFlash('success', 'Ingrédient supprimé avec succès !');
        }

        return $this->redirectToRoute('app_user_ingredient_index');
    }
}