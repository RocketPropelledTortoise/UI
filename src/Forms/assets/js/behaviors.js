
//helps for javascript side validation
APP.behaviors.form_validation = {
    attach: function (context, settings) {
        $('form', context).once('form_validation', function () {

            var $this = $(this);
            if($this.data('validate') == 'no'){
                return;
            }

            var temp=[];

            $this.find("*[data-rules]").each(function (i, el) {
                var $el = $(el);
                temp.push({
                    element: $el,
                    name: $el.attr("name"),
                    rules: $el.attr("data-rules"),
                    display: $el.attr("data-display"),
                    live: $el.attr("data-live")
                });
            });

            // Create FormValidator object
            var validator = new FormValidator(this, temp, function(errors, allFields) {

                // Clear all error fields
                if(allFields === true){
                    $this.find(".error").removeClass("error");
                    $this.find(".help-block").html("");
                }

                $('[for='+ allFields+']').find("input").removeClass("error").end().find(".help-block").html("");

                // Check for errors
                if(errors.length > 0) {
                    $.each(errors, function (index, err) {
                        // Displays the erros message in the help-block
                        var $target = $('[name='+ err.name+']').addClass("error");
                        $target.next(".help-block").html(err.message);

                        // Adds error class to the controlgroup
                        $target.closest("label").addClass("error");
                    });
                    return false;
                }
                return true;
            });

            $this.data("instance", validator);
        });
    }
};

//make HTML5 placeholders work in non supportive browsers
APP.behaviors.placeholders = {
    hasPlaceholder: ('placeholder' in document.createElement('input') && 'placeholder' in document.createElement('textarea')),
    attach: function (context, settings) {
        if(!APP.behaviors.placeholders.hasPlaceholder){
            $("input[placeholder]", context).once('placeholder',function(){
                if($(this).val()==""){
                    $(this)
                    .val($(this).attr("placeholder"))
                    .focus(function(){
                        if($(this).val()==$(this).attr("placeholder")) $(this).val("");
                    })
                    .blur(function(){
                        if($(this).val()==""){
                            $(this).val($(this).attr("placeholder"));
                        }
                    });
                }
            });

            $('form', context).once('placeholder',function(){
                $(this).submit(function(){
                    $('input[placeholder]', $(this)).each(function(){
                        if($(this).attr("placeholder") == $(this).val()) {
                            $(this).val('');
                        }
                    });
                });
            });
        }
    }
}
