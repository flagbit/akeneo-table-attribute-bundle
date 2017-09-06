define(
    [
        'flagbit/JsonGenerator/Observer'
    ],
    function(JsonGeneratorObserver) {

        /**
         * @class
         */
        var JsonGeneratorRendererText = function($editable, $container) {

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

            };

        };

        return JsonGeneratorRendererText;
    }
);