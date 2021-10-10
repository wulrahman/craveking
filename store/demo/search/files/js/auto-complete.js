var MIN_LENGTH = 2;

$( document ).ready(function() {
	$("#q").keyup(function() {
		var q = $("#q").val();
		if (q.length >= MIN_LENGTH) {

			$.post( "ajax/auto-complete.php", { q: q } )
			.done(function( data ) {
				$('#suggestions').html('');
				var suggestions = jQuery.parseJSON(data);
				$(suggestions).each(function(key, value) {
					$('#suggestions').append('<option class="item">' + value + '</option>');
				})

			    $('.item').click(function() {
			    	var text = $(this).html();
			    	$('#q').val(text);
			    })

			});
		} else {
			$('#suggestions').html('');
		}
	});

    $("#q").blur(function(){
    		$("#suggestions").fadeOut(500);
    	})
        .focus(function() {		
    	    $("#suggestions").show();
    	});

});