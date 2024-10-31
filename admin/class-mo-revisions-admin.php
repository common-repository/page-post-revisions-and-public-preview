<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://miniorange.com
 * @since      1.0.0
 *
 * @package    Mo_Revisions
 * @subpackage Mo_Revisions/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Mo_Revisions
 * @subpackage Mo_Revisions/admin
 * @author     miniOrange <info@xecurify.com>
 */
class Mo_Revisions_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */


    
    
	//when a post is approved , it's entry is created in wp_posts and whenever we insert something in wp_posts
    //the pre_post_update hook is called so to stop that we check if post_exists or not.


	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        
	}
	

	function mor_revisions_menu(){
        $menu_slug = 'mo_revision_manager';
        add_menu_page( 'miniOrange Revisions Manager', 'miniOrange Revisions Manager', 'manage_options', $menu_slug,array($this,'mor_revisions_custom'), plugin_dir_url( __FILE__ ) . '/images/miniorange_icon.png');
        add_submenu_page($menu_slug, 'miniOrange Revisions Manager', 'Dashboard', 'manage_options', $menu_slug, array($this,'mor_revisions_custom'),1);
        add_submenu_page($menu_slug, 'Help', 'Help', 'manage_options', 'mo_revision_help', array($this,'mor_revisions_help'),2);
    }

    function mor_revisions_custom(){
		global $db_object;
        if ( isset( $_GET['page']) ){

            $page = sanitize_text_field( $_GET['page'] );
            
            if($page == 'mo_revision_manager' && !isset($_POST['view'])){

                
                $post_result = $db_object -> mor_fetch_pages();

                include plugin_dir_path( dirname( __FILE__ ) ).'admin/partials/mo-revisions-admin-display.php';   
            }
            
            if(isset($_POST['mo_rev_action'])){
                $nonce = sanitize_text_field($_POST['mo_rev_action']);
                if(wp_verify_nonce($nonce,'mo_rev_nonce') ){
                    
                    if(isset($_POST['view'])){
                        $post = $db_object->mor_fetch_single_page(sanitize_text_field($_POST['track_id']));
                        $post_before = get_post($post[0]->parent_id);
        
                        $old_title = $post_before -> post_title;
                        $new_title = $post[0] -> page_title;
                        $s1 =  $post_before->post_content;
                        $s2 = $post[0]->page_content;
        
                        $updated_by = get_user_by("ID", $post[0]->post_author);
                        
                        include plugin_dir_path( dirname( __FILE__ ) ).'admin/partials/mo-revisions-admin-show-diff.php';
                    }
                }
            }            
        }
    }

    function mor_revisions_help(){
        if ( isset( $_GET['page']) ){
           
            $page = sanitize_text_field( $_GET['page'] );
            
            if($page == 'mo_revision_help'){
                include plugin_dir_path( dirname( __FILE__ ) ).'admin/partials/mo-revisions-help.php';
            }
        }
    }

    function mor_action() {
        
        global $db_object;

        if(isset($_POST['nonce'])){
            $nonce = sanitize_text_field($_POST['nonce']);

            if(wp_verify_nonce($nonce,'mo_rev_nonce') ){
            
                if(sanitize_text_field($_POST['button_val'])=='Approve'){
                    
                    global $post_exists;
                    $post_exists = true;
                    $db_object->mor_save_post_on_approve(sanitize_text_field($_POST['track_id']));
                    $post_exist = false;
                    wp_die('approved');
                }

                if(sanitize_text_field($_POST['button_val'])=='Deny'){
                    $db_object->mor_denied_changes(sanitize_text_field($_POST['track_id']));
                    wp_die('denied');
                }
            }
        }
    }

	function mor_handle_post_before_update($post_ID, $post_data) {
        global $post_exists;

        if(!$post_exists && $post_data['post_status']!='trash'){
           
            $post_before = get_post($post_ID);
            if($post_before && $post_before->post_parent == 0 && $post_before->post_status != 'auto-draft'){

                $user = get_current_user_id();
                $updated_by = get_user_by("ID", $user);

                global $db_object;
                $track_id = $db_object->mor_save_post_before_update($post_ID,$post_data,$user);
                
                $updates = "<head>  
                                <style>
                                table {
                                font-family: arial, sans-serif;
                                border-collapse: collapse;
                                width: 100%;
                                }
                                
                                td, th {
                                border: 1px solid #dddddd;
                                text-align: left;
                                padding: 8px;
                                }
                                
                                table.diff {
                                    -ms-word-break: break-all;
                                    word-break: break-all;
                                    word-wrap: break-word;
                                }
                                table.diff tr {
                                    background-color: transparent;
                                }
                                table.diff td,
                                table.diff th {
                                    font-family: Consolas, Monaco, monospace;
                                    font-size: 14px;
                                    line-height: 1.57142857;
                                    padding: 0.5em 0.5em 0.5em 2em;
                                    vertical-align: top;
                                    word-wrap: break-word;
                                }                    
                                table.diff .diff-deletedline del,
                                table.diff .diff-addedline ins {
                                    text-decoration: none;
                                }
                                table.diff .diff-deletedline {
                                    position: relative;
                                    background-color: #fcf0f1;
                                }
                                table.diff .diff-deletedline del {
                                    background-color: #ffabaf;
                                }
                                table.diff .diff-addedline {
                                    position: relative;
                                    background-color: #edfaef;
                                }
                                table.diff .diff-deletedline .dashicons,
                                table.diff .diff-addedline .dashicons {
                                    position: absolute;
                                    top: 0.85714286em;
                                    left: 0.5em;
                                    width: 1em;
                                    height: 1em;
                                    font-size: 1em;
                                    line-height: 1;
                                }
                                table.diff .diff-addedline .dashicons {
                                    /* Compensate the vertically non-centered plus glyph. */
                                    top: 0.92857143em;
                                }
                                table.diff .diff-addedline ins {
                                    background-color: #68de7c;
                                }
                                .button{
                                    
                                    margin:5px !important;
                                  }
                                </style>
                            </head>
                            <table>
                                <tr>
                                    <th>Page Title</th>
                                    <th>Updated By</th>
                                    <th>Last Update</th>
                                    <th>Changes</th>
                                    <th>Actions</th>
                                    
                                </tr>";

                    $s1 =  $post_before->post_content;
                    $s2 = $post_data["post_content"];

                    $args = array(
                        'title_left'  => 'Old Version',
                        'title_right' => 'New Version'
                    );

                    $changes = wp_text_diff($s1,$s2,$args);
                    $view_url = site_url( 'wp-admin/?page=mo_revision_manager', __FILE__ );
                    $updates = $updates."<tr>
                                        <td>{$post_data["post_title"]}</td>
                                        <td>{$updated_by->user_login}</td>
                                        <td>{$post_data["post_modified"]}</td>
                                        <td>{$changes}</td>
                                        <td>
                                        <a href='{$view_url}' target='_blank' > View </a>
                                        </td>
                                        </tr>"; 
                    
                
                $updates .= "</table>";

                //send mail
                $to = "test@test.com";
                $subject = "POST UPDATED ".$post_data["post_title"];
  
                global $mailer_object;
                $mailer_object->mor_send_mail($to,$subject,$updates);
            
                wp_send_json("POST SENT FOR REVIEW",200);

                exit;
            }
        }
    }

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function mor_enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/mo-revisions-admin.css', array(), $this->version, 'all' ); 
		wp_enqueue_style( "Datatable_CSS", plugin_dir_url( __FILE__ ) . 'css/jquery.dataTables.min.css', array(), $this->version, 'all' );
		wp_enqueue_style( "mor_admin_display_CSS", plugin_dir_url( __FILE__ ) . 'css/mo-revisions-admin-display.css', array(), $this->version, 'all' );
		wp_enqueue_style( "mor_admin_show_diff_CSS", plugin_dir_url( __FILE__ ) . 'css/mo-revisions-admin-show-diff.css', array(), $this->version, 'all' );
        
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */

	public function mor_enqueue_scripts() {
         
        wp_enqueue_script( "Datatable_JS", plugin_dir_url( __FILE__ ) . 'js/jquery.dataTables.min.js', array( 'jquery' ), $this->version, false );   
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/mo-revisions-admin.js', array( 'jquery' ), $this->version, false );
        
        if ( isset( $_GET['page']) ){
            if(isset($_POST['mo_rev_action'])){
                $nonce = sanitize_text_field($_POST['mo_rev_action']);
                if(wp_verify_nonce($nonce,'mo_rev_nonce') ){
                    if(isset($_POST['view'])){
                        wp_enqueue_script( 'ajax-script', plugin_dir_url( __FILE__ ) . 'js/mo-ajax.js', array('jquery') );
                        wp_localize_script( 'ajax-script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' )) );
                    }
                }
            }            
        }
    }

    
       

}
