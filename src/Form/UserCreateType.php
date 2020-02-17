<?php

declare(strict_types=1);

namespace App\Form;

use App\Message\UserCreateMessage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserCreateType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname',TextType::class, [
                'constraints' => new NotBlank(['message' => 'Firstname parameter value should not be blank.'])
            ])
            ->add('lastname', TextType::class)
            ->add('nickname', TextType::class)
            ->add('password', TextType::class)
            ->add('age', TextType::class);
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserCreateMessage::class,
            'csrf_protection' => false
        ]);
    }
}