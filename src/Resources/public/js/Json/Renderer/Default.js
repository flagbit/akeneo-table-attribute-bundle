define(
    [
        'flagbit/JsonGenerator/Observer',
        'oro/translator',
    ],
    function (JsonGeneratorObserver, __) {

        /**
         * @class
         */
        var JsonGeneratorRendererDefault = function ($editable, $container) {

            /**
             * @public
             * @type {JsonGeneratorObserver}
             */
            this.observer = new JsonGeneratorObserver();

            /**
             * @public
             * @param {Object} $data
             */
            this.render = function ($data) {
                var $text = document.createElement('span');
                $text.innerText = __('flagbit.table_attribute.no_configuration.text');
                $container.appendChild($text);
            };

            /**
             * @public
             * @returns {Object}
             */
            this.read = function () {
                return {};
            };

        };

        return JsonGeneratorRendererDefault;
    }
);
