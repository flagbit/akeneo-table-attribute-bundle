<?php

namespace Flagbit\Bundle\TableAttributeBundle\Controller;

use Akeneo\Pim\Structure\Component\Model\AttributeOption;
use Akeneo\Platform\Bundle\UIBundle\Controller\AjaxOptionController as BaseAjaxOptionController;
use Flagbit\Bundle\TableAttributeBundle\Entity\AttributeOption as TableAttributeOption;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AjaxOptionController
{
    private BaseAjaxOptionController $baseController;

    public function __construct(BaseAjaxOptionController $baseController)
    {
        $this->baseController = $baseController;
    }

    /**
     * Because of another hardcoded PHP class name in
     *
     * @see vendor/akeneo/pim-enterprise-dev/src/Akeneo/Pim/Automation/RuleEngine/front/src/fetch/AttributeOptionFetcher.ts
     *
     * the AttributeOption class can and should not be overridden in Akeneo PIM, we came up with this workaround to
     * support the legacy code of the Table attribute.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function listAction(Request $request)
    {
        $class = $request->query->get('class');
        if ($class === AttributeOption::class) {
            $request->query->set('class', TableAttributeOption::class);
        }

        return $this->baseController->listAction($request);
    }
}
