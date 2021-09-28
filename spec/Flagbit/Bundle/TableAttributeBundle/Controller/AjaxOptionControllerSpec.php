<?php

namespace spec\Flagbit\Bundle\TableAttributeBundle\Controller;

use Akeneo\Pim\Structure\Component\Model\AttributeOption;
use Akeneo\Platform\Bundle\UIBundle\Controller\AjaxOptionController;
use EmptyIterator;
use Flagbit\Bundle\TableAttributeBundle\Entity\AttributeOption as TableAttributeOption;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class AjaxOptionControllerSpec extends ObjectBehavior
{
    public function let(AjaxOptionController $controller)
    {
        $this->beConstructedWith($controller);
    }

    public function it_transforms_attribute_option_class(
        Request $request,
        ParameterBag $bag,
        AjaxOptionController $controller,
        JsonResponse $response
    ): void {
        $bag->get('class')->willReturn(AttributeOption::class);
        $bag->set('class', TableAttributeOption::class)->shouldBeCalledOnce();
        $request->query = $bag;

        $controller->listAction($request)->shouldBeCalledOnce()->willReturn($response);

        $this->listAction($request)->shouldReturn($response);
    }

    public function it_keeps_other_classes(
        Request $request,
        ParameterBag $bag,
        AjaxOptionController $controller,
        JsonResponse $response
    ): void {
        $bag->get('class')->willReturn(EmptyIterator::class);
        $bag->set('class', TableAttributeOption::class)->shouldNotBeCalled();
        $request->query = $bag;

        $controller->listAction($request)->shouldBeCalledOnce()->willReturn($response);

        $this->listAction($request)->shouldReturn($response);
    }
}
