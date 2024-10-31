jQuery(document).ready(function($) {
	
    $(document).on( 'click', '#idx_init' , function(event) {
      
        $(".id-modal").css({ "display": "none" });
        $(".id-modal").remove();
        var paymentEng =  IdentityKYC.verify({
            merchant_key: pluginScope.key,
            first_name: pluginScope.verification_firstname,
            last_name: pluginScope.verification_lastname,
            email: pluginScope.verification_email,
            user_ref: pluginScope.userRef,
            is_test: pluginScope.testing,
            config_id: pluginScope.config_id,
            callback: function (response) {
                url = pluginScope.page_redirect
                url = url.split ( ':' )
                if ( url[0] != 'http' && url[1] != 'https' )
                {
                    url = 'https://' + url.join ( ':' )
                }
                else
                {
                    url = url.join ( ':' )
                }

                window.location.href = url
                // console.log("Response::");
                // console.log("Callback Response", response);
            }
        });
    });

    $(document).on('click', '#id-modal-name .id-modal-header .card-close', function() {
        $(".id-modal").css({"display": "none"});
        $(".id-modal").remove();
    });
    
});