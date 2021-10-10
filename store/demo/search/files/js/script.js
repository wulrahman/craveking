$(document).ready(function() {
    var page = 1; 
    var loading  = false;
    var total = 10000;
    
    $('#' + c + '').load(""+ b + "", {'page':page, 'q':q}, function() {page++;});
    
    $(window).scroll(function() {
        
        if($(window).scrollTop() + $(window).height() == $(document).height())	{
            
            if(page <= total && loading==false) {
                loading = true;
                $('.animation_image').show();
                
                $.post('' + b + '',{'page': page, 'q':q}, function(data){
                                    
                    $("#"+ c + "").append(data);
                    $('.animation_image').hide();
                    
                    page++;
                    loading = false; 
                
                }).fail(function(xhr, ajaxOptions, thrownError) {
                    
                    alert(thrownError);
                    $('.animation_image').hide();
                    loading = false;
                
                });
                
            }
        }
    });
});
