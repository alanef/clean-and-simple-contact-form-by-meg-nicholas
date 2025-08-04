jQuery(document).ready(function ($) {

    var $div = $("#cscf");

    var $form = $div.find("#frmCSCF");

    $form.find("#recaptcha_response_field").focus(function () {

        $errele = $form.find("span[for='cscf_recaptcha']");
        $errele.html('');

    });

    $form.validate({

        errorElement: "span",

        highlight: function (label, errorClass, validClass) {
            $(label).closest('.form-group').removeClass('has-success').addClass('has-error');
            $(label).closest('.control-group').removeClass('success').addClass('error'); // support for bootstrap 2

        },
        unhighlight: function (label, errorClass, validClass) {
            $(label).closest('.form-group').removeClass('has-error').addClass('has-success');
            $(label).closest('.control-group').removeClass('error').addClass('success'); // support for bootstrap 2
        }
    });

    $form.submit(function (event) {
        event.preventDefault();

        if ($form.validate().valid()) {

            $button = $(this).find("#cscf_SubmitButton");
            $button.attr("disabled", "disabled");

            $.ajax({
                type: "post",
                dataType: "json",
                cache: false,
                url: cscfvars.ajaxurl,
                data: $($form).serialize() + "&action=cscf-submitform",
                success: function (response, strText) {
                    if (response.valid === true) {
                        //show sent message div
                        $formdiv = $div.find(".cscfForm");
                        $formdiv.css('display', 'none');
                        $messagediv = $div.find(".cscfMessageSent");
                        if (response.sent === false) {
                            $messagediv = $div.find(".cscfMessageNotSent");
                        }

                        $messagediv.css('display', 'block');

                        if (isScrolledIntoView($div) == false) {
                            jQuery('html,body')
                                .animate({
                                    scrollTop: $div.offset().top
                                }, 'slow');
                        }
                    }

                    else {
                        // Clear any previous errors first
                        $form.find("span.help-inline.help-block.error").html('').css('display', 'none');
                        $form.find('.form-group').removeClass('has-error').addClass('has-success');
                        $form.find('.control-group').removeClass('error').addClass('success');
                        
                        $.each(response.errorlist, function (name, value) {
                            // Debug logging
                            if (window.console) {
                                console.log("Error for field: " + name + ", message: " + value);
                            }
                            
                            $errele = $form.find("span[for='cscf_" + name + "']");
                            if ($errele.length > 0) {
                                $errele.html(value);
                                $errele.css('display', 'block');
                                $errele.closest('.form-group').removeClass('has-success').addClass('has-error');
                                $errele.closest('.control-group').removeClass('success').addClass('error'); // support for bootstrap 2
                            } else if (window.console) {
                                console.log("Could not find error element for: cscf_" + name);
                            }
                        });
                        $button.removeAttr("disabled");
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    if (window.console) {
                        console.log("Status: " + textStatus + "Error: " + errorThrown + "Response: " + XMLHttpRequest.responseText);
                    }
                    $button.removeAttr("disabled");

                }

            });

        }
        ;
    });

});


function isScrolledIntoView(elem) {
    var docViewTop = jQuery(window).scrollTop();
    var docViewBottom = docViewTop + jQuery(window).height();

    var elemTop = jQuery(elem).offset().top;
    var elemBottom = elemTop + jQuery(elem).height();

    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}