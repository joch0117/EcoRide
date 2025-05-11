<?php

namespace App\Twig;

use App\Form\MiniSearchTripType;
use Symfony\Component\Form\FormFactoryInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class MiniSearchFormExtension extends AbstractExtension implements GlobalsInterface
{
    private FormFactoryInterface $formFactory;

    public function __construct(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function getGlobals(): array
    {
        $form = $this->formFactory->create(MiniSearchTripType::class,null,[
            'method'=>'GET',
            'csrf_protection'=>false
        ]);

        return [
            'miniSearchForm' => $form->createView(),
        ];
    }
}
