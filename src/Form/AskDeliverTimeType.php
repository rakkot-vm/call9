<?php

declare(strict_types=1);

namespace App\Form;

use App\Controller\Dto\AskDeliverTime;
use App\Repository\ProviderRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Intl\Countries;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AskDeliverTimeType extends AbstractType
{
    public function __construct(
        private ProviderRepository $providerRepository,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('initDate', DateType::class, [
                'label' => 'Init Date',
                'widget' => 'single_text',
                'required' => false,
            ])
            ->add('provider', ChoiceType::class, [
                'label' => 'Delivery Service',
                'required' => false,
                'choices' => $this->getDeliveryProviders(),
            ])
            ->add('shippingAddress', ChoiceType::class, [
                'label' => 'Shipping address (Country)',
                'choices' => $this->countriesChoicesResolve(),
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'min' => 2,
                        'max' => 3,
                    ]),
                ],
            ])
            ->add('send', SubmitType::class, [
                'attr' => ['class' => 'save'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AskDeliverTime::class,
            'allow_extra_fields' => false,
        ]);
    }

    private function getDeliveryProviders(): array
    {
        $providers = $this->providerRepository->findAll();
        $providerList = [];

        foreach($providers as $provider){
            $providerList[$provider->getName()] = $provider->getId();
        }

        return $providerList;
    }

    private function countriesChoicesResolve(): array
    {
        return array_merge(
            array_flip(Countries::getNames()),
            ['European union' => 'EU']
        );
    }
}
