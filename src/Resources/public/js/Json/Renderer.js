define(
    [
        'flagbit/JsonGenerator/Observer',
        'flagbit/JsonGenerator/Renderer/Number',
        'flagbit/JsonGenerator/Renderer/Select',
        'flagbit/JsonGenerator/Renderer/Constraint',
        'flagbit/JsonGenerator/Renderer/Default'
    ],
    function(JsonGeneratorObserver, JsonGeneratorRendererNumber, JsonGeneratorRendererSelect, JsonGeneratorRendererConstraint, JsonGeneratorRendererDefault) {

    /**
     * @class
     * @param {Boolean} $editable
     * @param {HTMLElement} $container
     */
    var JsonGeneratorRenderer = function($editable, $container) {

        /**
         * @public
         * @type {JsonGeneratorObserver}
         */
        this.observer = new JsonGeneratorObserver();

        var $renderer = null;


        /**
         * @public
         */
        this.render = function($data) {

            return getRenderer().render($data);
        };


        /**
         * @public
         * @returns {Object}
         */
        this.read = function() {

            return getRenderer().read();
        };


        /**
         * @protected
         * @returns {*}
         */
        var getRenderer = function() {

            if($renderer === null) {
                if($container.querySelector('.json-select-generator')) {
                    $renderer = new JsonGeneratorRendererSelect($editable, $container);
                }
                else if($container.querySelector('.json-number-generator')) {
                    $renderer = new JsonGeneratorRendererNumber($editable, $container);
                }
                else if($container.querySelector('.json-constraint-generator')) {
                    $renderer = new JsonGeneratorRendererConstraint($editable, $container);
                }
                else {
                    $renderer = new JsonGeneratorRendererDefault($editable, $container);
                }
            }

            return $renderer;
        }.bind(this);


        /**
         * @protected
         */
        var persist = function() {

            this.observer.notify('persist');
        }.bind(this);


        /**
         * @protected
         */
        var save = function() {

            this.observer.notify('save');
        }.bind(this);


        /**
         * @protected
         */
        var addObserver = function() {

            getRenderer().observer.watch('update', persist);
            getRenderer().observer.watch('update', callDebounce(save));
        }.bind(this);


        /**
         * @protected
         * @param {Function} $callable
         */
        var callDebounce = function($callable) {

            var $debounceTimer = null;

            return function() {

                if($debounceTimer) {
                    window.clearTimeout($debounceTimer);
                }

                $debounceTimer = window.setTimeout($callable, 300);
            }.bind(this)
        }.bind(this);


        addObserver();
    };

    return JsonGeneratorRenderer;
});