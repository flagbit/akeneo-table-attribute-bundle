'use strict';
define([
        'pim/field',
        'underscore',
        'text!flagbit/template/product/field/table'
    ], function (
        Field,
        _,
        fieldTemplate
    ) {
        return Field.extend({
            fieldTemplate: _.template(fieldTemplate),
            events: {
                'change .field-input:first .table-type': 'updateModel'
            },
            renderInput: function (context) {
                return this.fieldTemplate(context);
            },
            updateModel: function () {
                var data = this.$('.field-input:first .table-type').val();

                this.setCurrentValue(data);
            }
        });
    }
);
