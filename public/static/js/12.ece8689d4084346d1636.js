webpackJsonp([12,22],{222:function(e,t,a){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={data:function(){return{self:this,userPanel:!1,formInline:{field:"id",value:"",thisGid:0,thisStatus:1},handleUser:{},groupList:[{gid:1,name:"超级管理员"},{gid:2,name:"编辑"},{gid:3,name:"会员"}],statusList:[{status:-1,name:"所有状态"},{status:0,name:"锁定"},{status:1,name:"正常"},{status:2,name:"删除"}],list:{field:[{title:"编号",key:"id",align:"center",width:"80px"},{title:"用户名",key:"username"},{title:"别称",key:"alias"},{title:"注册时间",key:"create_at"},{title:"用户组",key:"group_name"},{title:"状态",key:"status_name"},{title:"操作",key:"action",align:"center",render:function(e,t,a){return'<i-button type="info" size="small" @click.native="showEdit('+a+')">操作</i-button> <i-button type="error" size="small" @click.native="showDelete('+a+')">删除</i-button>'}}],data:[{id:1,username:"zhangsan",alias:"张三",create_at:"2017-03-01 12:12",group_id:3,group_name:"会员",status:1},{id:2,username:"zhangsan",alias:"张三",create_at:"2017-03-01 12:12",group_id:2,group_name:"会员",status:1},{id:3,username:"zhangsan",alias:"张三",create_at:"2017-03-01 12:12",group_id:3,group_name:"会员",status:1},{id:4,username:"zhangsan",alias:"张三",create_at:"2017-03-01 12:12",group_id:3,group_name:"会员",status:1},{id:5,username:"zhangsan",alias:"张三",create_at:"2017-03-01 12:12",group_id:2,group_name:"会员",status:1},{id:6,username:"zhangsan",alias:"张三",create_at:"2017-03-01 12:12",group_id:1,group_name:"会员",status:1}]}}},created:function(){for(var e in this.list.data)this.list.data[e].group_name=this.getGroupName(this.list.data[e].group_id),this.list.data[e].status_name=1===this.list.data[e].status?"正常":"不可用"},methods:{handleSubmit:function(){console.log(this.formInline)},showEdit:function(e){this.handleUser=this.list.data[e],this.userPanel=!0},showDelete:function(e){var t=this;this.$Modal.confirm({title:"确定删除?",content:"那就删除吧~",loading:!0,onOk:function(){setTimeout(function(){t.$Modal.remove(),t.$Message.success("用户已删除")},2e3)},cancelText:"取消"})},getList:function(e){console.log(e)},setUser:function(){console.log(this.handleUser),this.$Modal.remove(),this.$Message.success("操作成功")},getGroupName:function(e){for(var t=0;t<this.groupList.length;t++)if(e===this.groupList[t].gid)return this.groupList[t].name;return"无分组"}}}},248:function(e,t,a){t=e.exports=a(15)(),t.push([e.i,".table-box{width:98%;margin:10px 1%;position:relative;top:0;bottom:0;overflow:hidden}.table-page{display:block;float:right;margin:15px 10px}","",{version:3,sources:["/Users/snowdiva/www/font/ldemanage/src/components/pages/Review.vue"],names:[],mappings:"AACA,WACE,UAAW,AACX,eAAgB,AAChB,kBAAmB,AACnB,MAAO,AACP,SAAU,AACV,eAAiB,CAClB,AACD,YACE,cAAe,AACf,YAAa,AACb,gBAAkB,CACnB",file:"Review.vue",sourcesContent:["\n.table-box {\n  width: 98%;\n  margin: 10px 1%;\n  position: relative;\n  top: 0;\n  bottom: 0;\n  overflow: hidden;\n}\n.table-page {\n  display: block;\n  float: right;\n  margin: 15px 10px;\n}\n"],sourceRoot:""}])},270:function(e,t,a){var s=a(248);"string"==typeof s&&(s=[[e.i,s,""]]),s.locals&&(e.exports=s.locals);a(16)("52ee95d7",s,!0)},297:function(e,t){e.exports={render:function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{attrs:{id:"user"}},[a("div",{staticClass:"breadcrumb-box"},[a("Breadcrumb",[a("Breadcrumb-item",{attrs:{href:"/home"}},[e._v(" 首页 ")]),e._v(" "),a("Breadcrumb-item",[e._v(" 评论管理 ")])],1)],1),e._v(" "),a("Row"),e._v(" "),a("Modal",{attrs:{title:"用户编辑","mask-closable":!1,loading:!0,width:"720"},on:{"on-ok":e.setUser},model:{value:e.userPanel,callback:function(t){e.userPanel=t},expression:"userPanel"}},[a("Row",[a("i-col",{attrs:{span:"20",offset:"2"}},[a("i-form",{attrs:{"label-width":100},model:{value:e.handleUser,callback:function(t){e.handleUser=t},expression:"handleUser"}},[a("Form-item",{attrs:{label:"用户编号"}},[a("i-input",{attrs:{disabled:""},model:{value:e.handleUser.id,callback:function(t){e.handleUser.id=t},expression:"handleUser.id"}})],1),e._v(" "),a("Form-item",{attrs:{label:"登录名"}},[a("i-input",{attrs:{disabled:""},model:{value:e.handleUser.username,callback:function(t){e.handleUser.username=t},expression:"handleUser.username"}})],1),e._v(" "),a("Form-item",{attrs:{label:"别称"}},[a("i-input",{attrs:{placeholder:"不填写则不修改"},model:{value:e.handleUser.alias,callback:function(t){e.handleUser.alias=t},expression:"handleUser.alias"}})],1),e._v(" "),a("Form-item",{attrs:{label:"重置密码"}},[a("i-input",{attrs:{placeholder:"不填写则不修改"},model:{value:e.handleUser.password,callback:function(t){e.handleUser.password=t},expression:"handleUser.password"}})],1),e._v(" "),a("Form-item",{attrs:{label:"用户组变更"}},[a("i-select",{model:{value:e.handleUser.group_id,callback:function(t){e.handleUser.group_id=t},expression:"handleUser.group_id"}},e._l(e.groupList,function(t){return a("i-option",{attrs:{value:t.gid}},[e._v(e._s(t.name))])}))],1),e._v(" "),a("Form-item",{attrs:{label:"用户状态"}},[a("Radio-group",{staticStyle:{float:"left"},attrs:{type:"button",size:"small"},model:{value:e.handleUser.status,callback:function(t){e.handleUser.status=t},expression:"handleUser.status"}},[a("Radio",{attrs:{label:"1"}},[e._v("正常")]),e._v(" "),a("Radio",{attrs:{label:"0"}},[e._v("锁定")])],1)],1)],1)],1)],1)],1)],1)},staticRenderFns:[]}},32:function(e,t,a){a(270);var s=a(6)(a(222),a(297),null,null);e.exports=s.exports}});
//# sourceMappingURL=12.ece8689d4084346d1636.js.map