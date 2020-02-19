<?php

declare(strict_types=1);

namespace App\Form;

use App\Validator\UserNicknameUniqueness;
use App\ValueObject\User;
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
            ->add('lastname', TextType::class, [
                'constraints' => new NotBlank(['message' => 'Lastname parameter value should not be blank.'])
            ])
            ->add('nickname', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Nickname parameter value should not be blank.']),
                    new UserNicknameUniqueness()
                ]
            ])
            ->add('password', TextType::class, [
                'constraints' => new NotBlank(['message' => 'Password parameter value should not be blank.'])
            ])
            ->add('age', TextType::class, [
                'constraints' => new NotBlank(['message' => 'Age parameter value should not be blank.'])
            ]);
    }

    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection' => false
        ]);
    }
}