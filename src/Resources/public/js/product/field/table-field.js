'use strict';
define([
        'pim/field',
        'underscore',
        'jquery',
        'text!flagbit/template/product/field/table',
        'routing',
        'flagbit/inittable',
        'pim/user-context',
        'pim/i18n'
    ], function (
        Field,
        _,
        $,
        fieldTemplate,
        Routing,
        initTable,
        UserContext,
        i18n
    ) {
        return Field.extend({
            fieldTemplate: _.template(fieldTemplate),
            events: {
                'change .field-input:first .table-data': 'updateModel',
                'change .field-input:first .flagbit-table-values': 'updateJson'
            },
            columns: {},
            renderInput: function (context) {
                return this.fieldTemplate(context);
            },
            postRender: function() {
                this.getColumnUrl().then(function (columnUrl) {

                    $.ajax(
                        {
                            async: true,
                            type: 'GET',
                            url: columnUrl,
                            success: function (response) {
                                if (response) {
                                    _.each(response, function (value) {
                                        // this.columns.push(this.convertBackendItem(value));
                                        var column = this.convertBackendItem(value);
                                        this.columns[column.id] = column;
                                    }.bind(this));
                                    initTable.init(this.$('.flagbit-table-attribute'), this.columns);
                                }
                            }.bind(this)
                        }
                    );
                }.bind(this));
            },
            getColumnUrl: function () {
                return $.Deferred().resolve(
                    Routing.generate(
                        'pim_enrich_attributeoption_get',
                        {
                            identifier: this.attribute.code
                        }
                    )
                ).promise();
            },
            updateModel: function () {
                var data = this.$('.field-input:first .table-data').val();

                this.setCurrentValue(data);
            },
            updateJson: function () {
                var rows = this.$('.flagbit-table-values tr.flagbit-row');

                var values = [];
                var columns = this.columns;
                _.each(rows, function(row) {
                    var fields = {};

                    _.each($('td[data-code]', row), function(td) {
                        var id = $(td).data('code');
                        fields[id] = columns[id].func.parseValue($(td));
                    });

                    values.push(fields);
                });

                var valuesAsJson = JSON.stringify(values);
                this.$('.field-input:first .table-data').val(valuesAsJson);
                this.setCurrentValue(valuesAsJson);
            },
            convertBackendItem: function (item) {
                return {
                    id: item.code,
                    text: i18n.getLabel(item.labels, UserContext.get('catalogLocale'), item.code),
                    config: item.type_config,
                    type: item.type,
                    func: this.createColumnFunctions(item)
                };
            },
            createColumnFunctions: function(item) {
                // TODO Move this mapping to Symfony tags for every type to make this extendable
                var fieldTemplate;
                var parser = function (td) {
                    return $('input', td).val();
                };

                switch (item.type) {
                    case "text":
                        fieldTemplate = "<input type='text' name='<%= column.id %>' class='<%= column.id %>' value='<%= _.escape(value) %>' />";
                        break;
                    case "number":
                        fieldTemplate = "<input type='number' name='<%= column.id %>' class='<%= column.id %>' value='<%= _.escape(value) %>' step='<%= \'is_decimal\' in column.config && true === column.config.is_decimal ? 0.1 : 1 %>' />";
                        if ('is_decimal' in item.type_config && item.type_config.is_decimal === true) {
                            parser = function (td) {
                                return parseFloat($('input', td).val());
                            };
                        } else {
                            parser = function (td) {
                                return parseInt($('input', td).val());
                            };
                        }
                        break;
                    default:
                        throw "Unknown type '"+item.type+"'";
                }

                return {
                    renderField: _.template(fieldTemplate), // renders the template of the field
                    parseValue: parser // parses the value into the proper type for the json result
                };
            }
        });
    }
);
