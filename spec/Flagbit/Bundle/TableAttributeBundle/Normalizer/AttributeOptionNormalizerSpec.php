<?php

namespace spec\Flagbit\Bundle\TableAttributeBundle\Normalizer;

use Flagbit\Bundle\TableAttributeBundle\Entity\AttributeOption;
use Flagbit\Bundle\TableAttributeBundle\Normalizer\AttributeOptionNormalizer;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
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

    /**
     * @throws ExceptionInterface
     */
    public function it_checks_return_type_array_and_right_values(
        AttributeOption $attributeOption,
        $baseNormalizer
    ) {
        $constraints = [
            'NotBlank' => [],
            'Email' => [],
        ];

        $activatedLocales = [
            'onlyActivatedLocales' => true,
        ];

        $baseNormalizer->normalize($attributeOption, 'array', $activatedLocales)
            ->willReturn([])
            ->shouldBeCalled();
        $attributeOption->getType()->willReturn('text');
        $attributeOption->getTypeConfig()->willReturn([]);
        $attributeOption->getConstraints()->willReturn($constraints);
        $attributeOption->isTableAttribute()->willReturn(true);

        $normalizedValues = $this->normalize($attributeOption, 'array', $activatedLocales);

        $normalizedValues->shouldBeArray();

        $normalizedValues->shouldHaveKey('type');
        $normalizedValues->shouldHaveKey('type_config');
        $normalizedValues->shouldHaveKey('constraints');

        $normalizedValues->shouldHaveKeyWithValue('type', 'text');
        $normalizedValues->shouldHaveKeyWithValue('type_config', []);
        $normalizedValues->shouldHaveKeyWithValue('constraints', $constraints);
    }

    /**
     * @throws ExceptionInterface
     */
    public function it_checks_return_type_for_default_akeneo_attribute_options(
        AttributeOption $attributeOption,
        $baseNormalizer
    ) {
        $baseNormalizer->normalize($attributeOption, 'array', [])
            ->willReturn([])
            ->shouldBeCalled();
        $attributeOption->getType()->willReturn(null);
        $attributeOption->getTypeConfig()->willReturn([]);
        $attributeOption->getConstraints()->willReturn([]);
        $attributeOption->isTableAttribute()->willReturn(false);

        $normalizedValues = $this->normalize($attributeOption, 'array');

        $normalizedValues->shouldBeArray();

        $normalizedValues->shouldNotHaveKey('type');
        $normalizedValues->shouldNotHaveKey('type_config');
        $normalizedValues->shouldNotHaveKey('constraints');
    }
}
