jaxon.dialogs.pgwjs = {
    defaults: {maxWidth: 400},
    options: {}
};
<?php echo $this->options ?>

jaxon.command.handler.register("pgw.modal", function(args) {
    // Set user and default options into data only when they are missing
    for(key in jaxon.dialogs.pgwjs.options)
    {
        if(!(key in args.data))
        {
            args.data[key] = jaxon.dialogs.pgwjs.options[key];
        }
    }
    for(key in jaxon.dialogs.pgwjs.defaults)
    {
        if(!(key in args.data))
        {
            args.data[key] = jaxon.dialogs.pgwjs.defaults[key];
        }
    }
    $.pgwModal(args.data);
});