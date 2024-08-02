<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\UX\Dropzone\Form\DropzoneType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'Nom du produit',
                    'required' => false
                ],
            )
            // ->add('slug')
            ->add(
                'description',
                TextareaType::class,
                [
                    'label' => 'description du produit',
                    'required' => false
                ],
            )
            ->add('imageFile', DropzoneType::class, [
                'label' => 'Ajouter une image',
                'required' => false,
            ])
            ->add(
                'price',
                NumberType::class,
                [
                    'label' => 'Prix',
                    'required' => false
                ],
            )
            ->add(
                'stock',
                IntegerType::class,
                [
                    'label' => 'stock',
                    'required' => false
                ],
            )
            // ->add('createdAt', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('updatedAt', null, [
            //     'widget' => 'single_text',
            // ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
