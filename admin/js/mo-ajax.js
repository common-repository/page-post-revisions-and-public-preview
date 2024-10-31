jQuery(window).bind('load',function(){
    
    jQuery('.mor-asd-rev-buttons').click(function(event) {
        
        var track_id = jQuery('input[name="track_id"]').val();
        var button_val = jQuery(this).val();
        var nonce = jQuery('input[name="mo_rev_action"]').val();
        
        var data = {
            action: 'mor_action',
            track_id: track_id ,
            button_val : button_val,
            nonce : nonce
        };

        jQuery.post(ajax_object.ajax_url, data, function(response) {
            jQuery(".mor-asd-rev-buttons").attr("disabled", true);
            
        });
    });
});

jQuery(document).ready( function () {
    document.getElementById("defaultOpen").click();
} );

function openPage(pageName,elmnt,color) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablink");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].style.backgroundColor = "";
      tablinks[i].style.color = "black";
    }
    document.getElementById(pageName).style.display = "block";
    elmnt.style.backgroundColor = color;
    elmnt.style.color = "white";
}
  
  
