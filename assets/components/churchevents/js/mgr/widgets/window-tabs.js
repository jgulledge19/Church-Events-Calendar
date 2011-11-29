MODx.window.QuickCreateResource = function(config) {
    config = config || {};
    this.ident = config.ident || 'qcr'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_create_resource')
        ,id: this.ident
        ,width: 620
        ,url: MODx.config.connectors_url+'resource/index.php'
        ,action: 'create'
        ,shadow: false
        ,fields: [{
            xtype: 'modx-tabs'
            ,bodyStyle: { background: 'transparent' }
            ,deferredRender: false
            ,autoHeight: true
            ,items: [{
                title: _('resource')
                ,layout: 'form'
                ,cls: 'modx-panel'
                ,bodyStyle: { background: 'transparent', padding: '10px' }
                ,autoHeight: true
                ,labelWidth: 100
                ,items: [{
                    xtype: 'modx-combo-template'
                    ,name: 'template'
                    ,id: 'modx-'+this.ident+'-template'
                    ,fieldLabel: _('template')
                    ,editable: false
                    ,anchor: '100%'
                    ,baseParams: {
                        action: 'getList'
                        ,combo: '1'
                    }
                    ,value: MODx.config.default_template
                },{
                    xtype: 'textfield'
                    ,name: 'pagetitle'
                    ,id: 'modx-'+this.ident+'-pagetitle'
                    ,fieldLabel: _('pagetitle')
                    ,anchor: '100%'
                },{
                    xtype: 'textfield'
                    ,name: 'longtitle'
                    ,id: 'modx-'+this.ident+'-longtitle'
                    ,fieldLabel: _('long_title')
                    ,anchor: '100%'
                },{
                    xtype: 'textarea'
                    ,name: 'description'
                    ,id: 'modx-'+this.ident+'-description'
                    ,fieldLabel: _('description')
                    ,anchor: '100%'
                    ,grow: false
                    ,height: 50
                },{
                    xtype: 'textfield'
                    ,name: 'alias'
                    ,id: 'modx-'+this.ident+'-alias'
                    ,fieldLabel: _('alias')
                    ,anchor: '100%'
                },{
                    xtype: 'textarea'
                    ,name: 'introtext'
                    ,id: 'modx-'+this.ident+'-introtext'
                    ,fieldLabel: _('introtext')
                    ,anchor: '100%'
                    ,height: 50
                },{
                    xtype: 'textfield'
                    ,name: 'menutitle'
                    ,id: 'modx-'+this.ident+'-menutitle'
                    ,fieldLabel: _('resource_menutitle')
                    ,anchor: '100%'
                },
                MODx.getQRContentField(this.ident,config.record.class_key)]
            },{
                id: 'modx-'+this.ident+'-settings'
                ,title: _('settings')
                ,layout: 'form'
                ,cls: 'modx-panel'
                ,autoHeight: true
                ,forceLayout: true
                ,labelWidth: 100
                ,defaults: {autoHeight: true ,border: false}
                ,style: 'background: transparent;'
                ,bodyStyle: { background: 'transparent', padding: '10px' }
                ,items: MODx.getQRSettings(this.ident,config.record)
            }]
        }]
       ,keys: [{
            key: Ext.EventObject.ENTER
            ,shift: true
            ,fn: this.submit
            ,scope: this
        }]
    });
    MODx.window.QuickCreateResource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickCreateResource,MODx.Window);
Ext.reg('modx-window-quick-create-modResource',MODx.window.QuickCreateResource);

MODx.window.QuickUpdateResource = function(config) {
    config = config || {};
    this.ident = config.ident || 'qur'+Ext.id();
    Ext.applyIf(config,{
        title: _('quick_update_resource')
        ,id: this.ident
        ,width: 620
        ,url: MODx.config.connectors_url+'resource/index.php'
        ,action: 'update'
        ,autoHeight: true
        ,shadow: false
        ,fields: [{
            xtype: 'modx-tabs'
            ,bodyStyle: { background: 'transparent' }
            ,autoHeight: true
            ,deferredRender: false
            ,items: [{
                title: _('resource')
                ,layout: 'form'
                ,cls: 'modx-panel'
                ,bodyStyle: { background: 'transparent', padding: '10px' }
                ,autoHeight: true
                ,labelWidth: 100
                ,items: [{
                    xtype: 'hidden'
                    ,name: 'id'
                    ,id: 'modx-'+this.ident+'-id'
                },{
                    xtype: 'modx-combo-template'
                    ,name: 'template'
                    ,id: 'modx-'+this.ident+'-template'
                    ,fieldLabel: _('template')
                    ,editable: false
                    ,anchor: '100%'
                    ,baseParams: {
                        action: 'getList'
                        ,combo: '1'
                    }
                },{
                    xtype: 'textfield'
                    ,name: 'pagetitle'
                    ,id: 'modx-'+this.ident+'-pagetitle'
                    ,fieldLabel: _('pagetitle')
                    ,anchor: '100%'
                },{
                    xtype: 'textfield'
                    ,name: 'longtitle'
                    ,id: 'modx-'+this.ident+'-longtitle'
                    ,fieldLabel: _('long_title')
                    ,anchor: '100%'
                },{
                    xtype: 'textarea'
                    ,name: 'description'
                    ,id: 'modx-'+this.ident+'-description'
                    ,fieldLabel: _('description')
                    ,anchor: '100%'
                    ,grow: false
                    ,height: 50
                },{
                    xtype: 'textfield'
                    ,name: 'alias'
                    ,id: 'modx-'+this.ident+'-alias'
                    ,fieldLabel: _('alias')
                    ,anchor: '100%'
                },{
                    xtype: 'textfield'
                    ,name: 'menutitle'
                    ,id: 'modx-'+this.ident+'-menutitle'
                    ,fieldLabel: _('resource_menutitle')
                    ,anchor: '100%'
                },{
                    xtype: 'textarea'
                    ,name: 'introtext'
                    ,id: 'modx-'+this.ident+'-introtext'
                    ,fieldLabel: _('introtext')
                    ,anchor: '100%'
                    ,height: 50
                },
                MODx.getQRContentField(this.ident,config.record.class_key)]
            },{
                id: 'modx-'+this.ident+'-settings'
                ,title: _('settings'),layout: 'form'
                ,cls: 'modx-panel'
                ,autoHeight: true
                ,forceLayout: true
                ,labelWidth: 100
                ,defaults: {autoHeight: true ,border: false}
                ,style: 'background: transparent;'
                ,bodyStyle: { background: 'transparent', padding: '10px' }
                ,items: MODx.getQRSettings(this.ident,config.record)
            }]
        }]
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
        }]
    });
    MODx.window.QuickUpdateResource.superclass.constructor.call(this,config);
};
Ext.extend(MODx.window.QuickUpdateResource,MODx.Window);
Ext.reg('modx-window-quick-update-modResource',MODx.window.QuickUpdateResource);


MODx.getQRContentField = function(id,cls) {
    id = id || 'qur';
    cls = cls || 'modResource';    
    var o = {};
    switch (cls) {
        case 'modSymLink':
            o = {
                xtype: 'textfield'
                ,fieldLabel: _('symlink')
                ,name: 'content'
                ,id: 'modx-'+id+'-content'
                ,anchor: '100%'
                ,maxLength: 255
                ,allowBlank: false
            };
            break;
        case 'modWebLink':
            o = {
                xtype: 'textfield'
                ,fieldLabel: _('weblink')
                ,name: 'content'
                ,id: 'modx-'+id+'-content'
                ,anchor: '100%'
                ,maxLength: 255
                ,value: 'http://'
                ,allowBlank: false
            };
            break;
        case 'modStaticResource':
            o = {
                xtype: 'modx-combo-browser'
                ,browserEl: 'modx-browser'
                ,prependPath: false
                ,prependUrl: false
                ,hideFiles: true
                ,fieldLabel: _('static_resource')
                ,name: 'content'
                ,id: 'modx-'+id+'-content'
                ,anchor: '100%'
                ,maxLength: 255
                ,value: ''
                ,listeners: {
                    'select':{fn:function(data) {
                        if (data.url.substring(0,1) == '/') {
                            Ext.getCmp('modx-'+id+'-content').setValue(data.url.substring(1));
                        }   
                    },scope:this}
                }
            };
            break;
        case 'modResource':
        default:
            o = {
                xtype: 'textarea'
                ,name: 'content'
                ,id: 'modx-'+id+'-content'
                ,hideLabel: true
                ,labelSeparator: ''
                ,anchor: '100%'
                ,height: 300
            };
            break;
    }
    return o;
};

MODx.getQRSettings = function(id,va) {
    id = id || 'qur';
    return [{
        xtype: 'hidden'
        ,name: 'parent'
        ,id: 'modx-'+id+'-parent'
        ,value: va['parent']
    },{
        xtype: 'hidden'
        ,name: 'context_key'
        ,id: 'modx-'+id+'-context_key'
        ,value: va['context_key']
    },{
        xtype: 'hidden'
        ,name: 'class_key'
        ,id: 'modx-'+id+'-class_key'
        ,value: va['class_key']
    },{
        xtype: 'hidden'
        ,name: 'publishedon'
        ,id: 'modx-'+id+'-publishedon'
        ,value: va['publishedon']
    },{
        xtype: 'xcheckbox'
        ,name: 'published'
        ,id: 'modx-'+id+'-published'
        ,fieldLabel: _('resource_published')
        ,description: _('resource_published_help')
        ,inputValue: 1
        ,checked: va['published'] !== undefined ? va['published'] : (MODx.config.publish_default == '1' ? 1 : 0)
    },{
        xtype: 'xcheckbox'
        ,fieldLabel: _('resource_folder')
        ,description: _('resource_folder_help')
        ,name: 'isfolder'
        ,id: 'modx-'+id+'-isfolder'
        ,inputValue: 1
        ,checked: va['isfolder'] != undefined ? va['isfolder'] : false
    },{
        xtype: 'xcheckbox'
        ,fieldLabel: _('resource_richtext')
        ,description: _('resource_richtext_help')
        ,name: 'richtext'
        ,id: 'modx-'+id+'-richtext'
        ,inputValue: 1
        ,checked: va['richtext'] !== undefined ? (va['richtext'] ? 1 : 0) : (MODx.config.richtext_default == '1' ? 1 : 0)
    },{
        xtype: 'xcheckbox'
        ,fieldLabel: _('resource_searchable')
        ,description: _('resource_searchable_help')
        ,name: 'searchable'
        ,id: 'modx-'+id+'-searchable'
        ,inputValue: 1
        ,checked: va['searchable'] != undefined ? va['searchable'] : (MODx.config.search_default == '1' ? 1 : 0)
        ,listeners: {'check': {fn:MODx.handleQUCB}}
    },{
        xtype: 'xcheckbox'
        ,fieldLabel: _('resource_hide_from_menus')
        ,description: _('resource_hide_from_menus_help')
        ,name: 'hidemenu'
        ,id: 'modx-'+id+'-hidemenu'
        ,inputValue: 1
        ,checked: va['hidemenu'] != undefined ? va['hidemenu'] : (MODx.config.hidemenu_default == '1' ? 1 : 0)
    },{
        xtype: 'xcheckbox'
        ,fieldLabel: _('resource_cacheable')
        ,description: _('resource_cacheable_help')
        ,name: 'cacheable'
        ,id: 'modx-'+id+'-cacheable'
        ,inputValue: 1
        ,checked: va['cacheable'] != undefined ? va['cacheable'] : (MODx.config.cache_default == '1' ? 1 : 0)
    },{
        xtype: 'xcheckbox'
        ,name: 'clearCache'
        ,id: 'modx-'+id+'-clearcache'
        ,fieldLabel: _('clear_cache_on_save')
        ,description: _('clear_cache_on_save_msg')
        ,inputValue: 1
        ,checked: true
    }];
};
MODx.handleQUCB = function(cb) {
    var h = Ext.getCmp(cb.id+'-hd');
    if (cb.checked && h) {
        cb.setValue(1);
        h.setValue(1);
    } else if (h) {
        cb.setValue(0);
        h.setValue(0);
    }
}