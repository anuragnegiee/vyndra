jQuery(document).ready(function ($) {
    jQuery(".Qikinput").click(function () {         
         var validate= jQuery(".Username").val();
         var avalidate= isEmptyOrSpaces(validate);
         if(avalidate){
             jQuery("#qikink_error_txt").text("Enter Valid Username");
             jQuery(".qik_errordiv").show(); 
             return false;
         }
         jQuery(this).prop('disabled', true);
        var qikink_data = {
            'action': 'get_qikink_email',
            'EmailId': jQuery("#qikink_email").val()
        };
        
        jQuery.ajax(
                {
                    type: "POST",
                    dataType: "json",
                    url: ajax_object.ajax_url,
                    data: qikink_data,
                    success: function (response) {
                        if (response.msg==='success'){
                            jQuery(".qik_logindiv").hide();
                            jQuery(".qik_otpdiv").show();
                            jQuery(".qik_errordiv").hide();
                            
                        }else if(response.msg==='failure'){
                            $("#qikink_error_txt").text(response.txt);
                            jQuery(".qik_errordiv").show();                            
                            $(".Qikinput").removeAttr("disabled");
                        }
                        if(typeof response.client_id !=="undefined"){
                            $("#hidden_client_id").val(response.client_id);
                        }
                    }

                }

        );

    });
    jQuery(".QikinkOtp").click(function () {
        var validate= jQuery(".qotp").val();
         if(!validate){
             jQuery(".otpfailure").show(); 
             return false;
         }
         jQuery(this).prop('disabled', true);         
        var qikink_data = {
            'action': 'get_qikink_otp',
            'otp': jQuery("#qikink_otp").val(),
            'client_id':jQuery("#hidden_client_id").val()
            
        };
        
        jQuery.ajax(
                {
                    
                    type: "POST",
                    dataType: "json",
                    url: ajax_object.ajax_url,
                    data: qikink_data,
                    success: function (response) {
                        
                        if (response.msg==='Success'){
                            
                            jQuery(".otpsuccess").show();
                            jQuery(".otpfailure").hide();
                            call_url();
                            
                        }else if(response.msg==='failure'){
                            jQuery(".otpfailure").show();
                            $(".QikinkOtp").removeAttr("disabled");
                        }
                    }

                }

        );


    });
    
});
function call_url(){
    
    var qikink_data = {
            'action': 'qikink_endpoint',
            'client_id':jQuery("#hidden_client_id").val()
        };
        jQuery.ajax(
                {
                    type: "POST",
                    dataType: "json",
                    url: ajax_object.ajax_url,
                    data: qikink_data,
                    success: function (response) {
                        window.open(response.url);                   
                    }

                }

        );
}
function isEmptyOrSpaces(str){
    return str === null || str.match(/^ *$/) !== null;
}