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

                var emptyTitle = '<th style="width: 30px"></th>';
                // Title for reorder column
                $headerRow.append(emptyTitle);
                $footerRow.append(emptyTitle);
                _.each(columns, function (column) {
                    var th = "<th class='"+column.id+"'>"+column.text+"</th>";
                    $headerRow.append(th);
                    $footerRow.append(th);
                }.bind(this));
                // Title for edit and delete buttons column
                emptyTitle = '<th></th>';
                $headerRow.append(emptyTitle);
                $footerRow.append(emptyTitle);

                _.each(values, function(row) {
                    var html = '<tr class="flagbit-row editable-item-row"><td><span class="handle"><i class="icon-reorder"></i></span></td>';
                    _.each(columns, function (column) {
                        var value = "";
                        if (column.id in row) {
                            value = row[column.id];
                        }

                        html += "<td class='"+column.id+"' data-code='"+column.id+"'>"+column.func.renderField({column: column, value: value})+"</td>";
                    });
                    html += '<td><span class="btn btn-small delete-row"><i class="icon-trash"></i></span></td>';
                    html += '</tr>';
                    $tbody.append(html);
                });
            }
        };
    }
);
