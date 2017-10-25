define(
    [
        'flagbit/JsonGenerator/Observer',
        'oro/translator',
    ],
    function(JsonGeneratorObserver, __) {

        /**
         * @class
         * @param {Boolean} $editable
         * @param {HTMLElement} $container
         */
        var JsonGeneratorRendererSelectFromUrl = function($editable, $container) {

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

                var $label = document.createElement('label');
                $label.innerText = __('flagbit_attribute_table_simpleselect_options_url_label');
                var $input = document.createElement('input');
                $input.type = 'text';
                $input.className = 'AknTextField';
                $input.name = 'options_url';
                $input.value = $data['options_url'] ? $data['options_url'] : '';
                if(!$editable) {
                    $input.disabled = true;
                }
                observeChanges($input);

                $container.appendChild($label);
                $container.appendChild($input);
            };


            /**
             * @public
             * @returns {Object}
             */
            this.read = function() {

                var $data = {};

                $data['options_url'] = $container.querySelector('input[name="options_url"]').value;

                return $data;
            };

            /**
             * @protected
             * @param {HTMLInputElement} $input
             */
            var observeChanges = function($input) {
                $input.addEventListener('keyup', notify);
                $input.addEventListener('blur', notify);
            }.bind(this);


            /**
             * @protected
             */
            var notify = function() {

                this.observer.notify('update');
            }.bind(this);
        };

        return JsonGeneratorRendererSelectFromUrl;
    }
);
