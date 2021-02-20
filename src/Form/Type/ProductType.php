<?php

namespace App\Form\Type;

use App\Entity\Product\Product;
use App\Service\Receipt\VatCalculator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('barcode')
            ->add('name')
            ->add('price')
            ->add('freeItemFor')
            ->add(
                'vatClass',
                ChoiceType::class,
                [
                    'choices' => VatCalculator::VAT_CLASSES
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
