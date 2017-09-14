define(
    [
        'flagbit/JsonGenerator/Storage',
        'flagbit/JsonGenerator/Input',
        'flagbit/JsonGenerator/Renderer'
    ],
    function(JsonGeneratorStorage, JsonGeneratorInput, JsonGeneratorRenderer) {

        /**
         * @class
         * @param {(HTMLElement|HTMLTextAreaElement)} $element
         */
        var JsonGenerator = function($element, $types) {

            var $source = new JsonGeneratorStorage();
            var $input = new JsonGeneratorInput($element);
            var $renderer = new JsonGeneratorRenderer($input.isEditable(), $element.parentNode, $types);

            $input.observer.watch('load', function() {
                $source.write($input.read());
                $renderer.render($source.read());
            });

            $renderer.observer.watch('persist', function() {
                $source.write($renderer.read());
            });

            $renderer.observer.watch('save', function() {
                $input.write($source.read());
            });

            $input.hide();
            $input.observer.notify('load');
        };

        return JsonGenerator;
    }
);