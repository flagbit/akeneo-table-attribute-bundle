<?php

namespace Flagbit\Bundle\TableAttributeBundle\Form\Extension;

use Akeneo\Pim\Structure\Bundle\Form\Type\AttributeOptionType;
use Flagbit\Bundle\TableAttributeBundle\Form\TableJsonTransformer;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Choice;

/**
 * Extension for option attribute form
 */
class AttributeOptionTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'type',
            TextType::class,
            [
            'required' => true,
            'constraints' => [
                new Choice(['select', 'select_from_url', 'text', 'number']),
            ],
            ]
        );
        $builder->add('constraints', TextType::class, ['required' => true]);
        $builder->add('type_config', TextType::class, ['required' => true]);

        $transformer = new TableJsonTransformer();

        $builder->get('constraints')->addModelTransformer($transformer);
        $builder->get('type_config')->addModelTransformer($transformer);
    }

    /**
     * Returns the name of the type being extended.
     *
     * @return array<string> The names of the types being extended
     */
    public static function getExtendedTypes(): iterable
    {
        return [
            AttributeOptionType::class
        ];
    }
}
