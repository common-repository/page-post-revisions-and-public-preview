<?php

class MO_Revisions_DATABASE {

    function __construct(){
        global $wpdb;
    }

    function mor_fetch_pages(){
        global $wpdb;
        
        $post_result=$wpdb->get_results( $wpdb->prepare("SELECT * FROM wp_mor_data 
        WHERE approve_status = 'pending' and track_id IN (SELECT MAX(track_id) FROM wp_mor_data GROUP BY page_slug)" ));
        
        return $post_result;
    }

    function mor_fetch_single_page($track_id){
        global $wpdb;

        $post=$wpdb->get_results( $wpdb->prepare("SELECT * FROM wp_mor_data 
        WHERE track_id = %s",array($track_id)));
        
        return $post;
    }

    function mor_save_post_on_approve($track_id){

        global $wpdb;

        $post = $wpdb->get_results( $wpdb->prepare("SELECT * FROM wp_mor_data 
        WHERE track_id =%s",array($track_id) ));
        
        if($post[0]->approve_status=='pending'){
            $data = array(
                'post_author'           => $post[0]->post_author,
                'post_content'          => $post[0]->page_content,
                'post_title'            => $post[0]->page_title
                
            );
        
            $wpdb->update( 'wp_posts', $data, array( 'ID' => $post[0]->parent_id ) );
             
            $wpdb->query( $wpdb->prepare("UPDATE wp_mor_data SET approve_status= 'Approved' WHERE track_id=%s",array($track_id)));
            $rev_id = wp_save_post_revision($post[0]->parent_id);
          
            $user = get_current_user_id();
            $approved_by = get_user_by("ID", $user);

            $to = "test@test.com";
            $subject = "POST APPROVED";
            $message = "<html>
            <head>
            </head>
              <style>
                body {
                  text-align: center;
                  padding: 40px 0;
                  background: #EBF0F5;
                }
                  h1 {
                    color: #88B04B;
                    font-weight: 900;
                    font-size: 40px;
                    margin-bottom: 10px;
                  }
                  p {
                    color: #404F5E;
                    font-size:20px;
                    margin: 0;
                  }
                i {
                  color: #9ABC66;
                  font-size: 100px;
                  line-height: 200px;
                  margin-left:-15px;
                }
                .card {
                  background: white;
                  padding: 60px;
                  border-radius: 4px;
                  box-shadow: 0 2px 3px #C8D0D8;
                  display: inline-block;
                  margin: 0 auto;
                }
              </style>
              <body>
                <div class='card'>
                <div style='border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;'>
                  <i class='checkmark'>✓</i>
                </div>
                  <h1>Approved</h1> 
                  <p>Your " . $post[0]->page_title . " update request initiated on ".$post[0]->modified_at." is approved by ".$approved_by->user_login.". </p>
                  <br>
                  <p>You can view your changes <a href='".$post[0]->guid."'>here</a>. </p>
                </div>
              </body>
            </html>";
            
            global $mailer_object;
            $mailer_object->mor_send_mail($to,$subject,$message);

        }

        
    }

    function mor_denied_changes($track_id){
        global $wpdb;
        $wpdb->update( 'wp_mor_data', array('approve_status' => 'Denied'), array( 'track_id' => $track_id) );

        $user = get_current_user_id();
        $updated_by = get_user_by("ID", $user);

        $post = $wpdb->get_results( $wpdb->prepare("SELECT * FROM wp_mor_data 
        WHERE track_id =%s",array( $track_id)));
        
        $to = "test@test.com";
        $subject = "Changes Denied";
        $message = $message = "<html>
        <head>
        </head>
          <style>
            body {
              text-align: center;
              padding: 40px 0;
              background: #EBF0F5;
            }
            h1 {
              color: #d94652;
              font-weight: 900;
              font-size: 40px;
              margin-bottom: 10px;
            }
            p {
              color: #404F5E;
              font-size:20px;
              margin: 0;
            }
            i {
              color: #d94652;
              font-size: 100px;
              line-height: 200px;
              margin-left:-15px;
            }
            .card {
              background: white;
              padding: 60px;
              border-radius: 4px;
              box-shadow: 0 2px 3px #C8D0D8;
              display: inline-block;
              margin: 0 auto;
            }
          </style>
          <body>
            <div class='card'>
            <div style='border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;'>
              <i>✗</i>
            </div>
              <h1>Denied</h1> 
              <p>Your " . $post[0]->page_title . " update request initiated on ".$post[0]->modified_at." is denied by <br>".$updated_by->user_login.". </p>
            </div>
          </body>
      </html>";
        
      global $mailer_object;
      $mailer_object->mor_send_mail($to,$subject,$message);

    }

    function mor_save_post_before_update($post_ID,$post_data,$user){
        global $wpdb;

        $wpdb->insert(
            "wp_mor_data",
            array(
                'post_author' => $user,
                'page_content' => $post_data["post_content"],
                'page_title' => $post_data["post_title"],
                'post_status' => $post_data["post_status"],
                'post_type' => $post_data["post_type"],
                'comment_status' => $post_data["comment_status"],
                'ping_status' => $post_data["ping_status"],
                'page_slug' => $post_data["post_name"],
                'modified_at' => $post_data["post_modified"],
                'is_informed'=> false,
                'parent_id' => $post_ID,
                'approve_status' => "pending",
                'guid' => $post_data["guid"]
            )
        );

        return($wpdb->insert_id);
    }


}

?>