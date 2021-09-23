describe('Form Extensions', function() {
    it('table attribute overrides akeneo-attribute-select-filter', function() {
        const formExtensions = require('../../tests/public/js/extensions.json');

        const expected = {
            module: 'pim/filter/attribute/select',
            parent: null,
            targetZone: 'self',
            zones: [],
            aclResourceId: null,
            config: {
                url: 'pim_ui_ajaxentity_list',
                entityClass: 'Flagbit\\Bundle\\TableAttributeBundle\\Entity\\AttributeOption',
                operators: [ 'IN', 'EMPTY', 'NOT EMPTY' ]
            },
            position: 100,
            feature: null,
            code: 'akeneo-attribute-select-filter'
        };

        expect(formExtensions.extensions).toContainEqual(expected);
    });
});
