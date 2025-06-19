<?php

namespace App\Twig;

use App\Form\MiniSearchTripType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class MiniSearchFormExtension extends AbstractExtension implements GlobalsInterface
{
    private FormFactoryInterface $formFactory;

    //formulaire de recheche présent dans le menu
    public function __construct(FormFactoryInterface $formFactory,private RouterInterface $router)
    {
        $this->formFactory = $formFactory;
    }

    public function getGlobals(): array
    {
        $form = $this->formFactory->create(MiniSearchTripType::class,null,[
            'method'=>'GET',
            'action'=>$this->router->generate('app_trip_search'),
            'csrf_protection'=>false
        ]);

        return [
            'miniSearchForm' => $form->createView(),
        ];
    }
}
