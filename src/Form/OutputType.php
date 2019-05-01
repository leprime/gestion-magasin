<?php

namespace App\Form;

use App\Entity\Output;
use App\Entity\Product;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OutputType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('produit', EntityType::class, [
                'class' => Product::class,
                'placeholder' => 'Choisir le produit',
                'choice_label' => 'name',
                'query_builder' => function(EntityRepository $er){
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC')
                        ->where('p.isExitPermit = :isPermit')
                        ->setParameter('isPermit', 1);
                },
                'mapped' => true,
                'required' => true
            ])
            ->add('quantity')
//            ->add('outputed_at', DateTimeType::class, [
//                'empty_data' => ''
//            ])
            ->add('observation')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Output::class,
            'csrf_protection' => false,
        ]);
    }
}
