<?php

namespace Flagbit\Bundle\TableAttributeBundle\Form\Extension;

use Pim\Bundle\EnrichBundle\Form\Type\AttributeOptionType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Extension for option attribute form
 *
 * @author    Ruben Beglaryan <ruben.beglaryan@flagbit.de>
 */
class AttributeOptionTypeExtension extends AbstractTypeExtension
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('constraints', 'text', ['required' => true]);
        $builder->add('type_config', 'text', ['required' => true]);
    }

    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return 'pim_enrich_attribute_option';
    }
}
