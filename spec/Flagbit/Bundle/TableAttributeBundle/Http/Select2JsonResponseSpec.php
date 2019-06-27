<?php

namespace spec\Flagbit\Bundle\TableAttributeBundle\Http;

use Flagbit\Bundle\TableAttributeBundle\Http\Select2JsonResponse;
use PhpSpec\ObjectBehavior;

class Select2JsonResponseSpec extends ObjectBehavior
{
    public function let()
    {
        $array = [
            'a' => 1,
            'b' => 2,
            3 => 'c',
        ];
        $this->beConstructedWith($array);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Select2JsonResponse::class);
    }
    
    public function it_creates_select2_response_body()
    {
        $this->getContent()->shouldReturn('{"results":[{"id":"a","text":"1"},{"id":"b","text":"2"},{"id":"3","text":"c"}]}');
    }

    public function it_contains_json_response_header()
    {
        $this->headers->get('Content-Type')->shouldReturn('application/json');
    }
}
