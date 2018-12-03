<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Titre de l\'annonce !'
                ]
            ])
            ->add('slug',  TextType::class, [
                'label' => 'URL',
                'attr' => [
                    'placeholder' => 'Adresse Web (automatique)!'
                ]
            ])
            ->add('introduction',  TextType::class, [
                'label' => 'Introduction',
                'attr' => [
                    'placeholder' => 'Donnez une description globalede l\'annonce!'
                ]
            ])
            ->add('content',  TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Description détaillée !'
                ]
            ])
            ->add('rooms',  TextType::class, [
                'label' => 'Nombre de chambres',
                'attr' => [
                    'placeholder' => 'Donner le nombre des chambres disponible !'
                ]
            ])
            ->add('price',  MoneyType::class, [
                'label' => 'Prix par nuit',
                'attr' => [
                    'placeholder' => 'Prix que vous voulez par une nuit !'
                ]
            ])
            ->add('coverImage',  UrlType::class, [
                'label' => 'URL de l\'image',
                'attr' => [
                    'placeholder' => 'Donnez l\'url de l\'image de votre annonce!'
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
