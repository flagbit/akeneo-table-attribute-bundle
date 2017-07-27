define(function() {

    /**
     * @class
     */
    var JsonGeneratorStorage = function() {

        /**
         * @protected
         * @type {Object}
         */
        var $data = {};

        /**
         * @public
         * @param {Object} $_data
         */
        this.write = function($_data) {

            $data = $_data;
        };


        /**
         * @public
         * @returns {Object}
         */
        this.read = function() {

            return $data;
        };
    };

    return JsonGeneratorStorage;
});