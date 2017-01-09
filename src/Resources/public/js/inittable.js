define(
    ['jquery', 'underscore'],
    function ($, _) {
        'use strict';
        return {
            init: function ($target, columns) {

                var $headerRow = $target.find('thead tr');
                var $footerRow = $target.find('tfoot tr');
                var $tbody = $target.find('tbody');
                var value = $target.find('input.table-data').val();

                value = $.parseJSON(value?value:'{}');
console.log(value);

                _.each(columns.results, function (column) {
                    $headerRow.append("<th class='"+column.id+"'>"+column.text+"</th>");

                    var $input = this.getInputByConfig(column);


                    $footerRow.append("<th class='"+column.id+"'>"+column.text+"</th>");
                }.bind(this));

                _.each(value, function(row) {

                    var $tr = $tbody.append('<tr></tr>');

                    _.each(columns.results, function (column) {
                        $headerRow.append("<th class='"+column.id+"'>"+column.text+"</th>");
                    });
                });



            },
            getInputByConfig: function(columnConfig) {

            }
        };
    }
);
