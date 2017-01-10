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

                    column.renderField = this.getInputByConfig(column);

                    $footerRow.append("<th class='"+column.id+"'>"+column.text+"</th>");
                }.bind(this));

                _.each(values, function(row) {
                    $tbody.append('<tr>');
                    _.each(columns.results, function (column) {
                        var value = "";
                        if (column.id in row) {
                            value = row[column.id];
                        }

                        $tbody.append("<td class='"+column.id+"'>"+column.renderField({column: column, value: value})+"</td>");
                    });
                    $tbody.append('</tr>');
                });
            },
            getInputByConfig: function(column) {
                // TODO Move this mapping to Symfony tags for every type to make this extendable
                var fieldTemplate;

                switch (column.type) {
                    case "text":
                        fieldTemplate = "<input type='text' name='<%= column.id %>' class='<%= column.id %>' value='<%= _.escape(value) %>' />";
                        break;
                    case "number":
                        fieldTemplate = "<input type='number' name='<%= column.id %>' class='<%= column.id %>' value='<%= _.escape(value) %>' step='<%= \'is_decimal\' in column.config && true === column.config.is_decimal ? 0.1 : 1 %>' />";
                        break;
                    default:
                        throw "Unknown type '"+column.type+"'";
                }

                return _.template(fieldTemplate);
            }
        };
    }
);
