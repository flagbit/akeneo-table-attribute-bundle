define(
    [
        'flagbit/JsonGenerator/Observer',
        'oro/translator',
    ],
    function (JsonGeneratorObserver, __) {

        /**
         * @class
         * @param {Boolean} $editable
         * @param {HTMLElement} $container
         */
        var JsonGeneratorRendererSelect = function ($editable, $container) {

            /**
             * @protected
             * @type {HTMLTableElement}
             */
            var $table = null;
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

                var $label = document.createElement('label');
                $label.innerText = __('flagbit.table_attribute.simpleselect_options.label');
                $label.className = 'select-options-config-label';
                $container.appendChild($label);

                // needed!
                this.getTable();

                for (var $key in $data.options) {
                    if ($data.options.hasOwnProperty($key)) {
                        var $value = $data.options[$key];

                        var $keyCol = createTableColumn($key);
                        var $valCol = createTableColumn($value);
                        var $row = document.createElement('tr');

                        $row.appendChild($keyCol);
                        $row.appendChild($valCol);
                        if ($editable) {
                            $row.appendChild(createDeleteButton($row));
                        }
                        this.getTable().appendChild($row);
                    }
                }
            };


            /**
             * @public
             * @returns {Object}
             */
            this.read = function () {

                var $data = {options: {}};

                var $collection = this.getTable().querySelectorAll('tr');
                for (var $i in $collection) {
                    if ($collection.hasOwnProperty($i)) {
                        var $tr = $collection[$i];
                        $data.options[$tr.querySelectorAll('td input')[0].value] = $tr.querySelectorAll('td input')[1].value;
                    }
                }

                return $data;
            };

            /**
             * @public
             * @returns {HTMLTableSectionElement}
             */
            this.getTable = function () {

                if ($table === null) {
                    $table = document.createElement('table');
                    $table.className = 'AknGrid AknGrid--unclickable select-options-table';

                    var $thead = document.createElement('thead');
                    var $thRow = document.createElement('tr');
                    $thRow.className = 'AknGrid-bodyRow';
                    var $thCl1 = document.createElement('th');
                    $thCl1.className = 'AknGrid-headerCell';
                    $thCl1.innerText = __('flagbit.table_attribute.simpleselect.key.label');
                    var $thCl2 = document.createElement('th');
                    $thCl2.className = 'AknGrid-headerCell';
                    $thCl2.innerText = __('flagbit.table_attribute.simpleselect.value.label');

                    $thRow.appendChild($thCl1);
                    $thRow.appendChild($thCl2);
                    $thead.appendChild($thRow);
                    $table.appendChild($thead);

                    var $tbody = document.createElement('tbody');
                    $table.appendChild($tbody);

                    if ($editable) {
                        var $tfoot = document.createElement('tfoot');
                        var $tfRow = document.createElement('tr');
                        var $tfCol = document.createElement('td');
                        $tfCol.className = 'AknGrid-bodyCell field-cell';
                        var $tfBut = document.createElement('button');

                        $tfBut.innerText = __('pim_enrich.entity.product.module.attribute.add_option');
                        $tfBut.type = 'button';
                        $tfBut.className = 'btn AknButton AknButton--small pull-right';
                        $tfBut.addEventListener('click', addRow);
                        $tfCol.colSpan = 3;
                        $tfCol.appendChild($tfBut);
                        $tfRow.appendChild($tfCol);
                        $tfoot.appendChild($tfRow);
                        $table.appendChild($tfoot);
                    }

                    $container.appendChild($table);
                }

                return $table.querySelector('tbody');
            };


            /**
             * @protected
             */
            var addRow = function () {

                var $row = document.createElement('tr');
                $row.appendChild(createTableColumn(''));
                $row.appendChild(createTableColumn(''));
                if ($editable) {
                    $row.appendChild(createDeleteButton($row));
                }

                this.getTable().appendChild($row);

                notify();
            }.bind(this);


            /**
             * @protected
             * @param {String} $text
             */
            var createTableColumn = function ($text) {

                var $column = document.createElement('td');

                if ($editable) {
                    var $input = document.createElement('input');
                    $input.type = 'text';
                    $input.value = $text;
                    $input.className = 'AknTextField';
                    observeChanges($input);
                    $column.appendChild($input);
                } else {
                    $column.innerText = $text;
                }

                return $column;
            }.bind(this);


            /**
             * @protected
             * @param {HTMLTableRowElement} $row
             */
            var createDeleteButton = function ($row) {
                var $col = createTableColumn();
                $col.innerHTML = '<span class="btn btn-small AknButton AknButton--small"><i class="icon-remove"></i></span>';
                $col.querySelector('span').addEventListener('click', function () {
                    $row.parentNode.removeChild($row);
                    notify();
                });

                return $col;
            }.bind(this);


            /**
             * @protected
             * @param {HTMLInputElement} $input
             */
            var observeChanges = function ($input) {
                $input.addEventListener('keyup', notify);
                $input.addEventListener('blur', notify);
            }.bind(this);


            /**
             * @protected
             */
            var notify = function () {

                this.observer.notify('update');
            }.bind(this);
        };

        return JsonGeneratorRendererSelect;
    }
);
