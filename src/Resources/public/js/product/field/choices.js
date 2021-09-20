'use strict';

define([
    'underscore',
    'oro/translator',
    'pim/form',
    'pim/template/common/form-container'
],
function (
    _,
    __,
    BaseForm,
    template
) {
    return BaseForm.extend({
        className: 'tab-content',
        template: _.template(template),

        /**
         * {@inheritdoc}
         */
        initialize: function () {
            BaseForm.prototype.initialize.apply(this, arguments);
        },

        /**
         * {@inheritdoc}
         */
        configure: function () {
            if (!this.isActive()) {
                return;
            }

            this.trigger('tab:register', {
                code: this.code,
                label: __('flagbit.table_attribute.form.attribute.tab.title')
            });

            return BaseForm.prototype.configure.apply(this, arguments);
        },

        /**
         * {@inheritdoc}
         */
        render: function () {
            if (!this.isActive()) {
                return;
            }

            this.$el.html(this.template());

            this.renderExtensions();
        },

        isActive: function () {
            return ['flagbit_catalog_table'].includes((this.getRoot()).getType());
        }
    });
});
