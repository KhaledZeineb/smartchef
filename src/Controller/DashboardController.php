<?php

namespace App\Controller;

use App\Entity\UserIngredient;
use App\Repository\UserIngredientRepository;
use App\Service\GroqAIService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class DashboardController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('dashboard/index.html.twig');
    }

    #[Route('/dashboard', name: 'app_dashboard')]
    #[IsGranted('ROLE_USER')]
    public function dashboard(UserIngredientRepository $userIngredientRepository): Response
    {
        $user = $this->getUser();
        $userIngredients = $userIngredientRepository->findBy(['user' => $user]);

        return $this->render('dashboard/dashboard.html.twig', [
            'userIngredients' => $userIngredients,
        ]);
    }

   #[Route('/generate-recipe', name: 'app_generate_recipe', methods: ['POST'])]
#[IsGranted('ROLE_USER')]
public function generateRecipe(Request $request, UserIngredientRepository $userIngredientRepository, GroqAIService $groqService): Response
{
    $user = $this->getUser();
    $userIngredients = $userIngredientRepository->findBy(['user' => $user]);

    if (count($userIngredients) === 0) {
        $this->addFlash('error', 'Vous n\'avez pas d\'ingrédients dans votre frigo.');
        return $this->redirectToRoute('app_dashboard');
    }

    $recipe = $groqService->generateRecipe($userIngredients);

    return $this->render('dashboard/recipe.html.twig', [
        'recipe' => $recipe,
        'userIngredients' => $userIngredients,
    ]);
}
}