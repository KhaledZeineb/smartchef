<?php

namespace App\Form;

use App\Entity\UserIngredient;
use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserIngredientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ingredient', EntityType::class, [
                'class' => Ingredient::class,
                'choice_label' => 'name',
                'label' => 'Ingrédient',
                'placeholder' => 'Choisissez un ingrédient',
                'required' => true,
                'choices' => $options['ingredient_choices'],
            ])
            ->add('quantity', NumberType::class, [
                'label' => 'Quantité',
                'required' => false,
                'html5' => true,
                'attr' => [
                    'min' => 0,
                    'step' => 'any',
                ],
            ])
            ->add('unit', ChoiceType::class, [
                'label' => 'Unité',
                'required' => false,
                'choices' => [
                    'grammes' => 'g',
                    'kilogrammes' => 'kg',
                    'litres' => 'l',
                    'millilitres' => 'ml',
                    'cuillères à soupe' => 'cuillère à soupe',
                    'cuillères à café' => 'cuillère à café',
                    'pièces' => 'pièce(s)',
                    'pincées' => 'pincée(s)',
                ],
                'placeholder' => 'Choisissez une unité',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserIngredient::class,
            'ingredient_choices' => [],
        ]);
        
        $resolver->setAllowedTypes('ingredient_choices', 'array');
    }
}