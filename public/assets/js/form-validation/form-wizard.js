var FormWizard = function () {


    return {
        //main function to initiate the module
        init: function () {
            if (!jQuery().bootstrapWizard) {
                return;
            }

            function format(state) {
                if (!state.id) return state.text; // optgroup
                return "<img class='flag' src='../../assets/global/img/flags/" + state.id.toLowerCase() + ".png'/>&nbsp;&nbsp;" + state.text;
            }

            $("#country_list").select2({
                placeholder: "Select",
                allowClear: true,
                formatResult: format,
                width: 'auto',
                formatSelection: format,
                escapeMarkup: function (m) {
                    return m;
                }
            });

            var form = $('#release_requirement_form');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);

            form.validate({
                doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                rules: {
                    title: {
                        minlength: 1,
                        required: true
                    },
                    detail: {
                        minlength: 1,
                        required: true
                    },
                    apply_requirement: {
                        minlength: 1,
                        required: true
                    },
                    service_time_from: {
                        required: true,
                        lessEqualThan: ["#service_time_to","时间结束"]
                    },
                    service_time_to: {
                        required: true
                    },
                    time_required: {
                        required: true
                    },
                    valid_time: {
                        required: true,
                        lessThan: ["#service_time_from","服务开始时间"]
                    },
                    pay_amount: {
                        number : true,
                        required: true
                    },
                    region_1: {
                        required: true
                    },
                    district: {
                        required: true
                    },
                    detail_address: {
                        required: true
                    },
                    contact: {
                        required: true
                    },
                    contact_phone: {
                        required: true,
                        number:true,
                        maxlength:11,
                        minlength:11
                    },
                    captcha_code: {
                        required: true
                    },
                    contact_phone_confirm: {
                        required: true,
                        number:true,
                        maxlength:11,
                        minlength:11
                    }
                },

                messages: { // custom messages for radio buttons and checkboxes
                    'demand_type[]': {
                        required: "Please select at least one option",
                        minlength: jQuery.validator.format("Please select at least one option")
                    }
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    if (element.attr("name") == "demand_type") { // for uniform radio buttons, insert the after the given container
                        error.insertAfter("#form_demand_type_error");
                    } else if (element.attr("name") == "payment[]") { // for uniform checkboxes, insert the after the given container
                        error.insertAfter("#form_payment_error");
                    } else {
                        error.insertAfter(element); // for other inputs, just perform default behavior
                    }
                },

                invalidHandler: function (event, validator) { //display error alert on form submit
                    success.hide();
                    error.show();
                    // App.scrollTo(error, -200);
                },

                highlight: function (element) { // hightlight error inputs
                    $(element)
                        .closest('.form-group').removeClass('has-success').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    if (label.attr("for") == "demand_type" || label.attr("for") == "payment[]") { // for checkboxes and radio buttons, no need to show OK icon
                        label
                            .closest('.form-group').removeClass('has-error').addClass('has-success');
                        label.remove(); // remove error label here
                    } else { // display success icon for other inputs
                        label
                            .addClass('valid') // mark the current input as valid and display OK icon
                            .closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                    }
                },

                submitHandler: function (form) {
                    success.show();
                    error.hide();
                    // form[0].submit();
                    //add here some ajax code to submit your form or just call form.submit() if you want to submit the form without ajax
                }

            });

            var displayConfirm = function()
            {
                var title               = $("#title").val();
                var detail              = $("#detail").val();
                var apply_requirement   = $("#apply_requirement").val();
                var service_time_from   = $("#service_time_from").val();
                var service_time_to     = $("#service_time_to").val();
                var time_required       = $("#time_required").val();
                var valid_time          = $("#valid_time").val();
                var pay_amount          = $("#pay_amount").val();

                var region_1_val        = $("#region_1 option:selected").text();

                var district_val        = $("#district option:selected").text();
                var detail_address      = $("#detail_address").val();
                var contact             = $("#contact").val();
                var contact_phone       = $("#contact_phone").val();
                // var district_id         = $("#district").val();
                // var region_1_id         = $("#region_1").val();
                // var published_time      = $("#published_time").val();
                // var verify_code         = $("#verify_code").val();

                var demand_type         = getDemandTypeText();
                if (typeof demand_type ==='undefined') demand_type_val = $('input[name = "demand_type"]:checked').next('label').text();
                // console.log(demand_type);

                $("#demand_type_confirm").val(demand_type_val);
                $("#payment_time_confirm").val(pay_amount);
                $("#valid_time_confirm").val(valid_time);
                $("#contact_confirm").val(contact);
                $("#contact_phone_confirm").val(contact_phone);
                $("#service_address_confirm").val(region_1_val+" "+district_val+" "+detail_address);
                $("#service_hours").val(generateYear(service_time_from)+'年'+generateMonth(service_time_from)+'月'+generateDay(service_time_from)+'日-'+generateYear(service_time_to)+'年'+generateMonth(service_time_to)+'月'+generateDay(service_time_to)+'日');
                $("#time_required_confirm").val(time_required);
                $("#detail_confirm").val(detail);
                $("#apply_requirement_confirm").val(apply_requirement);

                $("#payment_time_confirm,#valid_time_confirm,#contact_confirm,#contact_phone_confirm," +
                    "#service_address_confirm,#service_hours,#time_required_confirm,#detail_confirm,#apply_requirement_confirm").change(function () {
                        $("#pay_amount").val($("#payment_time_confirm").val());
                        $("#contact").val($("#contact_confirm").val());
                        $("#contact_phone").val($("#contact_phone_confirm").val());
                        $("#time_required").val($("#time_required_confirm").val());
                        $("#detail").val($("#detail_confirm").val());
                        $("#apply_requirement").val($("#apply_requirement_confirm").val());
                        $("#valid_time").val($("#valid_time_confirm").val());

                })

            }


            var handleTitle = function(tab, navigation, index) {
                var total = navigation.find('li').length;
                 // alert(navigation);
                var current = index + 1;

                // set done steps
                jQuery('li', $('#form_wizard_1')).removeClass("done");
                var li_list = navigation.find('li');
                for (var i = 0; i < index; i++) {
                    jQuery(li_list[i]).addClass("done");
                }

                if (current == 1) {
                    $('#form_wizard_1').find('.button-previous').hide();
                    $('#form_wizard_1').find('.button-save').hide();

                    //when user click first next button change button's  at second position
                    $(".form-actions").css("margin-left", "167px");
                    $(".button-next").css("background-color", "#ff5656");

                    $(".progtrckr li").removeClass().addClass("progtrckr-todo");
                    $("#step-"+current).removeClass().addClass("progtrckr-done");

                } else {
                    $('#form_wizard_1').find('.button-previous').show();
                    $('#form_wizard_1').find('.button-save').hide();

                    $(".form-actions").css("margin-left", "166px");
                    $(".button-next").css("background-color", "#ffc750");


                    $(".progtrckr li").removeClass().addClass("progtrckr-todo");
                    $("#step-"+current).removeClass().addClass("progtrckr-done");
                }

                if (current >= total) {
                    $('#form_wizard_1').find('.button-next').hide();
                    $('#form_wizard_1').find('.button-submit').show();
                    $('#form_wizard_1').find('.button-save').show();
                    displayConfirm();

                    $(".progtrckr li").removeClass().addClass("progtrckr-todo");
                    $("#step-"+current).removeClass().addClass("progtrckr-done");
                } else {
                    $('#form_wizard_1').find('#btn_next1').show();
                    $('#form_wizard_1').find('.button-next').show();
                    $('#form_wizard_1').find('.button-submit').hide();
                    $('#form_wizard_1').find('.button-save').hide();
                }
                if (current == 1){
                    $('#form_wizard_1').find('#btn_next1').show();
                    $('#form_wizard_1').find('#btn_next').hide();
                    $('#form_wizard_1').find('.button-submit').hide();
                    $('#form_wizard_1').find('.button-save').hide();
                }
                // App.scrollTo($('.page-title'));
            }

            // default form wizard
            $('#form_wizard_1').bootstrapWizard({
                'nextSelector': '.button-next',
                'previousSelector': '.button-previous',
                onNext: function (tab, navigation, index) {
                    success.hide();
                    error.hide();
                    if (form.valid() == false)
                    {
                        return false;
                    }

                    handleTitle(tab, navigation, index);
                },
                onPrevious: function (tab, navigation, index) {
                    success.hide();
                    error.hide();

                    handleTitle(tab, navigation, index);
                },

            });

            $('#form_wizard_1').find('.button-previous').hide();
            $('#form_wizard_1').find('#btn_next').hide();
            //mode =0 save drafts insert state value 1
            $('#form_wizard_1').find('.button-save').click(function (e) {
                e.preventDefault();

                if ($("#captcha_code").val()=="")
                {
                    $("#captcha_error").show();
                    return false;
                }

                var formData = $("#release_requirement_form").serialize();
                $.ajax({
                    url : '/index/service_management.release_requirement/save/mode/0',
                    type : 'POST',
                    data : formData,
                    success:function (result)
                    {
                        if (result == "captcha_fail")
                        {
                            $("#captcha_error").text("验证码错误");
                        }
                        else if (result == "not_verify")
                        {
                            $.alert({
                                title: '注意!。',
                                content: '项目无法提交，因为真实姓名尚未验证。 请等待管理员批准!。',
                            });
                            window.location.href=('/index/service_management.release_requirement');
                        }

                        else
                            window.location.href=('/index/service_management.show_published');
                    }
                })
            }).hide();
            //save confirm and publish insert state value 2
            $('#form_wizard_1 .button-submit').click(function (e) {
                e.preventDefault();

                if ($("#captcha_code").val()=="")
                {
                    $("#captcha_error").show();
                    return false;
                }

                var formData = $("#release_requirement_form").serialize();
                // console.log(formData);
                var mode    = 1;
                $.ajax({
                    url : '/index/service_management.release_requirement/save/mode/1',
                    type : 'POST',
                    data : formData,
                    success:function (result)
                    {
                        if (result == "captcha_fail")
                        {
                            $.alert({
                                title: '注意!。',
                                content: '验证码错误!。',
                            });
                        }
                        else if (result == "not_verify")
                        {
                            $.alert({
                            title: '注意!。',
                            content: '项目无法提交，因为真实姓名尚未验证。 请等待管理员批准!。',
                        });
                            window.location.href=('/index/service_management.release_requirement');
                        }
                        else
                            window.location.href=('/index/service_management.show_published');
                    }
                })
            }).hide();
        }

    };

}();

jQuery(document).ready(function() {
    FormWizard.init();
});