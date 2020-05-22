<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('state', ChoiceType::class, [
                'choices'  => [
                    'Neuf' => 'neuf',
                    'Comme Neuf' => 'comme neuf',
                    'Très bon état' => 'tres bon etat',
                    'Bon état' => 'bon etat',
                    'Etat moyen' => 'etat moyen']])
            ->add('limitprice')
            ->add('price')
            ->add('save', SubmitType::class, ['label' => 'Valider']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
