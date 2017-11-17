//自定义按钮集合

//阿里云视频功能按钮
UE.registerUI('dialogali',function(editor,uiName){	
    //创建dialog
    var dialog = new UE.ui.Dialog({
        //指定弹出层中页面的路径，这里只能支持页面,因为跟addCustomizeDialog.js相同目录，所以无需加路径
        iframeUrl:'/ueditor/dialogs/edittool/alivideo.html',
        //需要指定当前的编辑器实例
        editor:editor,
        //指定dialog的名字
        name:uiName,
        //dialog的标题
        title:"添加阿里云视频",
        //指定dialog的外围样式
        cssRules:"width:600px;height:300px;",
        //如果给出了buttons就代表dialog有确定和取消
        buttons:[
            {
                className:'edui-okbutton',
                label:'确定',
                onclick:function () {
                    dialog.close(true);
                }
            },
            {
                className:'edui-cancelbutton',
                label:'取消',
                onclick:function () {
                    dialog.close(false);
                }
            }
        ]});

    //参考addCustomizeButton.js
    var btn = new UE.ui.Button({
        name:'dialogbutton' + uiName,
        title:'添加阿里云视频',
        //需要添加的额外样式，指定icon图标，这里默认使用一个重复的icon
        //cssRules :'background-position: -500px 0;',
        cssRules :'background-image:url(/ueditor/themes/default/images/video.png)  !important;background-size:20px;',
        //cssRules :'background-position: -380px 0;',
        onclick:function () {
            //渲染dialog
            dialog.render();
            dialog.open();
        }
    });
    return btn;
}/*index 指定添加到工具栏上的那个位置，默认时追加到最后,editorId 指定这个UI是那个编辑器实例上的，默认是页面上所有的编辑器都会添加这个按钮*/);




//音频功能按钮
UE.registerUI('dialogaudio',function(editor,uiName){  
    //创建dialog
    var dialog = new UE.ui.Dialog({
        //指定弹出层中页面的路径，这里只能支持页面,因为跟addCustomizeDialog.js相同目录，所以无需加路径
        iframeUrl:'/ueditor/dialogs/edittool/audio.html',
        //需要指定当前的编辑器实例
        editor:editor,
        //指定dialog的名字
        name:uiName,
        //dialog的标题
        title:"添加音频",
        //指定dialog的外围样式
        cssRules:"width:600px;height:300px;",
        //如果给出了buttons就代表dialog有确定和取消
        buttons:[
            {
                className:'edui-okbutton',
                label:'确定',
                onclick:function () {
                    dialog.close(true);
                }
            },
            {
                className:'edui-cancelbutton',
                label:'取消',
                onclick:function () {
                    dialog.close(false);
                }
            }
        ]});

    //参考addCustomizeButton.js
    var btn = new UE.ui.Button({
        name:'dialogbutton' + uiName,
        title:'添加音频',
        //需要添加的额外样式，指定icon图标，这里默认使用一个重复的icon
        //cssRules :'background-position: -500px 0;',
        cssRules :'background-image:url(/ueditor/themes/default/images/audio.png)  !important;background-size:20px;',
        //cssRules :'background-position: -380px 0;',
        onclick:function () {
            //渲染dialog
            dialog.render();
            dialog.open();
        }
    });
    return btn;
}/*index 指定添加到工具栏上的那个位置，默认时追加到最后,editorId 指定这个UI是那个编辑器实例上的，默认是页面上所有的编辑器都会添加这个按钮*/);





//模板按钮
UE.registerUI('dialog_temple',function(editor,uiName){	
    //创建dialog
    var dialog = new UE.ui.Dialog({
        //指定弹出层中页面的路径，这里只能支持页面,因为跟addCustomizeDialog.js相同目录，所以无需加路径
        iframeUrl:'/ueditor/dialogs/edittool/temple.html',
        //需要指定当前的编辑器实例
        editor:editor,
        //指定dialog的名字
        name:uiName,
        //dialog的标题
        title:"选择内容模板",
        //指定dialog的外围样式
        cssRules:"width:600px;height:300px;",
        //如果给出了buttons就代表dialog有确定和取消
        // buttons:[
        //     {
        //         className:'edui-okbutton',
        //         label:'确定',
        //         onclick:function () {
        //             dialog.close(true);
        //         }
        //     },
        //     {
        //         className:'edui-cancelbutton',
        //         label:'取消',
        //         onclick:function () {
        //             dialog.close(false);
        //         }
        //     }
        // ]
        });

    //参考addCustomizeButton.js
    var btn = new UE.ui.Button({
        name:'dialogbutton' + uiName,
        title:'选择内容模板',
        //需要添加的额外样式，指定icon图标，这里默认使用一个重复的icon
        //cssRules :'background-position: -500px 0;',
        cssRules :'background-image:url(/ueditor/themes/default/images/temple.png)  !important;background-size:20px;',
        //cssRules :'background-position: -380px 0;',
        onclick:function () {
            //渲染dialog
            dialog.render();
            dialog.open();
        }
    });

    return btn;
}/*index 指定添加到工具栏上的那个位置，默认时追加到最后,editorId 指定这个UI是那个编辑器实例上的，默认是页面上所有的编辑器都会添加这个按钮*/);

//选择标题
UE.registerUI('dialog_title',function(editor,uiName){	
    //创建dialog
    var dialog = new UE.ui.Dialog({
        //指定弹出层中页面的路径，这里只能支持页面,因为跟addCustomizeDialog.js相同目录，所以无需加路径
        iframeUrl:'/ueditor/dialogs/edittool/title.html',
        //需要指定当前的编辑器实例
        editor:editor,
        //指定dialog的名字
        name:uiName,
        //dialog的标题
        title:"选择标题",
        //指定dialog的外围样式
        cssRules:"width:600px;height:300px;",
        //如果给出了buttons就代表dialog有确定和取消
        // buttons:[
        //     // {
        //     //     // // className:'edui-okbutton',
        //     //     // // label:'确定',
        //     //     // onclick:function (title) {
        //     //     //     dialog.close(true);
        //     //     // }
        //     // }
        //     // ,{
        //     //     className:'edui-cancelbutton',
        //     //     label:'取消',
        //     //     onclick:function () {
        //     //         dialog.close(false);
        //     //     }
        //     // }
        // ]
        });

    //参考addCustomizeButton.js
    var btn = new UE.ui.Button({
        name:'dialogbutton' + uiName,
        title:'选择标题',
        //需要添加的额外样式，指定icon图标，这里默认使用一个重复的icon
        //cssRules :'background-position: -500px 0;',
        cssRules :'background-image:url(/ueditor/themes/default/images/title.png)  !important;background-size:20px;',
        //cssRules :'background-position: -380px 0;',
        onclick:function () {
            //渲染dialog
            dialog.render();
            dialog.open();
        }
    });
    return btn;
}/*index 指定添加到工具栏上的那个位置，默认时追加到最后,editorId 指定这个UI是那个编辑器实例上的，默认是页面上所有的编辑器都会添加这个按钮*/);


//选择分割线
UE.registerUI('dialog_rules',function(editor,uiName){   
    //创建dialog
    var dialog = new UE.ui.Dialog({
        //指定弹出层中页面的路径，这里只能支持页面,因为跟addCustomizeDialog.js相同目录，所以无需加路径
        iframeUrl:'/ueditor/dialogs/edittool/rules.html',
        //需要指定当前的编辑器实例
        editor:editor,
        //指定dialog的名字
        name:uiName,
        //dialog的标题
        title:"选择分割线",
        //指定dialog的外围样式
        cssRules:"width:600px;height:300px;",
        //如果给出了buttons就代表dialog有确定和取消
        // buttons:[
        //     // {
        //     //     // // className:'edui-okbutton',
        //     //     // // label:'确定',
        //     //     // onclick:function (title) {
        //     //     //     dialog.close(true);
        //     //     // }
        //     // }
        //     // ,{
        //     //     className:'edui-cancelbutton',
        //     //     label:'取消',
        //     //     onclick:function () {
        //     //         dialog.close(false);
        //     //     }
        //     // }
        // ]
        });

    //参考addCustomizeButton.js
    var btn = new UE.ui.Button({
        name:'dialogbutton' + uiName,
        title:'选择分割线',
        //需要添加的额外样式，指定icon图标，这里默认使用一个重复的icon
        //cssRules :'background-position: -500px 0;',
        cssRules :'background-image:url(/ueditor/themes/default/images/rules.png)  !important;background-size:20px;',
        //cssRules :'background-position: -380px 0;',
        onclick:function () {
            //渲染dialog
            dialog.render();
            dialog.open();
        }
    });
    return btn;
}/*index 指定添加到工具栏上的那个位置，默认时追加到最后,editorId 指定这个UI是那个编辑器实例上的，默认是页面上所有的编辑器都会添加这个按钮*/);