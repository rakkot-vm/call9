<?php

declare(strict_types=1);

namespace App\Controller;

use App\Controller\Dto\AskDeliverTime;
use App\Form\AskDeliverTimeType;
use App\Service\DeliverTime\DeliverTimeCalc;
use App\Service\Exception\CountryNotSupportedForShipping;
use App\Service\Exception\ProviderNotFound;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DeliverController extends AbstractController
{
    public function __construct(
        private DeliverTimeCalc $deliverTimeCalcService
    ) {
    }

    #[Route('/deliver-time', name: 'app_deliver_time')]
    public function time(Request $request): Response
    {
        $dto = new AskDeliverTime();

        $form = $this->createForm(AskDeliverTimeType::class, $dto);
        $form->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->render('deliver/index.html.twig', [
                'form' => $form,
            ]);
        }

        try {
            $deliverInDays = $this->deliverTimeCalcService->calculate($dto);

            return $this->render('deliver/index.html.twig', [
                'form' => $form,
                'deliverInDays' => $deliverInDays,
            ]);
        } catch (ProviderNotFound|CountryNotSupportedForShipping $e) {
            $errorForm = $form->addError(new FormError($e->getMessage()));
        } catch (\Exception $e) {
            $errorForm = $form->addError(new FormError('Internal error'));
        }

        return $this->render('deliver/index.html.twig', [
            'form' => $errorForm,
        ]);
    }
}
