define(
    [
        'flagbit/JsonGenerator/Observer',
        'jquery'
    ],
    function(JsonGeneratorObserver, jQuery) {

        /**
         * @class
         */
        var JsonGeneratorRendererConstraint = function($editable, $container) {

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

                var $options = ['NotBlank', 'Blank', 'NotNull', 'IsNull', 'IsTrue', 'IsFalse', 'Email', 'Url', 'Ip', 'Uuid', 'Data', 'DateTime', 'Time', 'Language', 'Locale', 'Country', 'Bic', 'CardScheme', 'Currency', 'Luhn', 'Iban', 'Isbn', 'Issn'];

                var $dropdown = createDropdown();

                $options.forEach(function($value) {

                    var $option = document.createElement('option');
                    $option.value = $value;
                    $option.innerText = $value;

                    if($value in $data) {
                        $option.selected = true;
                    }

                    $dropdown.appendChild($option);
                });

                var $select2 = jQuery($dropdown).select2();

                observeChanges($select2);
            };


            /**
             * @public
             * @returns {Object}
             */
            this.read = function() {

                var $data = {};

                var $collection = $container.querySelector('select').querySelectorAll('option');

                for(var $i in $collection) {
                    if($collection.hasOwnProperty($i)) {
                        var $option = $collection[$i];
                        if($option.selected) {
                            $data[$option.value] = {};
                        }
                    }
                }

                return $data;
            };


            /**
             * @protected
             * @param {String} $name
             * @return {HTMLSelectElement}
             */
            var createDropdown = function() {

                var $dropdown = document.createElement('select');
                $dropdown.style.display = 'block';
                $dropdown.multiple = true;
                $container.appendChild($dropdown);

                if(!$editable) {
                    $dropdown.disabled = true;
                }

                return $dropdown;
            }.bind(this);


            /**
             * @protected
             * @param {HTMLSelectElement} $dropdown
             */
            var observeChanges = function($select) {
                $select.on('change', notify);
            }.bind(this);


            /**
             * @protected
             */
            var notify = function() {

                this.observer.notify('update');
            }.bind(this);
        };

        return JsonGeneratorRendererConstraint;
    }
);