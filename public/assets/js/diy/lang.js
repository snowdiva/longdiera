/**
 * 命名空间
 * @type {{}}
 */
var NameSpace = window.NameSpace || {}

// 按钮操作
NameSpace.button = new function() {
    var self = this

    self.ok = '知道了'
    self.confirm = '确认操作'
    self.cancel = '取消操作'
}

// 语言提示
NameSpace.message = new function() {
    var self = this

    self.success = '操作成功'
    self.error = '操作失败'
    self.delete = '一旦删除,则无法恢复,确认删除?'
    self.audit = '主动审核通过后会直接在前台显示,是否确认操作?'
}
