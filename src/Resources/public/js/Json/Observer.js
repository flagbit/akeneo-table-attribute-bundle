define(function () {

    /**
     * @class
     */
    var JsonGeneratorObserver = function () {

        /**
         * @protected
         * @type {Object}
         */
        var $watcher = {};


        /**
         * @public
         * @param {String} $action
         * @param {Function} $callable
         */
        this.watch = function ($action, $callable) {

            if (!$watcher[$action]) {
                $watcher[$action] = [];
            }

            $watcher[$action].push($callable);
        };


        /**
         * @public
         * @param {String} $action
         */
        this.notify = function ($action) {
            if ($watcher[$action]) {
                for (var $i in $watcher[$action]) {
                    if ($watcher[$action].hasOwnProperty($i)) {
                        var $callable = $watcher[$action][$i];

                        $callable();
                    }
                }
            }
        }
    };

    return JsonGeneratorObserver;
});