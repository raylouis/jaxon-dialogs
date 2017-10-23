jaxon.dialogs.bootstrap = {
    success: function(content, title) {
        BootstrapDialog.alert({type: BootstrapDialog.TYPE_SUCCESS, message: content, title: title});
    },
    info: function(content, title) {
        BootstrapDialog.alert({type: BootstrapDialog.TYPE_INFO, message: content, title: title});
    },
    warning: function(content, title) {
        BootstrapDialog.alert({type: BootstrapDialog.TYPE_WARNING, message: content, title: title});
    },
    error: function(content, title) {
        BootstrapDialog.alert({type: BootstrapDialog.TYPE_DANGER, message: content, title: title});
    },
    confirm: function(question, title, yesCallback, noCallback) {
        BootstrapDialog.confirm({
            title: title,
            message: question,
            btnOKLabel: "<?php echo $this->no ?>",
            btnCancelLabel: "<?php echo $this->yes ?>",
            callback: function(res){
                if(res)
                    yesCallback();
                else if(noCallback != undefined)
                    noCallback();
            }
        });
    }
};

jaxon.command.handler.register("bootstrap.show", function(args) {
    // Add buttons
    for(var ind = 0, len = args.data.buttons.length; ind < len; ind++)
    {
        button = args.data.buttons[ind];
        if(button.action == "close")
        {
            button.action = function(dialog){dialog.close();};
        }
        else
        {
            button.action = new Function(button.action);
        }
    }
    // Open modal
    BootstrapDialog.show(args.data);
});
jaxon.command.handler.register("bootstrap.hide", function(args) {
    // Hide modal
    BootstrapDialog.closeAll();
});
jaxon.command.handler.register("bootstrap.alert", function(args) {
    var dataTypes = {
        success: BootstrapDialog.TYPE_SUCCESS,
        info: BootstrapDialog.TYPE_INFO,
        warning: BootstrapDialog.TYPE_WARNING,
        danger: BootstrapDialog.TYPE_DANGER
    };
    args.data.type = dataTypes[args.data.type];
    BootstrapDialog.alert(args.data);
});

<?php if(($this->defaultForAlert)): ?>
jaxon.ajax.message.success = jaxon.dialogs.bootstrap.success;
jaxon.ajax.message.info = jaxon.dialogs.bootstrap.info;
jaxon.ajax.message.warning = jaxon.dialogs.bootstrap.warning;
jaxon.ajax.message.error = jaxon.dialogs.bootstrap.error;
<?php endif ?>
<?php if(($this->defaultForConfirm)): ?>
jaxon.ajax.message.confirm = jaxon.dialogs.bootstrap.confirm;
<?php endif ?>