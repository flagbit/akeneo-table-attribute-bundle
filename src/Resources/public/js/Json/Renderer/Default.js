define(
    [
        'flagbit/JsonGenerator/Observer'
    ],
    function(JsonGeneratorObserver) {

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
                $text.innerText = 'There is no configuration option.';
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