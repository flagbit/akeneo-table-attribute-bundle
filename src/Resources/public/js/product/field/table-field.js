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
                'change .field-input:first .flagbit-table-values': 'updateJson',
                'click .field-input:first .flagbit-table-values .delete-row': 'deleteItem',
                'click .field-input:first .flagbit-table-attribute .item-add': 'addItem'
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
                                        var column = this.convertBackendItem(value);
                                        this.columns[column.id] = column;
                                    }.bind(this));
                                    initTable.init(this.$('.flagbit-table-attribute'), this.columns);
                                    // initialize dran & drop sorting
                                    this.$('.flagbit-table-values tbody').sortable({
                                        handle: ".handle",
                                        stop: function() {
                                            this.updateJson();
                                        }.bind(this)
                                    });
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
                var rows = this.$('.flagbit-table-values tr.flagbit-table-row');

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
            deleteItem: function (event) {
                $(event.currentTarget).closest('tr').remove();
                this.updateJson();
            },
            addItem: function () {
                this.$('table.flagbit-table-values').append(initTable.createEmptyRow(this.columns));
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
                var init = function (td, column, value) {
                };

                switch (item.type) {
                    case "text":
                        fieldTemplate = "<input data-type='<%= column.type %>' type='text' name='<%= column.id %>' class='<%= column.id %>' value='<%= _.escape(value.toString()) %>' />";
                        break;
                    case "number":
                        fieldTemplate = "<input data-type='<%= column.type %>' type='number' name='<%= column.id %>' class='<%= column.id %>' value='<%= _.escape(value.toString()) %>' step='<%= \'is_decimal\' in column.config && true === column.config.is_decimal ? 0.1 : 1 %>' />";
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
                    case "select":
                        fieldTemplate = "<input data-type='<%= column.type %>' type='text' name='<%= column.id %>' class='<%= column.id %>' value='<%= value %>' />";

                        parser = function (td) {
                            var option = $('input', td).select2('data');
                            return option.id;
                        };

                        init = function (td, column, value) {
                            var select2Config = {
                                placeholder: ' '
                            };
                            if ('options' in column.config) {
                                var options = [];
                                _.each(column.config.options, function(option, key) {
                                    options.push({ id: key, text: option });
                                });
                                select2Config.data = options;
                            } else if ('options_url' in column.config) {
                                select2Config.ajax = {
                                    url: column.config.options_url,
                                    cache: true,
                                    minimumInputLength: 0,
                                    dataType: 'json',
                                    quietMillis: 1000,
                                    results: function (data) {
                                        return data;
                                    },
                                    data: function (term) {
                                        return {
                                            q: term
                                        };
                                    }
                                };
                                // initSelection needs to be cleaned up in the future without forcing a whole API
                                select2Config.initSelection = function(element, callback) {
                                    var option = $(element).val();

                                    if (option !== '') {
                                        $.ajax(column.config.options_url, {
                                            dataType: "json",
                                            cache: true
                                        }).done(function (data) {
                                            var selected = _.find(data.results, function (row) {
                                                return row.id === option;
                                            });
                                            callback(selected);
                                        });
                                    }
                                };
                            }

                            var select2 = $('input', td).select2(select2Config);
                            select2.on('select2-close', function () {
                                this.updateJson();
                            }.bind(this));
                        }.bind(this);
                        break;
                    default:
                        throw "Unknown type '"+item.type+"'";
                }

                return {
                    renderField: _.template(fieldTemplate), // renders the template of the field
                    parseValue: parser, // parses the value into the proper type for the json result
                    init: init // an optional function that allows to initialize third party plugins
                };
            }
        });
    }
);
