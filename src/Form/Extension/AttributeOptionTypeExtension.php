<?php

namespace Flagbit\Bundle\TableAttributeBundle\Form\Extension;

use Pim\Bundle\EnrichBundle\Form\Type\AttributeOptionType;
use Pim\Bundle\FilterBundle\Form\Type\UnstructuredType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Choice;

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
        $builder->add('type', TextType::class, [
            'required' => true,
            'constraints' => [
                new Choice(['select', 'select_from_url', 'text', 'number']),
            ],
        ]);
        $builder->add('constraints', UnstructuredType::class, ['required' => true]);
        $builder->add('type_config', UnstructuredType::class, ['required' => true]);
    }

    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return AttributeOptionType::class;
    }
}
