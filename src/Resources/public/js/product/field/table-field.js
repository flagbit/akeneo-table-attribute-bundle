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
                'change .field-input:first .table-type': 'updateModel'
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
                //
                //    enrichedContext.columnConfig = $.ajax({
                //        url: columnUrl,
                //        quietMillis: 250,
                //        cache: true,
                //        data: function (term, page) {
                //            return {
                //                search: term,
                //                options: {
                //                    limit: 20,
                //                    locale: UserContext.get('catalogLocale'),
                //                    page: page
                //                }
                //            };
                //        }.bind(this),
                //        results: function (response) {
                //            if (response.results) {
                //                response.more = 20 === _.keys(response.results).length;
                //
                //                return response;
                //            }
                //
                //            var data = {
                //                more: 20 === _.keys(response).length,
                //                results: []
                //            };
                //            _.each(response, function (value) {
                //                data.results.push(this.convertBackendItem(value));
                //            }.bind(this));
                //
                //            return data;
                //        }.bind(this)
                //    });
                //}.bind(this));
                //console.log(enrichedContext);

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
                var data = this.$('.field-input:first .table-type').val();

                this.setCurrentValue(data);
            },
            convertBackendItem: function (item) {
                return {
                    id: item.code,
                    text: i18n.getLabel(item.labels, UserContext.get('catalogLocale'), item.code)
                };
            }
        });
    }
);
