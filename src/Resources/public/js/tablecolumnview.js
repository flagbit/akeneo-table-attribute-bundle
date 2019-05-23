define(
    [
        'jquery',
        'underscore',
        'backbone',
        'oro/translator',
        'routing',
        'oro/mediator',
        'oro/loading-mask',
        'pim/dialog',
        'flagbit/JsonGenerator',
        'jquery-ui'
    ],
    function ($, _, Backbone, __, Routing, mediator, LoadingMask, Dialog, JsonGenerator) {
        'use strict';

        var AttributeOptionItem = Backbone.Model.extend({
            defaults: {
                code: '',
                optionValues: {},
                constraints: {},
                type_config: {}
            },
            attributesToJson: function() {
                if (typeof this.get('constraints') === 'object') {
                    this.set('constraints', JSON.stringify(this.get('constraints')));
                }
                if (typeof this.get('type_config') === 'object') {
                    this.set('type_config', JSON.stringify(this.get('type_config')));
                }
            }
        });

        var ItemCollection = Backbone.Collection.extend({
            model: AttributeOptionItem,
            initialize: function (options) {
                this.url = options.url;
            }
        });

        var EditableItemView = Backbone.View.extend({
            tagName: 'tr',
            className: 'editable-item-row AknGrid-bodyRow',
            showTemplate: _.template(
                '<td class="AknGrid-bodyCell">' +
                    '<span class="handle"><i class="icon-reorder"></i></span>' +
                    '<span class="option-code"><%= item.code %></span>' +
                '</td>' +
                '<% _.each(locales, function (locale) { %>' +
                '<td class="AknGrid-bodyCell">' +
                    '<% if (item.optionValues[locale]) { %>' +
                        '<span title="<%= item.optionValues[locale].value %>">' +
                            '<%= item.optionValues[locale].value %>' +
                        '</span>' +
                    '<% } %>' +
                '</td>' +
                '<% }); %>' +
                '<td class="AknGrid-bodyCell">' +
                    '<% _.each(types, function (type) { %>' +
                        '<% if (item.type == type.type) { %>' +
                            '<span class="option-type">' +
                                '<%= type.label %>' +
                            '</span>' +
                        '<% } %>' +
                    '<% }); %>' +
                '</td>' +
                '<td class="AknGrid-bodyCell">' +
                    '<span class="option-constraint json-generator json-constraint-generator"><%= item.constraints %></span>' +
                '</td>' +
                '<td class="AknGrid-bodyCell">' +
                    '<span class="option-config json-generator json-<%= item.type %>-generator"><%= item.type_config %></span>' +
                '</td>' +
                '<td class="AknGrid-bodyCell">' +
                    '<div class="AknButtonList AknButtonList--right">' +
                        '<span class="btn btn-small AknButtonList-item AknIconButton AknIconButton--small AknIconButton--apply edit-row"><i class="icon-pencil"></i></span>' +
                        '<span class="btn btn-small AknButtonList-item AknIconButton AknIconButton--small AknIconButton--important delete-row"><i class="icon-trash"></i></span>' +
                    '</div>' +
                '</td>'
            ),
            editTemplate: _.template(
                '<td class="AknGrid-bodyCell field-cell">' +
                    '<div class="AknFieldContainer-inputContainer">' +
                        '<input type="text" class="attribute_option_code exclude AknTextField" value="<%= item.code %>"/>' +
                        '<i class="validation-tooltip hidden AknIconButton AknIconButton--hide AknIconButton--important" data-placement="top" data-toggle="tooltip"></i>' +
                    '</div>' +
                '</td>' +
                '<% _.each(locales, function (locale) { %>' +
                '<td class="AknGrid-bodyCell field-cell">' +
                    '<% if (item.optionValues[locale]) { %>' +
                        '<input type="text" class="attribute-option-value exclude AknTextField" data-locale="<%= locale %>" ' +
                            'value="<%= item.optionValues[locale].value %>"/>' +
                    '<% } else { %>' +
                        '<input type="text" class="attribute-option-value exclude AknTextField" data-locale="<%= locale %>" ' +
                            'value=""/>' +
                    '<% } %>' +
                '</td>' +
                '<% }); %>' +
                '<td class="AknGrid-bodyCell">' +
                    '<select class="attribute_option_type exclude" >' +
                        '<% _.each(types, function (type) { %>' +
                            '<option value="<%= type.type %>" <% if (item.type == type.type) { %> selected="selected" <% } %>><%= type.label %></option>' +
                        '<% }); %>' +
                    '</select>' +
                '</td>' +
                '<td class="AknGrid-bodyCell">' +
                    '<textarea class="attribute_option_constraints exclude json-generator json-constraint-generator" ><%= item.constraints %></textarea>' +
                '</td>' +
                '<td class="AknGrid-bodyCell">' +
                    '<textarea class="attribute_option_config exclude json-generator json-<%= item.type %>-generator" ><%= item.type_config %></textarea>' +
                '</td>' +
                '<td class="AknGrid-bodyCell">' +
                    '<div class="AknButtonList AknButtonList--right">' +
                        '<span class="btn btn-small AknButtonList-item AknIconButton AknIconButton--small AknIconButton--apply update-row"><i class="icon-ok"></i></span>' +
                        '<span class="btn btn-small AknButtonList-item AknIconButton AknIconButton--small AknIconButton--important show-row"><i class="icon-remove"></i></span>' +
                    '</div>' +
                '</td>'
            ),
            events: {
                'click .show-row':   'stopEditItem',
                'click .edit-row':   'startEditItem',
                'click .delete-row': 'deleteItem',
                'click .update-row': 'updateItem',
                'keyup input':       'soil',
                'keydown':           'cancelSubmit',
                'change .attribute_option_type': 'changeType'
            },
            editable: false,
            parent: null,
            loading: false,
            locales: [],
            initialize: function (options) {
                this.locales       = options.locales;
                this.parent        = options.parent;
                this.model.urlRoot = this.parent.updateUrl;
                this.types = [{
                    'type': 'text',
                    'label': __('pim_enrich.entity.attribute.type.pim_catalog_text')
                }, {
                    'type': 'number',
                    'label': __('pim_enrich.entity.attribute.type.pim_catalog_number')
                }, {
                    'type':  'select',
                    'label': __('pim_enrich.entity.attribute.type.pim_catalog_simpleselect')
                }, {
                    'type':  'select_from_url',
                    'label': __('pim_enrich.entity.attribute.type.pim_catalog_simpleselect_from_url')
                }];

                this.render();
            },
            render: function () {
                var template = null;
                var types = this.types;

                if (this.editable) {
                    this.clean();
                    this.$el.addClass('in-edition');
                    template = this.editTemplate;
                } else {
                    this.$el.removeClass('in-edition');
                    template = this.showTemplate;
                }

                this.model.attributesToJson();
                this.$el.html(template({
                    item: this.model.toJSON(),
                    locales: this.locales,
                    types: types
                }));

                this.$el.find('.json-generator').each(function() {
                    new JsonGenerator(this, types);
                }).bind(types);

                this.$el.attr('data-item-id', this.model.id);

                return this;
            },
            changeType: function () {
                this.inLoading(true);
                var editedModel = this.loadModelFromView();
                this.model.set(editedModel.attributes);
                this.render();
                this.inLoading(false);
                this.clean();
            },
            showReadableItem: function () {
                this.editable = false;
                this.parent.showReadableItem(this);
                this.clean();
                this.render();
            },
            showEditableItem: function () {
                this.editable = true;
                this.render();
                this.model.set(this.loadModelFromView().attributes);
            },
            startEditItem: function () {
                var rowIsEditable = this.parent.requestRowEdition(this);

                if (rowIsEditable) {
                    this.showEditableItem();
                }
            },
            stopEditItem: function () {
                if (!this.model.id || this.dirty) {
                    if (this.dirty) {
                        Dialog.confirm(
                            __('confirm.attribute_option.cancel_edition_on_new_option_text'),
                            __('confirm.attribute_option.cancel_edition_on_new_option_title'),
                            function () {
                                this.showReadableItem(this);
                                if (!this.model.id) {
                                    this.deleteItem();
                                }
                            }.bind(this));
                    } else {
                        if (!this.model.id) {
                            this.deleteItem();
                        } else {
                            this.showReadableItem();
                        }
                    }
                } else {
                    this.showReadableItem();
                }
            },
            deleteItem: function () {
                var itemCode = this.el.firstChild.innerText;

                Dialog.confirm(
                    __('pim_enrich.item.delete.confirm.content', {'itemName': itemCode}),
                    __('pim_enrich.item.delete.confirm.title', {'itemName': itemCode}),
                    function () {
                        this.parent.deleteItem(this);
                    }.bind(this)
                );
            },
            updateItem: function () {
                this.inLoading(true);

                var editedModel = this.loadModelFromView();

                editedModel.save(
                    {},
                    {
                        url: this.model.url(),
                        success: function () {
                            this.inLoading(false);
                            this.model.set(editedModel.attributes);
                            this.clean();
                            this.stopEditItem();
                        }.bind(this),
                        error: function (data, xhr) {
                            this.inLoading(false);

                            var response = xhr.responseJSON;
                            var _response = response;
                            if(_response.children) {
                                _response = _response.children;
                            }
                            if (_response && _response.code) {
                                var error = _response.code;
                                var message = '';
                                if(_response.code) {
                                    if(_response.code.errors) {
                                        message = _response.code.errors.join('<br/>');
                                    }
                                    else {
                                        message = _response.code;
                                    }
                                }
                                this.$el.find('.validation-tooltip')
                                    .addClass('visible')
                                    .tooltip('destroy')
                                    .tooltip({title: message})
                                    .tooltip('show');

                                this.$el.find('.AknIconButton--hide')
                                    .removeClass('AknIconButton--hide')
                                    .addClass('icon-warning-sign');

                            } else {
                                Dialog.alert(
                                    __('alert.attribute_option.error_occured_during_submission'),
                                    __('error.saving.attribute_option')
                                );
                            }
                        }.bind(this)
                    }
                );
            },
            cancelSubmit: function (e) {
                if (e.keyCode === 13) {
                    this.updateItem();

                    return false;
                }
            },
            loadModelFromView: function () {
                var attributeOptions = {};
                var editedModel = this.model.clone();

                editedModel.urlRoot = this.model.urlRoot;

                _.each(this.$el.find('.attribute-option-value'), function (input) {
                    var locale = input.dataset.locale;

                    attributeOptions[locale] = {
                        locale: locale,
                        value:  input.value,
                        id:     this.model.get('optionValues')[locale] ?
                            this.model.get('optionValues')[locale].id :
                            null
                    };
                }.bind(this));

                editedModel.set('code', this.$el.find('.attribute_option_code').val());
                editedModel.set('optionValues', attributeOptions);
                editedModel.set('type', this.$el.find('.attribute_option_type').val());
                try {
                    editedModel.set('constraints', this.$el.find('.attribute_option_constraints').val());
                    editedModel.set('type_config', this.$el.find('.attribute_option_config').val());
                } catch (e) {
                    Dialog.alert(
                        __('flagbit.attribute_table.alert.json_error_text'),
                        __('flagbit.attribute_table.alert.json_error_title')
                    );
                }

                return editedModel;
            },
            inLoading: function (loading) {
                this.parent.inLoading(loading);
            },
            soil: function () {
                if (JSON.stringify(this.model.attributes) !== JSON.stringify(this.loadModelFromView().attributes)) {
                    this.dirty = true;
                } else {
                    this.dirty = false;
                }
            },
            clean: function () {
                this.dirty = false;
            }
        });

        var ItemCollectionView = Backbone.View.extend({
            tagName: 'table',
            className: 'table table-bordered table-stripped attribute-option-view AknGrid AknGrid--unclickable',
            template: _.template(
                '<!-- Pim/Bundle/EnrichBundle/Resources/public/js/pim-attributeoptionview.js -->' +
                '<colgroup>' +
                    '<col class="code" span="1"></col>' +
                    '<col class="fields" span="<%= locales.length %>"></col>' +
                    '<col class="type" span="1"></col>' +
                    '<col class="constaint" span="1"></col>' +
                    '<col class="config" span="1"></col>' +
                    '<col class="action" span="1"></col>' +
                '</colgroup>' +
                '<thead>' +
                    '<tr>' +
                        '<th class="AknGrid-headerCell"><%= code_label %></th>' +
                        '<% _.each(locales, function (locale) { %>' +
                        '<th class="AknGrid-headerCell">' +
                            '<%= locale %>' +
                        '</th>' +
                        '<% }); %>' +
                        '<th class="AknGrid-headerCell"><%= type_label %></th>' +
                        '<th class="AknGrid-headerCell"><%= constraint_label %></th>' +
                        '<th class="AknGrid-headerCell"><%= config_label %></th>' +
                        '<th class="AknGrid-headerCell">Action</th>' +
                    '</tr>' +
                '</thead>' +
                '<tbody></tbody>' +
                '<tfoot>' +
                    '<tr>' +
                        '<td colspan="<%= 5 + locales.length %>">' +
                            '<span class="btn AknButton AknButton--apply AknButton--small option-add pull-right"><%= add_column_label %></span>' +
                        '</td>' +
                    '</tr>' +
                '</tfoot>'
            ),
            events: {
                'click .option-add': 'addItem'
            },
            $target: null,
            locales: [],
            sortable: true,
            sortingUrl: '',
            updateUrl: '',
            currentlyEditedItemView: null,
            itemViews: [],
            rendered: false,
            initialize: function (options) {
                this.$target    = options.$target;
                this.collection = new ItemCollection({url: options.updateUrl});
                this.locales    = options.locales;
                this.updateUrl  = options.updateUrl;
                this.sortingUrl = options.sortingUrl;
                this.sortable   = options.sortable;

                this.render();
                this.load();
            },
            render: function () {
                this.$el.empty();

                this.currentlyEditedItemView = null;
                this.updateEditionStatus();

                this.$el.html(this.template({
                    locales: this.locales,
                    add_column_label: __('label.attribute_table.add_column'),
                    code_label: __('flagbit.attribute_table.code.label'),
                    type_label: __('flagbit_attribute_table_type_label'),
                    constraint_label: __('flagbit_attribute_table_validation_label'),
                    config_label: __('flagbit_attribute_table_config_label')
                }));

                _.each(this.collection.models, function (attributeOptionItem) {
                    this.addItem({item: attributeOptionItem});
                }.bind(this));

                if (0 === this.collection.length) {
                    this.addItem();
                }

                if (!this.rendered) {
                    this.$target.html(this.$el);

                    this.rendered = true;
                }

                this.tbodyElement = this.$el.find('tbody');

                this.tbodyElement.sortable({
                    axis: 'y',
                    distance: 5,
                    cursor: 'move',
                    scroll: true,
                    start: function(e, ui){
                        ui.placeholder.height($(ui.item[0]).height());
                    },
                    helper: function (e, ui) {
                        ui.children().each(function () {
                            $(this).width($(this).width());
                        });

                        return ui;
                    },
                    stop: function () {
                        this.updateSorting();
                    }.bind(this)
                });

                this.updateSortableStatus(this.sortable);

                return this;
            },
            load: function () {
                this.itemViews = [];
                this.inLoading(true);
                this.collection
                    .fetch({
                        success: function () {
                            this.inLoading(false);
                            this.render();
                        }.bind(this)
                    });
            },
            addItem: function (opts) {
                var options = opts || {};

                //If no item model provided we create one
                var itemToAdd;
                if (!options.item) {
                    itemToAdd = new AttributeOptionItem();
                } else {
                    itemToAdd = options.item;
                }

                var newItemView = this.createItemView(itemToAdd);

                if (newItemView) {
                    this.$el.children('tbody').append(newItemView.$el);
                }
            },
            createItemView: function (item) {
                var itemView = new EditableItemView({
                    model:    item,
                    url:      this.updateUrl,
                    locales:  this.locales,
                    parent:   this
                });

                //If the item is new the view is changed to edit mode
                if (!item.id) {
                    if (!this.requestRowEdition(itemView)) {
                        return;
                    } else {
                        itemView.showEditableItem();
                    }
                }

                this.collection.add(item);
                this.itemViews.push(itemView);

                return itemView;
            },
            requestRowEdition: function (attributeOptionRow) {
                if (this.currentlyEditedItemView) {
                    if (this.currentlyEditedItemView.dirty) {
                        Dialog.alert(__('alert.attribute_option.save_before_edit_other'));

                        return false;
                    } else {
                        this.currentlyEditedItemView.stopEditItem();
                        this.currentlyEditedItemView = null;
                        this.updateEditionStatus();
                    }
                }

                if (attributeOptionRow.model.id) {
                    this.currentlyEditedItemView = attributeOptionRow;
                }

                this.updateEditionStatus();

                return true;
            },
            showReadableItem: function (item) {
                if (item === this.currentlyEditedItemView) {
                    this.currentlyEditedItemView = null;
                    this.updateEditionStatus();
                }
            },
            deleteItem: function (item) {
                this.inLoading(true);

                item.model.destroy({
                    success: function () {
                        this.inLoading(false);

                        this.collection.remove(item);
                        this.currentlyEditedItemView = null;
                        this.updateEditionStatus();

                        if (0 === this.collection.length) {
                            this.addItem();
                            item.$el.hide(0);
                        } else if (!item.model.id) {
                            item.$el.hide(0);
                        } else {
                            item.$el.hide(500);
                        }
                    }.bind(this),
                    error: function (data, response) {
                        this.inLoading(false);
                        var message;

                        if (response.responseJSON) {
                            message = response.responseJSON.message;
                        } else {
                            message = response.responseText;
                        }

                        Dialog.alert(message, __('error.removing.attribute_option'));
                    }.bind(this)
                });
            },
            updateEditionStatus: function () {
                if (this.currentlyEditedItemView) {
                    this.$el.addClass('in-edition');
                } else {
                    this.$el.removeClass('in-edition');
                }
            },
            updateSortableStatus: function (sortable) {
                this.sortable = sortable;

                if (sortable) {
                    this.tbodyElement.sortable('enable');
                } else {
                    this.tbodyElement.sortable('disable');
                }
            },
            updateSorting: function () {
                this.inLoading(true);
                var sorting = [];

                var rows = this.$el.find('tbody tr.editable-item-row');
                for (var i = rows.length - 1; i >= 0; i--) {
                    sorting[i] = rows[i].dataset.itemId;
                }

                $.ajax({
                    url: this.sortingUrl,
                    type: 'PUT',
                    data: JSON.stringify(sorting)
                }).done(function () {
                    this.inLoading(false);
                }.bind(this));
            },
            inLoading: function (loading) {
                if (loading) {
                    var loadingMask = new LoadingMask();
                    loadingMask.render().$el.appendTo(this.$el);
                    loadingMask.show();
                } else {
                    this.$el.find('.loading-mask').remove();
                }
            }
        });

        return function ($element) {
            var itemCollectionView = new ItemCollectionView(
            {
                $target: $element,
                updateUrl: Routing.generate(
                    'pim_enrich_attributeoption_index',
                    {attributeId: $element.data('attribute-id')}
                ),
                sortingUrl: Routing.generate(
                    'pim_enrich_attributeoption_update_sorting',
                    {attributeId: $element.data('attribute-id')}
                ),
                locales: $element.data('locales'),
                sortable: $element.data('sortable')
            });

            mediator.on('attribute:auto_option_sorting:changed', function (autoSorting) {
                itemCollectionView.updateSortableStatus(!autoSorting);
            }.bind(this));
        };
    }
);
