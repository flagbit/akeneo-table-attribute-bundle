<?php

namespace spec\Flagbit\Bundle\TableAttributeBundle\Normalizer;

use Flagbit\Bundle\TableAttributeBundle\Entity\AttributeOption;
use Flagbit\Bundle\TableAttributeBundle\Normalizer\AttributeOptionNormalizer;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AttributeOptionNormalizerSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType(AttributeOptionNormalizer::class);
    }

    public function let(NormalizerInterface $baseNormalizer)
    {
        $this->beConstructedWith($baseNormalizer);

    }

    public function it_checks_return_type_array_and_right_values
    (
        AttributeOption $attributeOption,
        $baseNormalizer
    ){
        $constraints = [
            'NotBlank' => [],
            'Email' => [],
        ];

        $activatedLocales = [
            'onlyActivatedLocales' => true,
        ];

        $baseNormalizer->normalize($attributeOption, 'array', $activatedLocales)->shouldBeCalled();
        $attributeOption->getType()->willReturn('text');
        $attributeOption->getTypeConfig()->willReturn([]);
        $attributeOption->getConstraints()->willReturn($constraints);

        $normalizedValues = $this->normalize($attributeOption, 'array', $activatedLocales);

        $normalizedValues->shouldBeArray();

        $normalizedValues->shouldHaveKey('type');
        $normalizedValues->shouldHaveKey('type_config');
        $normalizedValues->shouldHaveKey('constraints');

        $normalizedValues->shouldHaveKeyWithValue('type', 'text');
        $normalizedValues->shouldHaveKeyWithValue('type_config', []);
        $normalizedValues->shouldHaveKeyWithValue('constraints', $constraints);
    }
}
