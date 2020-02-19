<?php

declare(strict_types=1);

namespace App\Form;

use App\ValueObject\TrackingData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
            ->add('user_id', TextType::class, [
                'constraints' => new NotBlank([
                    'message' => 'Unknown user, to identify user USER-AGENT-TOKEN or X-AUTH-TOKEN header is needed'
                ])
            ]);

        // Manually set userId form TrackingData object before form submit
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($options) {
            /** @var TrackingData $trackingData*/
            $trackingData = $options['data'];

            $formData = $event->getData();
            $formData['user_id'] = $trackingData->getUserId();

            $event->setData($formData);
        });
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