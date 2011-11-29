/* YOU will need to edit this file with proper names, follow the cases(upper/lower) */
Cmp.grid.Suggestions = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'cmp-grid-suggestions'
        ,url: Cmp.config.connectorUrl
        ,baseParams: { action: 'mgr/suggestions/getList' }
        ,save_action: 'mgr/suggestions/updateFromGrid'
        ,fields: ['id','display_title','search_terms','display_message','resource_id','url']
        ,paging: true
        ,autosave: true
        ,remoteSort: true
        ,anchor: '97%'
        //,autoExpandColumn: 'feed'
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,sortable: true
            ,width: 40
        },{
            header: _('suggestedsearch.display_title')
            ,dataIndex: 'display_title'
            ,sortable: true
            ,width: 70 
            ,editor: { xtype: 'textfield' }
        },{
            header: _('suggestedsearch.search_terms')
            ,dataIndex: 'search_terms'
            ,sortable: true
            ,width: 90
            ,editor: { xtype: 'textfield' }
        },{
            header: _('suggestedsearch.display_message')
            ,dataIndex: 'display_message'
            ,sortable: true
            ,width: 100
            ,editor: { xtype: 'textfield' }
        },{
            header: _('suggestedsearch.resource_id')
            ,dataIndex: 'resource_id'
            ,sortable: true
            ,width: 40 
            ,editor: { xtype: 'textfield' }
        },{
            header: _('suggestedsearch.url')
            ,dataIndex: 'url'
            ,sortable: false
            ,width: 100
            ,wrap: true
            ,editor: { xtype: 'textfield' }
        }]
        ,tbar: [{
            xtype: 'textfield'
            ,id: 'cmp-search-filter'
            ,emptyText: _('suggestedsearch.search...')
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
            text: _('suggestedsearch.suggestion_create')
            ,handler: { xtype: 'cmp-window-suggestion-create' ,blankValues: true }
        }]
    });
    Cmp.grid.Suggestions.superclass.constructor.call(this,config);
};

Ext.extend(Cmp.grid.Suggestions,MODx.grid.Grid,{
    search: function(tf,nv,ov) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    ,getMenu: function() {
        var m = [{
            text: _('suggestedsearch.suggestion_update')
            ,handler: this.updateSuggestion
        },'-',{
            text: _('suggestedsearch.suggestion_remove')
            ,handler: this.removeSuggestion
        }];
        this.addContextMenuItem(m);
        
        return true;
    }
    ,updateSuggestion: function(btn,e) {
        console.log('Update');
        if (!this.updateSuggestionWindow) {
            this.updateSuggestionWindow = MODx.load({
                xtype: 'cmp-window-suggestion-update'
                ,record: this.menu.record
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        } else {
            this.updateSuggestionWindow.setValues(this.menu.record);
        }
        this.updateSuggestionWindow.show(e.target);
    }

    ,removeSuggestion: function() {
        MODx.msg.confirm({
            title: _('suggestedsearch.suggestion_remove')
            ,text: _('suggestedsearch.suggestion_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/suggestions/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }
});
Ext.reg('cmp-grid-suggestions',Cmp.grid.Suggestions);


Cmp.window.UpdateSuggestion = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('suggestedsearch.suggestion_update')
        ,url: Cmp.config.connectorUrl
        ,baseParams: {
            action: 'mgr/suggestions/update'
        }
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{ 
            html: _('suggestedsearch.suggestion_update_instructions')+'<br />'
        },{
            xtype: 'textarea'
            ,fieldLabel: _('suggestedsearch.search_terms_desc')
            ,name: 'search_terms'
            ,width: 300
        },{
            xtype: 'textfield'
            ,fieldLabel: _('suggestedsearch.display_title_desc')
            ,name: 'display_title'
            ,width: 300
        },{
            xtype: 'textarea'
            ,fieldLabel: _('suggestedsearch.display_message_desc')
            ,name: 'display_message'
            ,width: 300
        },{
            xtype: 'textfield'
            ,fieldLabel: _('suggestedsearch.resource_id_desc')
            ,name: 'resource_id'
            ,width: 50
        },{
            xtype: 'textfield'
            ,fieldLabel: _('suggestedsearch.url_desc')
            ,name: 'url'
            ,width: 300
        }]
    });
    Cmp.window.UpdateSuggestion.superclass.constructor.call(this,config);
};
Ext.extend(Cmp.window.UpdateSuggestion,MODx.Window);
Ext.reg('cmp-window-suggestion-update',Cmp.window.UpdateSuggestion);

Cmp.window.CreateSuggestion = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('suggestedsearch.suggestion_create')
        ,url: Cmp.config.connectorUrl
        ,baseParams: {
            action: 'mgr/suggestions/create'
        }
        ,fields: [
        { 
            html: _('suggestedsearch.suggestion_create_instructions')+'<br />'
        },{
            xtype: 'textarea'
            ,fieldLabel: _('suggestedsearch.search_terms_desc')
            ,name: 'search_terms'
            ,width: 300
        },{
            xtype: 'textfield'
            ,fieldLabel: _('suggestedsearch.display_title_desc')
            ,name: 'display_title'
            ,width: 300
        },{
            xtype: 'textarea'
            ,fieldLabel: _('suggestedsearch.display_message_desc')
            ,name: 'display_message'
            ,width: 300
        },{
            xtype: 'textfield'
            ,fieldLabel: _('suggestedsearch.resource_id_desc')
            ,name: 'resource_id'
            ,width: 50
        },{
            xtype: 'textfield'
            ,fieldLabel: _('suggestedsearch.url_desc')
            ,name: 'url'
            ,width: 300
        }
        ]
    });
    Cmp.window.CreateSuggestion.superclass.constructor.call(this,config);
};
Ext.extend(Cmp.window.CreateSuggestion,MODx.Window);
Ext.reg('cmp-window-suggestion-create',Cmp.window.CreateSuggestion);

/*****************
 * Report Grid
 *****************/
Cmp.grid.Report = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'cmp-grid-report'
        ,url: Cmp.config.connectorUrl
        ,baseParams: { action: 'mgr/report/getList' }
        ,fields: ['id','term','parent','url', 'total','search_term_id','search_time', 'type','total_period', 'total_search', 'total_suggested', 'total_suggestedPage']
        ,paging: true
        ,autosave: true
        ,remoteSort: true
        ,anchor: '97%'
        //,autoExpandColumn: 'feed'
        ,columns: [{
            header: _('suggestedsearch.search_terms')
            ,dataIndex: 'term'
            ,sortable: true
            ,width: 40 
            //,editor: { xtype: 'textfield' }
        },{
            header: _('suggestedsearch.report_url')
            ,renderer: function(value, cell) {
                if ( value.length > 2 ) {
                    return '<a href="'+value+'" target="_blank">'+value+'</a>';
                } else {
                    return '-';
                }
            }
            ,dataIndex: 'url'
            ,sortable: false
            ,width: 30
        },{
            header: _('suggestedsearch.report_total_period')
            ,dataIndex: 'total_period'
            ,sortable: true
            ,width: 15
            //,editor: { xtype: 'textfield' }
        },{
            header: _('suggestedsearch.report_total_search')
            ,dataIndex: 'total_search'
            ,sortable: false
            ,width: 15
            //,editor: { xtype: 'textfield' }
        },{
            header: _('suggestedsearch.report_total_suggested')
            ,dataIndex: 'total_suggested'
            ,sortable: false
            ,width: 15
            //,editor: { xtype: 'textfield' }
        },{
            header: _('suggestedsearch.report_total_suggestedPage')
            ,dataIndex: 'total_suggestedPage'
            ,sortable: false
            ,width: 15
            //,editor: { xtype: 'textfield' }
        },{
            header: _('suggestedsearch.report_total')
            ,dataIndex: 'total'
            ,sortable: true
            ,width: 15
            //,editor: { xtype: 'textfield' }
        }]
        ,tbar: [{
            xtype: 'textfield'
            ,id: 'cmp-search-report'
            ,emptyText: _('suggestedsearch.search...')
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
            xtype: 'textfield'
            ,id: 'cmp-startdate-report'
            ,emptyText: _('suggestedsearch.start_date')
            ,listeners: {
                'change': {fn:this.filterStartDate,scope:this}
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
            xtype: 'textfield'
            ,id: 'cmp-enddate-report'
            ,emptyText: _('suggestedsearch.end_date')
            ,listeners: {
                'change': {fn:this.filterEndDate,scope:this}
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
        }]
    });
    Cmp.grid.Report.superclass.constructor.call(this,config);
};

Ext.extend(Cmp.grid.Report,MODx.grid.Grid,{
    search: function(tf,nv,ov) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    ,filterStartDate: function(cb,nv,ov) {
        //cmpAlbumId = cb.getValue();
        this.getStore().setBaseParam('start_date',cb.getValue());
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    ,filterEndDate: function(cb,nv,ov) {
        this.getStore().setBaseParam('end_date',cb.getValue());
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    ,getMenu: function() {
        var m = [/*{
            text: _('suggestedsearch.suggestion_update')
            ,handler: this.updateSuggestion
        },'-',{
            text: _('suggestedsearch.suggestion_remove')
            ,handler: this.removeSuggestion
        }*/];
        this.addContextMenuItem(m);
        
        return true;
    }
    ,updateSuggestion: function(btn,e) {
        console.log('Update');
        if (!this.updateSuggestionWindow) {
            this.updateSuggestionWindow = MODx.load({
                xtype: 'cmp-window-suggestion-update'
                ,record: this.menu.record
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        } else {
            this.updateSuggestionWindow.setValues(this.menu.record);
        }
        this.updateSuggestionWindow.show(e.target);
    }
});
Ext.reg('cmp-grid-report',Cmp.grid.Report);

