<?php

namespace App\Form;

use App\Entity\Pokemon;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

class PokemonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Naam',
                'constraints' => [new NotBlank(message: 'Vul een naam in.')],
            ])
            ->add('cardValue', NumberType::class, [
                'label' => 'Waarde',
                'input' => 'string',
                'scale' => 2,
                'html5' => true,
                'attr' => ['min' => 0, 'step' => '0.01'],
                'constraints' => [
                    new NotBlank(message: 'Vul een waarde in.'),
                    new GreaterThanOrEqual(value: 0, message: 'De waarde mag niet negatief zijn.'),
                ],
            ])
            ->add('image', TextType::class, [
                'label' => 'Afbeelding',
                'help' => 'Gebruik de bestandsnaam uit assets/images, bijvoorbeeld pikachu.jpg.',
                'constraints' => [new NotBlank(message: 'Vul een bestandsnaam in.')],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Beschrijving',
                'required' => false,
                'attr' => ['rows' => 4],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pokemon::class,
        ]);
    }
}
