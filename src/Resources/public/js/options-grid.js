'use strict';

define([
    'jquery',
    'underscore',
    'pim/form',
    'pim/fetcher-registry',
    'flagbit/tablecolumnview',
    'pim/template/attribute/tab/choices/options-grid'
],
function (
    $,
    _,
    BaseForm,
    fetcherRegistry,
    AttributeOptionGrid,
    template
) {
    return BaseForm.extend({
        template: _.template(template),
        locales: [],

        /**
         * {@inheritdoc}
         */
        configure: function () {
            return $.when(
                BaseForm.prototype.configure.apply(this, arguments),
                fetcherRegistry.getFetcher('locale').fetchActivated()
                    .then(function (locales) {
                        this.locales = locales;
                    }.bind(this))
            );
        },

        /**
         * {@inheritdoc}
         */
        render: function () {
            this.$el.html(this.template({
                attributeId: this.getFormData().meta.id,
                sortable: !this.getFormData().auto_option_sorting,
                localeCodes: _.pluck(this.locales, 'code')
            }));

            AttributeOptionGrid(this.$('.attribute-option-grid'));

            this.renderExtensions();
        }
    });
});
