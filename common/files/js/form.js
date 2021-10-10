function request_post(a) {

    $("." + a + "").submit(function(event) {
    var ajaxRequest;

    event.preventDefault();

    var values = $(this).serialize();

       ajaxRequest= $.ajax({
            url: url_post,
            type: "post",
            data: values
        });

     ajaxRequest.done(function(response, textStatus, jqXHR){
          $("#comment").html(response);
     });
     
     /* On failure of request this function will be called  */
     ajaxRequest.fail(function (){

       // show error
       $("#comment").html('There is error while submit');
     });

});

}
$('#comment').load(""+ url_post + "", {'id': id});
