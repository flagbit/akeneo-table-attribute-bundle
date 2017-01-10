define(
    ['jquery', 'underscore'],
    function ($, _) {
        'use strict';
        return {
            init: function ($target, columns) {

                var $headerRow = $target.find('thead tr');
                var $footerRow = $target.find('tfoot tr');
                var $tbody = $target.find('tbody');
                var values = $target.find('input.table-data').val();

                values = $.parseJSON(values?values:'{}');

                _.each(columns.results, function (column) {
                    $headerRow.append("<th class='"+column.id+"'>"+column.text+"</th>");

                    var $input = this.getInputByConfig(column);


                    $footerRow.append("<th class='"+column.id+"'>"+column.text+"</th>");
                }.bind(this));

                _.each(values, function(row) {
                    $tbody.append('<tr>');
                    _.each(columns.results, function (column) {
                        var value = "";
                        if (column.id in row) {
                            value = row[column.id];
                        }

                        $tbody.append("<td class='"+column.id+"'>"+value+"</td>");
                    });
                    $tbody.append('</tr>');
                });
            },
            getInputByConfig: function(columnConfig) {

            }
        };
    }
);
