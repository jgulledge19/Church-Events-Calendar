/* YOU will need to edit this file with proper names, follow the cases(upper/lower) */
Cmp.panel.Home = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,baseCls: 'modx-formpanel'
        ,items: [{
            html: '<h2>'+_('churchevents.management')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        },{
            xtype: 'modx-tabs'
            ,bodyStyle: 'padding: 10px'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            ,items: [{
                title: _('churchevents.calendar_tab')
                ,defaults: { autoHeight: true }
                ,items: [{
                    html: '<p>'+_('churchevents.calendar_desc')+'</p><br />'
                    ,border: false
                },{
                    xtype: 'cmp-grid-calendar'
                    ,preventRender: true
                }]
            },{
                title: _('churchevents.category_tab')
                ,defaults: { autoHeight: true }
                ,items: [{
                    html: '<p>'+_('churchevents.category_desc')+'</p><br />'
                    ,border: false
                },{
                    xtype: 'cmp-grid-category'
                    ,preventRender: true
                }]
            },{
                title: _('churchevents.location_tab')
                ,defaults: { autoHeight: true }
                ,items: [{
                    html: '<p>'+_('churchevents.location_desc')+'</p><br />'
                    ,border: false
                },{
                    xtype: 'cmp-grid-location'
                    ,preventRender: true
                }]
            },{
                title: _('churchevents.location_type_tab')
                ,defaults: { autoHeight: true }
                ,items: [{
                    html: '<p>'+_('churchevents.location_type_desc')+'</p><br />'
                    ,border: false
                },{
                    xtype: 'cmp-grid-locationtype'
                    ,preventRender: true
                }]
            }]
        }]
    });
    Cmp.panel.Home.superclass.constructor.call(this,config);
};

Ext.extend(Cmp.panel.Home,MODx.Panel);
Ext.reg('cmp-panel-home',Cmp.panel.Home);
