/**
 * Cell Manager jQuery plugin
 *
 * Usage:
 *
 * $("#P2Cell_classProps").externalModel('action');
 */

(function($) {
    $.fn.externalModel = function(options, settings) {
        
        var config = {
            'context' : null,
            'dialogSelector': 'externalModel',
            'classPropsSelector': 'classProps'
        };

        if (settings) {
            $.extend(config, settings);
        }

        this.each(function() {
            $this = $(this);
            switch(options) {

                case 'openDialog':
                    $("#"+this.id+'_'+config.dialogSelector+'_'+config.context).dialog('open');
                    break;

                case 'closeDialog':
                    $("#"+this.id+'_'+config.dialogSelector+'_'+config.context).dialog('close');
                    break;

                case 'applyClassProps':
                    data = new Object();
                    widgetBoundary = "#"+this.id+'_'+config.dialogSelector+'_'+config.context;
                    // collect input from model form fields
                    if ($('#modelClass', widgetBoundary).length > 0) {
                        model = $('#modelClass', widgetBoundary).val();
                        inputs = $('.classProps :input', widgetBoundary).filter(
                            function(){
                                // should begin with the model name
                                pattern = "^"+model;
                                // add attributes when specified
                                if ($("#modelAttributesPattern").val() != undefined) {
                                    pattern += "_"+$("#modelAttributesPattern").val();
                                }
                                regexp = new RegExp(pattern);
                                return regexp.exec(this.id);
                            }
                            );
                        for (var i = 0; i < inputs.length; i++) {
                            property = inputs[i].id.replace(model+'_', '');
                            if($(inputs[i]).is('[type=checkbox]') && !$(inputs[i]).is(':checked')) {
                                data[property] = 0;
                            } else {
                                data[property] = inputs[i].value;
                            }
                        }
                    }

                    // collect input from auto-generated forms
                    else {
                        inputs = $('.classProps', widgetBoundary);
                        //alert(JSON.stringify(inputs));
                        for (var i = 0; i < inputs.length; i++) {
                            property = inputs[i].id.replace(config.classPropsSelector+'_', '');
                            data[property] = inputs[i].value;
                        }
                    }

                    $('#P2Cell_classProps', '#'+config.context).val(JSON.stringify(data));
                    break;

            /*case 'saveCell':
                    $('#'+this.id+' input[type=submit]').click();
                    break;*/
            }

        });

        return this;
    };

})(jQuery);

