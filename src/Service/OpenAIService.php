<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use App\Entity\UserIngredient;

class OpenAIService
{
    private HttpClientInterface $httpClient;
    private string $apiKey;

    public function __construct(HttpClientInterface $httpClient, string $apiKey)
    {
        $this->httpClient = $httpClient;
        $this->apiKey = $apiKey;
    }

    public function generateRecipe(array $userIngredients): string
    {
        // 🧾 Préparer la liste des ingrédients en texte
        $ingredientsList = '';
        foreach ($userIngredients as $userIngredient) {
            $ingredientsList .= $userIngredient->getQuantity() . ' ' . 
                                $userIngredient->getUnit() . ' ' . 
                                $userIngredient->getIngredient()->getName() . ', ';
        }

        // 🗣️ Formuler le prompt pour OpenAI
        $prompt = "Je dispose des ingrédients suivants: $ingredientsList. 
        Suggère-moi une recette simple en utilisant ces ingrédients ou une partie d'entre eux.
        Présente la recette avec :
        1. Un titre attrayant
        2. La liste des ingrédients nécessaires avec les quantités
        3. Les étapes détaillées de préparation";

        // 📡 Appel API OpenAI avec gestion des erreurs
        try {
            $response = $this->httpClient->request('POST', 'https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo',
                    'messages' => [
                        ['role' => 'user', 'content' => $prompt]
                    ],
                    'temperature' => 0.7,
                ],
            ]);

            $data = $response->toArray();
            return $data['choices'][0]['message']['content'] ?? 'Aucune réponse trouvée. Veuillez réessayer.';

        } catch (ClientExceptionInterface $e) {
            if (method_exists($e, 'getCode') && $e->getCode() === 429) {
                sleep(10); // 💤 Pause pour laisser respirer l’API
                return '⚠️ Trop de requêtes envoyées à OpenAI. Veuillez réessayer dans quelques instants.';

            }
            return '❌ Erreur client OpenAI : ' . $e->getMessage();
        } catch (TransportExceptionInterface $e) {
            return '🚫 Erreur de transport lors de la requête OpenAI : ' . $e->getMessage();
        }
    }
}
