/* make a local <select>/combo box */
Cmp.combo.FeedStatus = function(config) {
    config = config || {};
    Ext.applyIf(config,{
       //displayField: 'name'
        //,valueField: 'id'
        //,fields: ['id', 'name']
        store: ['approved','hidden','pending','auto_approved']
        //,url: Testapp.config.connectorUrl
        ,baseParams: { action: '' ,combo: true }
        //,mode: 'local'
        ,editable: false
    });
    Cmp.combo.FeedStatus.superclass.constructor.call(this,config);
};
//Ext.extend(MODx.combo.FeedStatus, MODx.combo.ComboBox);
Ext.extend(Cmp.combo.FeedStatus,MODx.combo.ComboBox);
Ext.reg('feedstatus-combo', Cmp.combo.FeedStatus);


/* YOU will need to edit this file with proper names, follow the cases(upper/lower) */
Cmp.grid.Cmp = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'cmp-grid-cmp'
        ,url: Cmp.config.connectorUrl
        ,baseParams: { action: 'mgr/cmp/getList' }
        ,save_action: 'mgr/cmp/updateFromGrid'
        ,fields: ['id','service','username','post_date','status','feed']
        ,paging: true
        ,autosave: true
        ,remoteSort: true
        ,anchor: '97%'
        ,autoExpandColumn: 'feed'
        ,columns: [{
            header: _('id')
            ,dataIndex: 'id'
            ,sortable: true
            ,width: 30
        },{
            header: _('cmp.service')
            ,dataIndex: 'service'
            ,sortable: true
            ,width: 45 /* NOT Editiable
            ,editor: { xtype: 'textfield' } */
        },{
            header: _('cmp.username')
            ,dataIndex: 'username'
            ,sortable: true
            ,width: 65 /* NOT Editiable
            ,editor: { xtype: 'textfield' } */
        },{
            header: _('cmp.post_date')
            ,dataIndex: 'post_date'
            ,sortable: true
            ,width: 60/*
            //,name: 'post_date'
            ,hiddenName: 'post_date'
            ,anchor: '90%'
            ,dateFormat: MODx.config.manager_date_format
            ,timeFormat: MODx.config.manager_time_format
            ,dateWidth: 120
            ,timeWidth: 120*/
        },{
            header: _('cmp.status')
            ,dataIndex: 'status'
            ,sortable: true
            ,width: 60 
            ,editor: //{ xtype: 'textfield' } 
            { xtype: 'feedstatus-combo', renderer: 'value' }
            //{ xtype: 'combo-boolean', renderer: true, mode: 'local', feilds:[ 'approved', 'pending', 'auto_approved',] }
            // ,editor: { xtype: 'combo-boolean' ,renderer: 'boolean' }
        },{
            header: _('cmp.feed')
            ,dataIndex: 'feed'
            ,sortable: false
            ,width: 200
            ,wrap: true /* NOT Editiable?
            ,editor: { xtype: 'textfield' } */
        }]
        ,tbar: [{
            xtype: 'textfield'
            ,id: 'cmp-search-filter'
            ,emptyText: _('cmp.search...')
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
            text: _('cmp.get_lastest')
            ,handler: { xtype: 'cmp-window-get-latest' ,blankValues: true }
        }]
    });
    Cmp.grid.Cmp.superclass.constructor.call(this,config);
};

Ext.extend(Cmp.grid.Cmp,MODx.grid.Grid,{
    search: function(tf,nv,ov) {
        var s = this.getStore();
        s.baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
    ,getMenu: function() {
        var m = [{
            text: _('cmp.feed_update')
            ,handler: this.updateFeed
        },'-',{
            text: _('cmp.feed_remove')
            ,handler: this.removeFeed
        }];
        this.addContextMenuItem(m);
        
        return true;
    }
    ,updateFeed: function(btn,e) {
        console.log('Update');
        if (!this.updateFeedWindow) {
            this.updateFeedWindow = MODx.load({
                xtype: 'cmp-window-cmp-update'
                ,record: this.menu.record
                ,listeners: {
                    'success': {fn:this.refresh,scope:this}
                }
            });
        } else {
            this.updateFeedWindow.setValues(this.menu.record);
        }
        this.updateFeedWindow.show(e.target);
    }

    ,removeFeed: function() {
        MODx.msg.confirm({
            title: _('cmp.feed_remove')
            ,text: _('cmp.feed_remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/cmp/remove'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:this.refresh,scope:this}
            }
        });
    }
});
Ext.reg('cmp-grid-cmp',Cmp.grid.Cmp);


Cmp.window.UpdateFeed = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('cmp.feed_update')
        ,url: Cmp.config.connectorUrl
        ,baseParams: {
            action: 'mgr/cmp/update'
        }
        ,fields: [{
            xtype: 'hidden'
            ,name: 'id'
        },{
            xtype: 'textfield'
            ,fieldLabel: _('cmp.username')
            ,name: 'username'
            ,width: 300
            ,disable: true
            ,editable: false
        },{
            //xtype: 'textfield'
            xtype: 'feedstatus-combo'
            ,renderer: true
            ,fieldLabel: _('cmp.status')
            ,name: 'status'
            ,width: 150
        },{
            xtype: 'textarea'
            ,fieldLabel: _('cmp.feed')
            ,name: 'feed'
            ,width: 300
            ,readonly: true
            ,disable: true
            ,editable: false
        }]
    });
    Cmp.window.UpdateFeed.superclass.constructor.call(this,config);
};
Ext.extend(Cmp.window.UpdateFeed,MODx.Window);
Ext.reg('cmp-window-cmp-update',Cmp.window.UpdateFeed);

Cmp.window.GetLatestPosts = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        title: _('cmp.feed_get_latest')
        ,url: Cmp.config.connectorUrl
        ,baseParams: {
            action: 'mgr/cmp/getlatest'
        }
        ,fields: [
        { 
            html: _('cmp.feed_get_latest_desc')+'<br />'
        }/*,{
            xtype: 'textfield'
            ,fieldLabel: 
            ,name: 'username'
            ,width: 0
        }*//* maybe have a list of user accounts to choose for and/or services? */
        ]
    });
    Cmp.window.GetLatestPosts.superclass.constructor.call(this,config);
};
Ext.extend(Cmp.window.GetLatestPosts,MODx.Window);
Ext.reg('cmp-window-get-latest',Cmp.window.GetLatestPosts);
