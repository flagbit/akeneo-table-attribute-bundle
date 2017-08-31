<?php
namespace Flagbit\Bundle\TableAttributeBundle\Twig;


use Pim\Bundle\EnrichBundle\Twig\AttributeExtension as BaseAttributeExtension;
use Pim\Component\Catalog\Repository\AttributeRepositoryInterface;

class AttributeExtension extends BaseAttributeExtension {
    /**
     * @param AttributeRepositoryInterface $repository
     * @param array                        $communityIcons
     * @param array                        $eeIcons
     */
    public function __construct(AttributeRepositoryInterface $repository, array $communityIcons, array $eeIcons, array $ftIcons)
    {
        $this->repository = $repository;

        parent::__construct(array_merge($communityIcons, $eeIcons, $ftIcons), $repository);
    }
}