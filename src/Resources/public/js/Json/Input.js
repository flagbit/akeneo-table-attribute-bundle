define(
    [
        'flagbit/JsonGenerator/Observer'
    ],
    function(JsonGeneratorObserver) {

    /**
     * @class
     * @param {(HTMLElement|HTMLTextAreaElement)} $element
     */
    var JsonGeneratorInput = function($element) {

        /**
         * @public
         * @type {JsonGeneratorObserver}
         */
        this.observer = new JsonGeneratorObserver();


        /**
         * @public
         * @param {Object} $data
         */
        this.write = function($data) {

            $element.value = JSON.stringify($data);

            this.observer.notify('write');
        };


        /**
         * @public
         * @returns {Object}
         */
        this.read = function() {
            var $data = {};

            if(this.isEditable()) {
                $data = JSON.parse($element.value);
            }
            else {
                $data = JSON.parse($element.innerText);
            }

            return $data;
        };


        /**
         * @public
         * @returns {Boolean}
         */
        this.isEditable = function() {

            return $element instanceof HTMLTextAreaElement || ($element instanceof HTMLInputElement && $element.type.toLowerCase() === 'text');
        };


        /**
         * @public
         */
        this.hide = function() {
            $element.style.display = 'none';
        };


        /**
         * @public
         */
        this.show = function() {
            $element.style.display = '';
        };
    };

    return JsonGeneratorInput;
});