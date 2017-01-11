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

                _.each(columns, function (column) {
                    $headerRow.append("<th class='"+column.id+"'>"+column.text+"</th>");

                    $footerRow.append("<th class='"+column.id+"'>"+column.text+"</th>");
                }.bind(this));

                _.each(values, function(row) {
                    var html = '<tr class="flagbit-row">';
                    _.each(columns, function (column) {
                        var value = "";
                        if (column.id in row) {
                            value = row[column.id];
                        }

                        html += "<td class='"+column.id+"' data-code='"+column.id+"'>"+column.func.renderField({column: column, value: value})+"</td>";
                    });
                    html += '</tr>';
                    $tbody.append(html);
                });
            }
        };
    }
);
