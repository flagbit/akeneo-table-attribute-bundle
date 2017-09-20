define(
    [
        'flagbit/JsonGenerator/Observer'
    ],
    function(JsonGeneratorObserver) {

        /**
         * @class
         */
        var JsonGeneratorRendererNumber = function($editable, $container) {

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

                if(!$data['is_decimal']) {
                    $data['is_decimal'] = 'false';
                }

                var $value = $data['is_decimal'];

                var $label = document.createElement('label');
                $label.innerText = 'is_decimal';
                $container.appendChild($label);

                var $dropdown = createDropdown('is_decimal');

                var $options = {
                    'true': 'true',
                    'false': 'false'
                };

                for(var $i in $options) {
                    if($options.hasOwnProperty($i)) {
                        var $option = document.createElement('option');
                        $option.value = $i;
                        $option.innerText = $options[$i];
                        $dropdown.appendChild($option);
                    }
                }

                $dropdown.value = $value;

            };


            /**
             * @public
             * @returns {Object}
             */
            this.read = function() {

                var $data = {};

                var $collection = $container.querySelectorAll('select');
                for(var $i in $collection) {
                    if($collection.hasOwnProperty($i)) {
                        var $dropdown = $collection[$i];
                        $data[$dropdown.name] = $dropdown.value === 'true';
                    }
                }

                return $data;
            };


            /**
             * @protected
             * @param {String} $name
             * @return {HTMLSelectElement}
             */
            var createDropdown = function($name) {

                var $dropdown = document.createElement('select');
                $dropdown.name = $name;
                $dropdown.style.display = 'block';
                $container.appendChild($dropdown);

                observeChanges($dropdown);

                if(!$editable) {
                    $dropdown.disabled = true;
                }

                return $dropdown;
            }.bind(this);


            /**
             * @protected
             * @param {HTMLSelectElement} $dropdown
             */
            var observeChanges = function($dropdown) {
                $dropdown.addEventListener('change', notify);
            }.bind(this);


            /**
             * @protected
             */
            var notify = function() {

                this.observer.notify('update');
            }.bind(this);
        };

        return JsonGeneratorRendererNumber;
    }
);