
<div class="mo-asd-center row mor-asd-navbar" >
    <button  class="button button-primary" onclick="javascript:window.history.back();">	&#129136; Back</button>
    <img id="mo-asd-logo" src = 'https://www.miniorange.com/images/logo/miniorange-logo.webp' >
</div>

<div class = "mor-asd-container">
    
    <div class="card mor-asd-card">
    <h1><?php echo esc_html($post[0]->page_title) ; ?></h1>
    <br>
    <div style="font-size:16px">
        <b><?php echo esc_html($updated_by->display_name) ; ?>  </b>
        wants to merge changes into 
        <a href="<?php echo esc_url($post[0]->guid) ?>" target="_blank">
            <b><?php echo esc_html($post[0]->page_title) ; ?></b>
        </a>
        .
    </div>
    <br>
    <div style="font-size: 12px">
        Changes created at <?php 
                $time1 = "05:30:00";
                $secs = strtotime($time1)-strtotime("00:00:00");
                echo esc_html(date('d M Y H:i:s', strtotime($post[0]->modified_at)+$secs))
              ?>
    </div>
    <br>
    <div>
        <form method="post">
            
            <input type="hidden" name="track_id" value = <?php echo esc_html($post[0]->track_id) ?> />
            <?php wp_nonce_field('mo_rev_nonce', 'mo_rev_action'); ?>
        
            <button type="button" name="approve" value="Approve" class="mor-asd-rev-buttons button-secondary mor-asd-button-success" title="Click to merge and publish changes">
                <img src = "<?php echo esc_url(plugin_dir_url( __FILE__ ) . '../images/confirm-icon.svg') ?>" alt = "Checkmark icon" class="mor-asd-button-img"/>
                Approve
            </button>
            <button type="button" name="deny" value="Deny" class="mor-asd-rev-buttons button-secondary mor-asd-button-fail" title="Click to reject changes">
                <img src = "<?php echo esc_url(plugin_dir_url( __FILE__ ) . '../images/cancel-icon.svg') ?>" alt = "Checkmark icon" class="mor-asd-button-img"/>
                Deny
            </button>
        </form>
    </div>
</div>
    <!-- tabs -->
    <br>
    <br>
    <div class = "row">
    <button class="tablink" onclick="openPage('code-diff', this, '#2271b1')" id="defaultOpen">Code Difference</button>
     </div>
   
    <div id="code-diff" class="tabcontent">
        <div class = "card mor-asd-card">
            <h3>Title</h3>
            <?php
                $changes = wp_text_diff($old_title,$new_title);
                if(!$changes){
                    echo wp_kses_post("<h4>No changes in title.</h4>");
                }
                else{
                    echo wp_kses_post($changes);
                }  
            ?>
        </div>
        
        <div class="card mor-asd-card">
            <h3>Content</h3>
        
            <?php
                $changes = wp_text_diff($s1,$s2);
                if(!$changes){
                    echo wp_kses_post("<h3>No changes in content.</h3>");
                }
                else{
                    echo wp_kses_post($changes);
                }  
            ?>
        </div>

    </div> 
    
    
    <div id="ui-diff" class="tabcontent">
        <div class="row card mor-asd-card">
            <div class="col-6">
                <?php echo esc_html($s1);?>
            </div> 
            <div class="col-6">
                <?php echo esc_html($s2);?>
            </div>
        </div>
    
    </div>

    <div class = "mor-asd-container">
    
    <div class="card mor-asd-card">
    <h1><?php echo esc_html($post[0]->page_title) ; ?></h1>
    <br>
    <div style="font-size:16px">
        <b><?php echo esc_html($updated_by->display_name) ; ?>  </b>
        wants to merge changes into 
        <a href="<?php echo esc_url($post[0]->guid)?>" target="_blank">
            <b><?php echo esc_html($post[0]->page_title) ; ?></b>
        </a>
        .
    </div>
    <br>
    <div style="font-size: 12px">
        Changes created at <?php 
                $time1 = "05:30:00";
                $secs = strtotime($time1)-strtotime("00:00:00");
                echo esc_html(date('d M Y H:i:s', strtotime($post[0]->modified_at)+$secs))
              ?>
    </div>
    <br>
    <div>
        <form method="post">
            <input type="hidden" name="track_id" value = <?php echo esc_html($post[0]->track_id) ?> />
            <?php wp_nonce_field('mo_rev_nonce', 'mo_rev_action'); ?>
        
            <button type="button" name="approve" value="Approve" class="mor-asd-rev-buttons button-secondary mor-asd-button-success" title="Click to merge and publish changes">
                <img src = "<?php echo esc_url(plugin_dir_url( __FILE__ ) . '../images/confirm-icon.svg') ?>" alt = "Checkmark icon" class="mor-asd-button-img"/>
                Approve
            </button>
            <button type="button" name="deny" value="Deny" class="mor-asd-rev-buttons button-secondary mor-asd-button-fail" title="Click to reject changes">
                <img src = "<?php echo esc_url(plugin_dir_url( __FILE__ ) . '../images/cancel-icon.svg') ?>" alt = "Checkmark icon" class="mor-asd-button-img"/>
                Deny
            </button>
        </form>
    </div>
</div>
        



