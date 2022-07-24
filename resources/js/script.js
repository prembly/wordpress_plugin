jQuery(document).ready(function($) {

    $('#idx_init').on('click', function(e) {
        e.preventDefault();

        // jQuery.ajax({
        //     type : "post",
        //     dataType : "json",
        //     url : myAjax.ajaxurl,
        //     data : {action: "my_price_request", data : data, nonce: nonce},
        //     success: function(response) {
        //        if(response.type == "success") {
        //           alert(response.data);
        //        }else{
        //           alert("error occured");
        //        }
        //     },
        //     error: function (xhr, ajaxOptions, thrownError) {
        //        console.log(xhr.status);
        //        console.log(thrownError);
        //        alert('major error');
        //     }
        //  })


        const modal = `
        <div class="id-modal" id="id-modal-name">
            <div class="id-modal-sandbox"></div>
            <form action="return false;" onsubmit="return false;">
                <div class="id-modal-box">
                    <div class="id-modal-header">
                    <div class="close-id-modal">&#10006;</div> 
                    <h1>Identitypass KYC Modal</h1>
                    </div>
                    <div class="id-modal-body">
                    <p><input type="text" name="firstname" id="idp_firstname" placeholder="Please enter your firstname" required /></p>
                    <p><input type="text" name="lastname" id="idp_lastname" placeholder="Please enter your lastname" required /></p>
                    <p><input type="email" name="email" id="idp_email" placeholder="Please enter your a valid email address" required /></p>
                    <p><input type="number" name="phone" id="idp_phone" placeholder="Please enter your a valid phone number" required /></p>
                    <!-- <p>Laboriosam voluptas, iure rem provident laborum culpa atque fugit inventore sit. Corrupti dolore architecto inventore officia, odit totam voluptatem laboriosam tempore reiciendis, et neque, consequuntur. Non, tenetur? Tempore reprehenderit tenetur nemo asperiores alias commodi assumenda architecto minima numquam repellendus debitis nulla, rerum officia itaque, sunt nihil sequi quod perspiciatis, animi quas voluptates velit aperiam voluptatem.</p> -->
                    <br /><br/>
                    <button class="close-id-modal" type="submit">Proceed!</button>
                    </div>
                </div>
            </form>
        </div>`;

        $('body').append(modal);

        $("#id-modal-name").css({"display": "block"});
    });

    $(document).on( 'click', '#id-modal-name form button' , function(event) {
        
        const firtName = $('#idp_firstname').val();
        const lastName = $('#idp_lastname').val();
        const email = $('#idp_email').val();
        const phoneNumber = $('#idp_phone').val();
        
        if(!firtName || !lastName || !email || !phoneNumber) return true;

        $(".id-modal").css({ "display": "none" });
        $(".id-modal").remove();
        
        var paymentEng =  IdentityKYC.verify({
            merchant_key: pluginScope.key,
            first_name: firtName,
            last_name: lastName,
            email: email,
            user_ref: pluginScope.userRef,
            is_test: pluginScope.testing,
            callback: function (response) {
                console.log("Response::");
                console.log("Callback Response", response);

                // jQuery.ajax({
                //     type : "post",
                //     dataType : "json",
                //     url : myAjax.ajaxurl,
                //     data : {action: "my_price_request", data : data, nonce: nonce},
                //     success: function(response) {
                //        if(response.type == "success") {
                //           alert(response.data);
                //        }else{
                //           alert("error occured");
                //        }
                //     },
                //     error: function (xhr, ajaxOptions, thrownError) {
                //        console.log(xhr.status);
                //        console.log(thrownError);
                //        alert('major error');
                //     }
                //  })
            }
        });
    });

    $(document).on('click', '#id-modal-name .id-modal-header .close-id-modal', function() {
        $(".id-modal").css({"display": "none"});
        $(".id-modal").remove();
    });
    
});