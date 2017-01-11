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
                                    var data = {
                                        results: []
                                    };
                                    _.each(response, function (value) {
                                        data.results.push(this.convertBackendItem(value));
                                    }.bind(this));
                                    initTable.init(this.$('.flagbit-table-attribute'), data);
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
                _.each(rows, function(row) {
                    var fields = {};

                    // TODO each field type needs an JS accessor to get the values in a normalized format
                    // This selector only works if only one of these form-fields is in the field
                    _.each($('td > input, textarea, select', row), function(field) {
                        fields[$(field).prop('name')] = $(field).val();
                    });

                    values.push(fields);
                });

                var valuesAsJson = JSON.stringify(values);
                // TODO check if the hidden field is needed and do this.setCurrentValue(valuesAsJson) instead
                this.$('.field-input:first .table-data').val(valuesAsJson);
            },
            convertBackendItem: function (item) {
                return {
                    id: item.code,
                    text: i18n.getLabel(item.labels, UserContext.get('catalogLocale'), item.code),
                    config: item.type_config,
                    type: item.type
                };
            }
        });
    }
);
