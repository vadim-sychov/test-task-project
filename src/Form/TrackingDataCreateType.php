<?php

declare(strict_types=1);

namespace App\Form;

use App\ValueObject\TrackingData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class TrackingDataCreateType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('source_label',TextType::class, [
                'constraints' => new NotBlank(['message' => 'Source_label parameter value should not be blank.'])
            ])
            ->add('user_id', TextType::class);
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TrackingData::class,
            'csrf_protection' => false
        ]);
    }
}