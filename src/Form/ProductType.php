<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('code', TextType::class,[
                    'attr' => ['autofocus' => true],
                    'label' => 'Code article'
            ])
            ->add('name', TextType::class, [
                'label' => 'Libellé'
            ])
            ->add('mark', TextType::class, [
                'label' => 'Marque'
            ])
            ->add('price', null, [
                'label' => 'Prix untitaire'
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Quantité'
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'placeholder' => 'Sélectionner la catégorie',
                'choice_label' => 'name',
                'mapped' => false,
                'required' => true
            ])
            ->add('service')
            ->add('refe_order', TextType::class, [
                'label' => 'Ordre de référence'
            ])
            ->add('image_url')
            ->add('isExitPermit', ChoiceType::class, [
                'label' => 'Sortie permise ?',
                'choices' => ['Oui' => true, 'Non' => false],
            ])
            ->add('isLendable', ChoiceType::class, [
                'label' => 'Prêt permis ?',
                'choices' => ['Oui' => true, 'Non' => false]
            ])
            ->add('description', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
