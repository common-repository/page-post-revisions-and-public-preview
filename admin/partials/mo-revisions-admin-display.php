<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://miniorange.com
 * @since      1.0.0
 *
 * @package    Mo_Revisions
 * @subpackage Mo_Revisions/admin/partials
 */
?>

<div class='mor-admin-display-top'>
  <img id="mor-admin-display-logo" src = 'https://www.miniorange.com/images/logo/miniorange-logo.webp' >
  <h1>Revisions Manager</h1>
</div>
<br>
<h2 id = "mor-admin-display-table-heading">Page Update Requests</h2>
<table id="myTable" class=" widefat striped" style="margin-right:10px;">
  <thead>
    <tr>
        <th>Page Title</th>
        <th>Updated By</th>
        <th>Last Update</th>
        <th>Changes</th>
           
    </tr>
  </thead>
  <tbody>
    <?php foreach($post_result as $list){  
      $updated_by = get_user_by("ID", $list->post_author);
      ?>
      
        <tr>
            <td><?php echo esc_html($list->page_title)?></td>
           
            <td><?php echo esc_html($updated_by->user_login)?></td>
            
            <td>
              <?php 
                $time1 = "05:30:00";
                $secs = strtotime($time1)-strtotime("00:00:00");
                echo esc_html(date('d M Y H:i:s', strtotime($list->modified_at)+$secs))
              ?>
            </td>
            <td>
              <form method="post">
                <?php wp_nonce_field('mo_rev_nonce', 'mo_rev_action'); ?>
                <input type="hidden" name="track_id" value = <?php echo esc_attr($list->track_id) ?> />
                <input type="submit" name="view" value="View" class="button button-primary mor-admin-display-button" title="click to view UI and Code difference"/>
              </form>
            </td>
            
        </tr>

    <?php } ?>
  </tbody>
  <tfoot>
    <tr>
        <th>Page Title</th>
        <th>Updated By</th>
        <th>Last Update</th>
        <th>Changes</th>
        
    </tr>
  </tfoot>
</table>

<div class='mor-admin-display-top'>
  <p>For more enquire or premium feature list please contact us on <a href="">2fasupport@xecurify.com</a></p>
</div>

