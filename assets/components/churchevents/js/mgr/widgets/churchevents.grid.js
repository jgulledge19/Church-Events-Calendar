/* YOU will need to edit this file with proper names, follow the cases(upper/lower) */
Cmp.grid.Calendar = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'cmp-grid-calendar'
        ,url: Cmp.config.connectorUrl
        ,baseParams: { action: 'mgr/calendar/getList' }
        ,save_action: 'mgr/calendar/updateFromGrid'
        ,fields: ['id','title','description','request_notify']
        ,paging: true
        ,autosave: true
        ,remoteSort: true
        ,anchor: '97%'
        ,autoExpandColumn: 'description'
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,sortable: true
            ,width: 30
        },{
            header: _('churchevents.calendar_title')
            ,dataIndex: 'title'
            ,sortable: true
            ,width: 100 
            ,allowBlank : false
            ,editor: { xtype: 'textfield' }
        },{
            header: _('churchevents.calendar_description')
            ,dataIndex: 'description'
            ,sortable: false
            ,width: 200
            ,wrap: true
            ,allowBlank : false
            ,editor: { xtype: 'textfield' }
        },{
            header: _('churchevents.calendar_request_notify')
            ,dataIndex: 'request_notify'
            ,sortable: true
            ,width: 80
            ,allowBlank : false
            ,editor: { xtype: 'textfield' }
        }]
        ,tbar: [{
            xtype: 'textfield'
            ,id: 'cmp-search-filter'
            ,emptyText: _('churchevents.search...')
            ,listeners: {
                'change': {fn:this.search,scope:this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: function() {
                            this.fireEvent('change',this);
                            this.blur();
                            return true;
                        }
                        ,scope: cmp
                    });
                },scope:this}
            }
        },{
            text: _('churchevents.calendar_create')
            ,handler: { xtype: 'cmp-window-calendar-create' ,blankValues: true }
        }]
    });
    Cmp.grid.Calendar.superclass.constructor.call(this,config);
};

Ext.extend(Cmp.grid.Calendar,MODx.grid.Grid,{
    search: function(tf,nv,ov) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    ,getMenu: function() {
        var m = [{
            text: _('churchevents.calendar_update')
            ,handler: this.updateCalendar
        },'-',{
            text: _('churchevents.calendar_remove')
            ,handler: this.removeCalendar
        }];
        this.addContextMenuItem(m);
        
        return true;
    }
    ,updateCalendar: function(btn,e) {
        //console.log('Update');
        if (!this.updateCalendarWindow) {
            this.updateCalendarWindow = MODx.load({
                xtype: 'cmp-window-calendar-update'
                ,record: this.menu.record
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        } else {
            this.updateCalendarWindow.setValues(this.menu.record);
        }
        this.updateCalendarWindow.show(e.target);
    }

    ,removeCalendar: function() {
        MODx.msg.confirm({
            title: _('churchevents.calendar_remove')
            ,text: _('churchevents.calendar_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/calendar/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }
});
Ext.reg('cmp-grid-calendar',Cmp.grid.Calendar);

Cmp.window.UpdateCalendar = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('churchevents.calendar_update')
        ,url: Cmp.config.connectorUrl
        ,baseParams: {
            action: 'mgr/calendar/update'
        }
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('churchevents.calendar_title')
            ,name: 'title'
            ,width: 300
            ,allowBlank : false
        },{
            xtype: 'textarea'
            ,fieldLabel: _('churchevents.calendar_description')
            ,name: 'description'
            ,width: 300
            ,allowBlank : false
        },{
            xtype: 'textfield'
            ,fieldLabel: _('churchevents.calendar_request_notify')
            ,name: 'request_notify'
            ,width: 300
            ,allowBlank : false
        }]
    });
    Cmp.window.UpdateCalendar.superclass.constructor.call(this,config);
};
Ext.extend(Cmp.window.UpdateCalendar,MODx.Window);
Ext.reg('cmp-window-calendar-update',Cmp.window.UpdateCalendar);

Cmp.window.CreateCalendar = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('churchevents.calendar_create')
        ,url: Cmp.config.connectorUrl
        ,baseParams: {
            action: 'mgr/calendar/create'
        }
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('churchevents.calendar_title')
            ,name: 'title'
            ,width: 300
            ,allowBlank : false
        },{
            xtype: 'textarea'
            ,fieldLabel: _('churchevents.calendar_description')
            ,name: 'description'
            ,width: 300
            ,allowBlank : false
        },{
            xtype: 'textfield'
            ,fieldLabel: _('churchevents.calendar_request_notify')
            ,name: 'request_notify'
            ,width: 300
            ,allowBlank : false
        }]
    });
    Cmp.window.CreateCalendar.superclass.constructor.call(this,config);
};
Ext.extend(Cmp.window.CreateCalendar,MODx.Window);
Ext.reg('cmp-window-calendar-create',Cmp.window.CreateCalendar);

/**
 * Categories
 */
Cmp.grid.Category = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'cmp-grid-category'
        ,url: Cmp.config.connectorUrl
        ,baseParams: { action: 'mgr/category/getList' }
        ,save_action: 'mgr/category/updateFromGrid'
        ,fields: ['id','name','background','color']
        ,paging: true
        ,autosave: true
        ,remoteSort: true
        ,anchor: '97%'
        ,autoExpandColumn: 'name'
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,sortable: true
            ,width: 30
        },{
            header: _('churchevents.category_name')
            ,dataIndex: 'name'
            ,sortable: true
            ,width: 150 
            ,allowBlank : false
            ,editor: { xtype: 'textfield' }
        },{
            header: _('churchevents.category_background')
            ,dataIndex: 'background'
            ,sortable: true
            ,width: 100
            ,length: 6
            ,allowBlank : false
            ,editor: { xtype: 'textfield' }
        },{
            header: _('churchevents.category_color')
            ,dataIndex: 'color'
            ,sortable: true
            ,width: 100
            ,length: 6
            ,allowBlank : false
            ,editor: { xtype: 'textfield' }
        }]
        ,tbar: [{
            xtype: 'textfield'
            ,id: 'cmp-search-filter-category'
            ,emptyText: _('churchevents.search...')
            ,listeners: {
                'change': {fn:this.search,scope:this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: function() {
                            this.fireEvent('change',this);
                            this.blur();
                            return true;
                        }
                        ,scope: cmp
                    });
                },scope:this}
            }
        },{
            text: _('churchevents.category_create')
            ,handler: { xtype: 'cmp-window-category-create' ,blankValues: true }
        }]
    });
    Cmp.grid.Category.superclass.constructor.call(this,config);
};

Ext.extend(Cmp.grid.Category,MODx.grid.Grid,{
    search: function(tf,nv,ov) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    ,getMenu: function() {
        var m = [{
            text: _('churchevents.category_update')
            ,handler: this.updateCategory
        },'-',{
            text: _('churchevents.category_remove')
            ,handler: this.removeCategory
        }];
        this.addContextMenuItem(m);
        
        return true;
    }
    ,updateCategory: function(btn,e) {
        //console.log('Update');
        if (!this.updateCategoryWindow) {
            this.updateCategoryWindow = MODx.load({
                xtype: 'cmp-window-category-update'
                ,record: this.menu.record
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        } else {
            this.updateCategoryWindow.setValues(this.menu.record);
        }
        this.updateCategoryWindow.show(e.target);
    }

    ,removeCategory: function() {
        MODx.msg.confirm({
            title: _('churchevents.category_remove')
            ,text: _('churchevents.category_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/category/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }
});
Ext.reg('cmp-grid-category',Cmp.grid.Category);

Cmp.window.UpdateCategory = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('churchevents.category_update')
        ,url: Cmp.config.connectorUrl
        ,baseParams: {
            action: 'mgr/category/update'
        }
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('churchevents.category_name')
            ,name: 'name'
            ,width: 300
            ,allowBlank : false
        },{
            // color picker
            xtype: 'colorpickerfield'//xtype: 'textfield'
            ,fieldLabel: _('churchevents.category_background')
            ,name: 'background'
            ,maxLength: 6
            ,width: 300
            ,allowBlank : false
            ,cls: 'noShadow'
        },{
            // color picker
            xtype: 'colorpickerfield'//xtype: 'textfield'
            ,fieldLabel: _('churchevents.category_color')
            ,name: 'color'
            ,maxLength: 6
            ,width: 300
            ,allowBlank : false
            ,cls: 'noShadow'
        }]
    });
    Cmp.window.UpdateCategory.superclass.constructor.call(this,config);
};
Ext.extend(Cmp.window.UpdateCategory,MODx.Window);
Ext.reg('cmp-window-category-update',Cmp.window.UpdateCategory);

Cmp.window.CreateCategory = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('churchevents.category_create')
        ,url: Cmp.config.connectorUrl
        ,baseParams: {
            action: 'mgr/category/create'
        }
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('churchevents.category_name')
            ,name: 'name'
            ,width: 300
            ,allowBlank : false
        },{
            // color picker
            xtype: 'colorpickerfield'//xtype: 'textfield'
            ,fieldLabel: _('churchevents.category_background')
            ,name: 'background'
            ,maxLength: 6
            ,width: 300
            ,allowBlank : false
            ,cls: 'noShadow'
        },{
            // color picker
            xtype: 'colorpickerfield'//xtype: 'textfield'
            ,fieldLabel: _('churchevents.category_color')
            ,name: 'color'
            ,maxLength: 6
            ,width: 300
            ,allowBlank : false
            ,cls: 'noShadow'
        }]
    });
    Cmp.window.CreateCategory.superclass.constructor.call(this,config);
};
Ext.extend(Cmp.window.CreateCategory,MODx.Window);
Ext.reg('cmp-window-category-create',Cmp.window.CreateCategory);

/**
 * Locations
 * 
 */
/**
 * Example:
 * /
CronManager.SnippetsCombo = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: CronManager.config.connectorUrl,
        baseParams: {
            action: 'mgr/snippets/getlist'
        },
        name: 'snippet',
        hiddenName: 'snippet',
        displayField: 'name',
        valueField: 'id',
        fields: ['id','name'],
        editable: true,
        typeAhead: true,
        minChars: 1,
        pageSize: 20,
        emptyText: _('cronmanager.selectasnippet'),
        forceSelection: true
    });
    CronManager.SnippetsCombo.superclass.constructor.call(this, config);
};
Ext.extend(CronManager.SnippetsCombo, MODx.combo.ComboBox);
Ext.reg('cronmanager-combo-snippets', CronManager.SnippetsCombo);
*/

// the building/location type combo
Cmp.combo.LocationType = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: Cmp.config.connectorUrl
        ,baseParams: { 
            action: 'mgr/locationtype/getList'
           /*,combo: true */ 
        }
        ,name: 'church_location_type_id'
        ,hiddenName: 'church_location_type_id'
        ,displayField: 'name'
        ,valueField: 'id'
        //,value: 0
        ,fields: ['id', 'name', 'notes', 'owner', 'public']
        ,editable: false
        ,typeAhead:true
        ,minChars: 1
        ,pageSize: 20
        ,emptyText: _('churchevents.location_type_select')
        ,forceSelection: true
    });
    Cmp.combo.LocationType.superclass.constructor.call(this,config);
};
//Ext.extend(MODx.combo.SlideStatus, MODx.combo.ComboBox);
Ext.extend(Cmp.combo.LocationType,MODx.combo.ComboBox);
Ext.reg('cmp-combo-locationtype', Cmp.combo.LocationType);

Cmp.combo.LocationTypeFilter = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        url: Cmp.config.connectorUrl
        ,baseParams: { 
            action: 'mgr/locationtype/getList'
            ,showSelect: true
           /*,combo: true */ 
        }
        ,name: 'church_location_type_id'
        ,hiddenName: 'church_location_type_id'
        ,displayField: 'name'
        ,valueField: 'id'
        ,value: 0
        ,fields: ['id', 'name', 'notes', 'owner', 'public']
        ,editable: false
        ,typeAhead:true
        ,minChars: 1
        ,pageSize: 20
        ,emptyText: _('churchevents.location_type_select')
        ,forceSelection: true
        
    });
    Cmp.combo.LocationTypeFilter.superclass.constructor.call(this,config);
};
//Ext.extend(MODx.combo.SlideStatus, MODx.combo.ComboBox);
Ext.extend(Cmp.combo.LocationTypeFilter,MODx.combo.ComboBox);
Ext.reg('cmp-combo-locationtypefilter', Cmp.combo.LocationTypeFilter);


Cmp.grid.Location = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'cmp-grid-location'
        ,url: Cmp.config.connectorUrl
        ,baseParams: { action: 'mgr/location/getList' }
        ,save_action: 'mgr/location/updateFromGrid'
        ,fields: ['id', 'church_location_type_id', 'check_conflict', 'floor', 'name', 'address', 'address2', 'city', 'state', 'zip', 
                'phone', 'toll_free', 'fax', 'website', 'contact_name', 'contact_phone', 'contact_email', 'notes',
                'published', 'map_url', 'owner', 'owner_group']
        ,paging: true
        ,autosave: true
        ,remoteSort: true
        ,anchor: '97%'
        ,autoExpandColumn: 'notes'
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,sortable: true
            ,width: 30
        },{
            header: _('churchevents.location_name')
            ,dataIndex: 'name'
            ,sortable: true
            ,width: 100
            ,allowBlank : false
            ,editor: { xtype: 'textfield' }
        },{
            header: _('churchevents.location_check_conflict')
            ,dataIndex: 'check_conflict'
            ,hiddenName: 'check_conflict'
            ,sortable: true
            ,width: 50
            ,allowBlank : false
            ,editor: { xtype: 'combo-boolean' ,renderer: 'boolean' /*'boolean'*/ }
        },{
            header: _('churchevents.location_floor')
            ,dataIndex: 'floor'
            ,sortable: true
            ,width: 100
            ,allowBlank : false
            ,editor: { xtype: 'textfield' }
        },{
            header: _('churchevents.location_notes')
            ,dataIndex: 'notes'
            ,sortable: true
            ,width: 150
            ,editor: { xtype: 'textarea' }
        }]
        ,tbar: [{
            xtype: 'textfield'
            ,id: 'cmp-search-filter-location'
            ,emptyText: _('churchevents.search...')
            ,listeners: {
                'change': {fn:this.search,scope:this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: function() {
                            this.fireEvent('change',this);
                            this.blur();
                            return true;
                        }
                        ,scope: cmp
                    });
                },scope:this}
            }
        },{
            xtype: 'cmp-combo-locationtypefilter'
            ,name: 'church_location_type_id'
            ,id: 'cmp-locationtype-filter'
            ,width: 250
            ,allowBlank: false
            ,listeners: {
                'select': {fn:this.filterLocations,scope:this}
            }
        },{
            text: _('churchevents.location_create')
            ,handler: { xtype: 'cmp-window-location-create' ,blankValues: true }
        }]
    });
    Cmp.grid.Location.superclass.constructor.call(this,config);
};

Ext.extend(Cmp.grid.Location,MODx.grid.Grid,{
    search: function(tf,nv,ov) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    ,filterLocations: function(cb,nv,ov) {
        cmpTypeId = cb.getValue();
        this.getStore().setBaseParam('church_location_type_id',cmpTypeId);
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    ,getMenu: function() {
        var m = [{
            text: _('churchevents.location_update')
            ,handler: this.updateLocation
        },'-',{
            text: _('churchevents.location_remove')
            ,handler: this.removeLocation
        }];
        this.addContextMenuItem(m);
        
        return true;
    }
    ,updateLocation: function(btn,e) {
        //console.log('Update');
        if (!this.updateLocationWindow) {
            this.updateLocationWindow = MODx.load({
                xtype: 'cmp-window-location-update'
                ,record: this.menu.record
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        } else {
            this.updateLocationWindow.setValues(this.menu.record);
        }
        this.updateLocationWindow.show(e.target);
    }

    ,removeLocation: function() {
        MODx.msg.confirm({
            title: _('churchevents.location_remove')
            ,text: _('churchevents.location_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/location/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }
});
Ext.reg('cmp-grid-location',Cmp.grid.Location);

function getLocationWindowObject(config, type) {
    this.ident = config.ident || 'cmp'+Ext.id();
    var hidden = '';
    if ( type == 'update') {
        hidden = 'id';
    }
    var object = {
        title: _('churchevents.location_'+type)
        ,url: Cmp.config.connectorUrl
        ,baseParams: {
            action: 'mgr/location/' + type
        }
        ,id: this.ident
        ,width: 620
        ,action: type
        ,autoHeight: true
        ,shadow: false
        ,fields: [{
            xtype: 'modx-tabs'
            ,bodyStyle: { background: 'transparent' }
            ,autoHeight: true
            ,deferredRender: false
            ,items: [{
                title: _('churchevents.location_basic_info')
                ,layout: 'form'
                ,cls: 'modx-panel'
                ,bodyStyle: { background: 'transparent', padding: '10px' }
                ,autoHeight: true
                ,labelWidth: 130
                ,items: [
                // basic info
                {
                    xtype: 'hidden'
                    ,name: hidden
                },{
                    fieldLabel: _('churchevents.location_published')
                    ,name: 'published'
                    ,width: 100
                    ,value: 1
                    ,xtype: 'combo-boolean'
                    ,renderer: 'boolean'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('churchevents.location_name')
                    ,name: 'name'
                    ,width: 300
                    ,maxLength: 128
                    ,anchor: '100%'
                    ,allowBlank : false
                },{
                    xtype: 'cmp-combo-locationtype'
                    ,fieldLabel: _('churchevents.location_church_location_type_id')
                    ,name: 'church_location_type_id'
                    ,width: 300
                    ,anchor: '100%'
                    ,allowBlank: false
                    ,id: 'cmp-combo-'+this.ident+'-locationtype'
                    //,renderer: 'int'
                    
                },{
                    fieldLabel: _('churchevents.location_check_conflict')
                    ,name: 'check_conflict'
                    ,width: 100
                    ,value: 1
                    ,xtype: 'combo-boolean'
                    ,renderer: 'boolean'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('churchevents.location_floor')
                    ,name: 'floor'
                    ,width: 80
                },{
                    xtype: 'textarea'
                    ,fieldLabel: _('churchevents.location_notes')
                    ,name: 'notes'
                    ,maxLength: 255
                    ,anchor: '100%'
                    ,width: 300
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('churchevents.location_map_url')
                    ,name: 'map_url'
                    ,maxLength: 255
                    ,anchor: '100%'
                    ,width: 300
                },{
                    xtype: 'modx-combo-user'
                    ,fieldLabel: _('churchevents.location_owner')
                    ,name: 'owner'
                    ,maxLength: 11
                    ,anchor: '100%'
                    ,width: 300
                },{
                    xtype: 'modx-combo-usergroup'
                    ,fieldLabel: _('churchevents.location_owner_group')
                    ,name: 'owner_group'
                    ,maxLength: 11
                    ,anchor: '100%'
                    ,width: 300
                }
                //,MODx.getQRContentField(this.ident,config.record.class_key)
                ]
            },{
                id: 'modx-'+this.ident+'-settings'
                ,title: _('churchevents.location_address_info')
                ,layout: 'form'
                ,cls: 'modx-panel'
                ,autoHeight: true
                ,forceLayout: true
                ,labelWidth: 130
                ,defaults: {autoHeight: true ,border: false}
                ,style: 'background: transparent;'
                ,bodyStyle: { background: 'transparent', padding: '10px' }
                ,items: //MODx.getQRSettings(this.ident,config.record)
                [
                    {
                        xtype: 'textfield'
                        ,fieldLabel: _('churchevents.location_address')
                        ,name: 'address'
                        ,maxLength: 128
                        ,width: 300
                        ,anchor: '100%'
                        ,allowBlank : false
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('churchevents.location_address2')
                        ,name: 'address2'
                        ,maxLength: 128
                        ,anchor: '100%'
                        ,width: 300
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('churchevents.location_city')
                        ,name: 'city'
                        ,maxLength: 128
                        ,width: 300
                        ,anchor: '100%'
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('churchevents.location_state')
                        ,name: 'state'
                        ,maxLength: 2
                        ,width: 300
                        ,anchor: '100%'
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('churchevents.location_zip')
                        ,name: 'zip'
                        ,maxLength: 32
                        ,width: 300
                        ,anchor: '100%'
                    }
                ]
            },{
                /*
                title: _('churchevents.location_basic_info')
                ,layout: 'form'
                ,cls: 'modx-panel'
                ,bodyStyle: { background: 'transparent', padding: '10px' }
                ,autoHeight: true
                ,labelWidth: 130
                ,items: [*/
                id: 'modx-'+this.ident+'-settings2'
                ,title: _('churchevents.location_contact_info')
                ,layout: 'form'
                ,cls: 'modx-panel'
                ,autoHeight: true
                ,forceLayout: true
                ,labelWidth: 130
                ,defaults: {autoHeight: true ,border: false}
                ,style: 'background: transparent;'
                ,bodyStyle: { background: 'transparent', padding: '10px' }
                ,items: //MODx.getQRSettings(this.ident,config.record)
                [
                    {
                        xtype: 'textfield'
                        ,fieldLabel: _('churchevents.location_phone')
                        ,name: 'phone'
                        ,maxLength: 64
                        ,width: 300
                        ,anchor: '100%'
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('churchevents.location_toll_free')
                        ,name: 'toll_free'
                        ,maxLength: 64
                        ,width: 300
                        ,anchor: '100%'
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('churchevents.location_fax')
                        ,name: 'fax'
                        ,maxLength: 64
                        ,width: 300
                        ,anchor: '100%'
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('churchevents.location_website')
                        ,name: 'website'
                        ,maxLength: 128
                        ,width: 300
                        ,anchor: '100%'
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('churchevents.location_contact_name')
                        ,name: 'contact_name'
                        ,maxLength: 128
                        ,width: 300
                        ,anchor: '100%'
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('churchevents.location_contact_phone')
                        ,name: 'contact_phone'
                        ,maxLength: 64
                        ,width: 300
                        ,anchor: '100%'
                    },{
                        xtype: 'textfield'
                        ,fieldLabel: _('churchevents.location_contact_email')
                        ,name: 'contact_email'
                        ,maxLength: 128
                        ,width: 300
                        ,anchor: '100%'
                    }
                ]
            }]
        }]/*
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
        ,buttons: [{
            text: config.cancelBtnText || _('cancel')
            ,scope: this
            ,handler: function() { this.hide(); }
        },{
            text: config.saveBtnText || _('save')
            ,scope: this
            ,handler: function() { this.submit(false); }
        },{
            text: config.saveBtnText || _('save_and_close')
            ,scope: this
            ,handler: this.submit
        }]*/
    };
    /*
    var object = {
        layout: 'card'
        ,activeItem: 0
        ,resizable: true
        ,collapsible: true
        ,maximizable: true
        ,autoHeight: true
        
        ,title: _('churchevents.location_'+type)
        ,url: Cmp.config.connectorUrl
        ,baseParams: {
            action: 'mgr/location/' + type
        }
        ,fields: [
                // basic info
                {
                    xtype: 'hidden'
                    ,name: hidden
                },{
                    fieldLabel: _('churchevents.location_published')
                    ,name: 'published'
                    ,width: 100
                    ,value: 1
                    ,xtype: 'combo-boolean'
                    ,renderer: 'boolean'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('churchevents.location_name')
                    ,name: 'name'
                    ,width: 300
                    ,maxLength: 128
                    ,allowBlank : false
                },{
                    xtype: 'cmp-combo-locationtype'
                    ,fieldLabel: _('churchevents.location_church_location_type_id')
                    ,name: 'church_location_type_id'
                    ,width: 300
                    ,allowBlank: false
                    //,renderer: 'int'
                    
                },{
                    fieldLabel: _('churchevents.location_check_conflict')
                    ,name: 'check_conflict'
                    ,width: 100
                    ,value: 1
                    ,xtype: 'combo-boolean'
                    ,renderer: 'boolean'
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('churchevents.location_floor')
                    ,name: 'floor'
                    ,width: 80
                },{
                    xtype: 'textarea'
                    ,fieldLabel: _('churchevents.location_notes')
                    ,name: 'notes'
                    ,maxLength: 255
                    ,width: 300
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('churchevents.location_map_url')
                    ,name: 'map_url'
                    ,maxLength: 255
                    ,width: 300
                },{
                    xtype: 'modx-combo-user'
                    ,fieldLabel: _('churchevents.location_owner')
                    ,name: 'owner'
                    ,maxLength: 11
                    ,width: 300
                },{
                    xtype: 'modx-combo-usergroup'
                    ,fieldLabel: _('churchevents.location_owner_group')
                    ,name: 'owner_group'
                    ,maxLength: 11
                    ,width: 300
                },
        // tab 2 Addres & Contact Info
        
                {
                    xtype: 'textfield'
                    ,fieldLabel: _('churchevents.location_address')
                    ,name: 'address'
                    ,maxLength: 128
                    ,width: 300
                    ,allowBlank : false
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('churchevents.location_address2')
                    ,name: 'address2'
                    ,maxLength: 128
                    ,width: 300
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('churchevents.location_city')
                    ,name: 'city'
                    ,maxLength: 128
                    ,width: 300
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('churchevents.location_state')
                    ,name: 'state'
                    ,maxLength: 2
                    ,width: 300
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('churchevents.location_zip')
                    ,name: 'zip'
                    ,maxLength: 32
                    ,width: 300
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('churchevents.location_phone')
                    ,name: 'phone'
                    ,maxLength: 64
                    ,width: 300
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('churchevents.location_toll_free')
                    ,name: 'toll_free'
                    ,maxLength: 64
                    ,width: 300
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('churchevents.location_fax')
                    ,name: 'fax'
                    ,maxLength: 64
                    ,width: 300
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('churchevents.location_website')
                    ,name: 'website'
                    ,maxLength: 128
                    ,width: 300
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('churchevents.location_contact_name')
                    ,name: 'contact_name'
                    ,maxLength: 128
                    ,width: 300
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('churchevents.location_contact_phone')
                    ,name: 'contact_phone'
                    ,maxLength: 64
                    ,width: 300
                },{
                    xtype: 'textfield'
                    ,fieldLabel: _('churchevents.location_contact_email')
                    ,name: 'contact_email'
                    ,maxLength: 128
                    ,width: 300
                }]
    };
    */
    return object;
}


Cmp.window.UpdateLocation = function(config) {
    config = config || {};
    locObject = getLocationWindowObject(config, 'update');
    Ext.applyIf(config,locObject);
    Cmp.window.UpdateLocation.superclass.constructor.call(this,config);
};
Ext.extend(Cmp.window.UpdateLocation,MODx.Window);
Ext.reg('cmp-window-location-update',Cmp.window.UpdateLocation);

Cmp.window.CreateLocation = function(config) {
    config = config || {};
    
    locObject = getLocationWindowObject(config, 'create');
    Ext.applyIf(config,locObject);
    /*
    Ext.applyIf(config,{
        title: _('churchevents.location_create')
        ,url: Cmp.config.connectorUrl
        ,baseParams: {
            action: 'mgr/location/create'
        }
        ,fields: [{
            xtype: 'textfield'
            ,fieldLabel: _('churchevents.location_name')
            ,name: 'name'
            ,width: 300
            ,allowBlank : false
        },{
            xtype: 'textfield'
            ,fieldLabel: _('churchevents.location_background')
            ,name: 'background'
            ,maxLength: 6
            ,width: 300
            ,allowBlank : false
        },{
            xtype: 'textfield'
            ,fieldLabel: _('churchevents.location_color')
            ,name: 'color'
            ,maxLength: 6
            ,width: 300
            ,allowBlank : false
        }]
    });
    */
    Cmp.window.CreateLocation.superclass.constructor.call(this,config);
};
Ext.extend(Cmp.window.CreateLocation,MODx.Window);
Ext.reg('cmp-window-location-create',Cmp.window.CreateLocation);

/**
 * Location Type
 */
Cmp.grid.LocationType = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'cmp-grid-locationtype'
        ,url: Cmp.config.connectorUrl
        ,baseParams: { action: 'mgr/locationtype/getList' }
        ,save_action: 'mgr/locationtype/updateFromGrid'
        ,fields: ['id', 'name', 'notes', 'owner', 'public']
        ,paging: true
        ,autosave: true
        ,remoteSort: true
        ,anchor: '97%'
        ,autoExpandColumn: 'notes'
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,sortable: true
            ,width: 30
        },{
            header: _('churchevents.location_type_name')
            ,dataIndex: 'name'
            ,sortable: true
            ,width: 150
            ,allowBlank : false
            ,editor: { xtype: 'textfield' }
        },{
            header: _('churchevents.location_type_public')
            ,dataIndex: 'public'
            ,sortable: true
            ,width: 50
            ,allowBlank : false
            ,editor: { xtype: 'combo-boolean' ,renderer: 'boolean' /*'boolean'*/ }
        },{
            header: _('churchevents.location_type_notes')
            ,dataIndex: 'notes'
            ,sortable: true
            ,width: 300
            ,editor: { xtype: 'textfield' }
        }]
        ,tbar: [{
            xtype: 'textfield'
            ,id: 'cmp-search-filter-locationtype'
            ,emptyText: _('churchevents.search...')
            ,listeners: {
                'change': {fn:this.search,scope:this}
                ,'render': {fn: function(cmp) {
                    new Ext.KeyMap(cmp.getEl(), {
                        key: Ext.EventObject.ENTER
                        ,fn: function() {
                            this.fireEvent('change',this);
                            this.blur();
                            return true;
                        }
                        ,scope: cmp
                    });
                },scope:this}
            }
        },{
            text: _('churchevents.location_type_create')
            ,handler: { xtype: 'cmp-window-locationtype-create' ,blankValues: true }
        }]
    });
    Cmp.grid.LocationType.superclass.constructor.call(this,config);
};

Ext.extend(Cmp.grid.LocationType,MODx.grid.Grid,{
    search: function(tf,nv,ov) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    ,getMenu: function() {
        var m = [{
            text: _('churchevents.location_type_update')
            ,handler: this.updateLocationType
        },'-',{
            text: _('churchevents.location_type_remove')
            ,handler: this.removeLocationType
        }];
        this.addContextMenuItem(m);
        
        return true;
    }
    ,updateLocationType: function(btn,e) {
        //console.log('Update');
        if (!this.updateLocationTypeWindow) {
            this.updateLocationTypeWindow = MODx.load({
                xtype: 'cmp-window-locationtype-update'
                ,record: this.menu.record
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        } else {
            this.updateLocationTypeWindow.setValues(this.menu.record);
        }
        this.updateLocationTypeWindow.show(e.target);
    }

    ,removeLocationType: function() {
        MODx.msg.confirm({
            title: _('churchevents.location_type_remove')
            ,text: _('churchevents.location_type_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/locationtype/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }
});
Ext.reg('cmp-grid-locationtype',Cmp.grid.LocationType);

function getLocationTypeWindowObject(type) {
    var hidden = '';
    if ( type == 'update') {
        hidden = 'id';
    }
    var object = {
        title: _('churchevents.location_type_'+type)
        ,url: Cmp.config.connectorUrl
        ,baseParams: {
            action: 'mgr/locationtype/' + type
        }
        ,fields: [{
            xtype: 'hidden'
            ,name: hidden
        },{
            xtype: 'textfield'
            ,fieldLabel: _('churchevents.location_type_name')
            ,name: 'name'
            ,width: 300
            ,maxLength: 128
            ,allowBlank : false
        },{
            fieldLabel: _('churchevents.location_type_public')
            ,name: 'public'
            ,width: 100
            ,value: 1
            ,xtype: 'combo-boolean'
            ,renderer: 'boolean'
        },{
            xtype: 'textarea'
            ,fieldLabel: _('churchevents.location_type_notes')
            ,name: 'notes'
            ,width: 300
        },{
            xtype: 'textfield'
            ,fieldLabel: _('churchevents.location_type_owner')
            ,name: 'owner'
            ,maxLength: 11
            ,width: 300
        }]
    };
    return object;
}

Cmp.window.UpdateLocationType = function(config) {
    config = config || {};
    locObject = getLocationTypeWindowObject('update');
    Ext.applyIf(config,locObject);
    Cmp.window.UpdateLocationType.superclass.constructor.call(this,config);
};
Ext.extend(Cmp.window.UpdateLocationType,MODx.Window);
Ext.reg('cmp-window-locationtype-update',Cmp.window.UpdateLocationType);

Cmp.window.CreateLocationType = function(config) {
    config = config || {};
    
    locObject = getLocationTypeWindowObject('create');
    Ext.applyIf(config,locObject);
    
    Cmp.window.CreateLocationType.superclass.constructor.call(this,config);
};
Ext.extend(Cmp.window.CreateLocationType,MODx.Window);
Ext.reg('cmp-window-locationtype-create',Cmp.window.CreateLocationType);

