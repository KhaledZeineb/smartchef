<?php

namespace App\Command;

use App\Entity\Ingredient;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:init-ingredients',
    description: 'Initialise la base de données avec des ingrédients de base',
)]
class InitIngredientsCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $ingredients = [
            // Légumes
            'Tomate', 'Oignon', 'Ail', 'Carotte', 'Pomme de terre', 'Courgette', 'Aubergine',
            'Poivron', 'Champignon', 'Salade', 'Concombre', 'Haricot vert', 'Épinard', 'Chou',
            'Brocoli', 'Chou-fleur', 'Maïs', 'Petits pois', 'Asperge', 'Citrouille',
            
            // Fruits
            'Pomme', 'Banane', 'Orange', 'Citron', 'Fraise', 'Framboise', 'Myrtille',
            'Poire', 'Pêche', 'Abricot', 'Kiwi', 'Ananas', 'Melon', 'Pastèque',
            'Raisin', 'Cerise', 'Mangue', 'Avocat',
            
            // Viandes
            'Poulet', 'Bœuf', 'Porc', 'Agneau', 'Veau', 'Dinde', 'Canard',
            'Saucisse', 'Jambon', 'Bacon', 'Steak haché',
            
            // Poissons et fruits de mer
            'Saumon', 'Thon', 'Cabillaud', 'Crevette', 'Moule', 'Huître', 'Maquereau',
            'Sardine', 'Truite', 'Bar', 'Daurade',
            
            // Produits laitiers
            'Lait', 'Beurre', 'Crème fraîche', 'Yaourt', 'Fromage blanc', 'Parmesan',
            'Mozzarella', 'Comté', 'Camembert', 'Chèvre', 'Bleu', 'Roquefort',
            
            // Féculents et céréales
            'Riz', 'Pâtes', 'Farine', 'Pain', 'Semoule', 'Quinoa', 'Boulgour',
            'Lentilles', 'Pois chiches', 'Haricots rouges', 'Haricots blancs',
            
            // Œufs
            'Œuf',
            
            // Condiments et épices
            'Sel', 'Poivre', 'Huile d\'olive', 'Vinaigre', 'Moutarde', 'Ketchup', 'Mayonnaise',
            'Sauce soja', 'Curry', 'Paprika', 'Cumin', 'Cannelle', 'Basilic', 'Thym',
            'Romarin', 'Persil', 'Coriandre', 'Menthe', 'Ciboulette',
            
            // Sucré
            'Sucre', 'Miel', 'Confiture', 'Chocolat', 'Vanille',
            
            // Boissons
            'Café', 'Thé', 'Jus d\'orange', 'Vin', 'Bière',
        ];

        $countAdded = 0;
        $countSkipped = 0;

        foreach ($ingredients as $ingredientName) {
            // Vérifier si l'ingrédient existe déjà
            $existingIngredient = $this->entityManager->getRepository(Ingredient::class)->findOneBy(['name' => $ingredientName]);
            
            if (!$existingIngredient) {
                $ingredient = new Ingredient();
                $ingredient->setName($ingredientName);
                
                $this->entityManager->persist($ingredient);
                $countAdded++;
            } else {
                $countSkipped++;
            }
        }
        
        $this->entityManager->flush();

        $io->success("Initialisation des ingrédients terminée : $countAdded ajoutés, $countSkipped déjà existants.");

        return Command::SUCCESS;
    }
}