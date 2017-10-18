define(
    [
        'flagbit/JsonGenerator/Observer',
        'oro/translator',
    ],
    function(JsonGeneratorObserver, __) {

        /**
         * @class
         */
        var JsonGeneratorRendererDefault = function($editable, $container) {

            /**
             * @public
             * @type {JsonGeneratorObserver}
             */
            this.observer = new JsonGeneratorObserver();

            /**
             * @public
             * @param {Object} $data
             */
            this.render = function($data) {
                var $text = document.createElement('span');
                $text.innerText = __('flagbit_attribute_table_no_configuration_text');
                $container.appendChild($text);
            };

            /**
             * @public
             * @returns {Object}
             */
            this.read = function() {
                return {};
            };

        };

        return JsonGeneratorRendererDefault;
    }
);
