<?php

class Mo_Revisions_Mailer{

    function mor_send_mail($to,$subject,$content){
        $html_content = "<html>{$content}</html>";
        $headers  = array( 'Content-Type: text/html; charset=UTF-8' );

        wp_mail($to, $subject, $html_content,$headers);
        
    }

}



?>