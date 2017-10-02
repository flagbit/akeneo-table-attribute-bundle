define(
    ['jquery', 'underscore'],
    function ($, _) {
        'use strict';
        return {
            init: function ($target, columns) {

                var $headerRow = $target.find('thead tr');
                if($headerRow[0].innerHTML && $headerRow[0].innerHTML.length != 0){
                    return;
                }

                var $footerRow = $target.find('tfoot tr');
                var $tbody = $target.find('tbody');
                var values = $target.find('input.table-data').val();

                values = $.parseJSON(values?values:'{}');

                var emptyTitle = '<th class="AknGrid-headerCell" style="width: 47px"></th>';
                // Title for reorder column
                $headerRow.append(emptyTitle);
                $footerRow.append(emptyTitle);
                _.each(columns, function (column) {
                    var th = "<th class='AknGrid-headerCell "+column.id+"'>"+column.text+"</th>";
                    $headerRow.append(th);
                    $footerRow.append(th);
                }.bind(this));
                // Title for delete button column
                $headerRow.append(emptyTitle);
                $footerRow.append(emptyTitle);

                _.each(values, function(row) {
                    var htmlColumns = [];
                    _.each(columns, function (column) {
                        var value = "";
                        if (column.id in row) {
                            value = row[column.id];
                        }

                        htmlColumns.push(this.createColumn(column, value));
                    }.bind(this));
                    $tbody.append(this.createRow(htmlColumns));
                }.bind(this));
            },
            createColumn: function (column, value) {
                var td =  $("<td class='AknGrid-bodyCell "+column.id+"' data-code='"+column.id+"'>"+column.func.renderField({column: column, value: value})+"</td>");
                column.func.init(td, column, value);

                return td;
            },
            createRow: function (htmlColumns) {
                var row = $('<tr class="flagbit-table-row AknGrid-bodyRow editable-item-row"></tr>');
                row.append($('<td class="AknGrid-bodyCell"><span class="handle"><i class="icon-reorder"></i></span></td>'));
                _.each(htmlColumns, function (htmlColumn) {
                    row.append(htmlColumn);
                });
                row.append($('<td class="AknGrid-bodyCell"><span class="btn btn-small AknButton AknButton--small AknIconButton--important delete-row"><i class="icon-trash"></i></span></td>'));

                return row;
            },
            createEmptyRow: function (columns) {
                var htmlColumns = [];
                _.each(columns, function (column) {
                    htmlColumns.push(this.createColumn(column, ''));
                }.bind(this));

                return this.createRow(htmlColumns);
            }
        };
    }
);
