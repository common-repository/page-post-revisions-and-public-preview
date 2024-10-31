<?php

/**
 * Fired during plugin activation
 *
 * @link       https://miniorange.com
 * @since      1.0.0
 *
 * @package    Mo_Revisions
 * @subpackage Mo_Revisions/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Mo_Revisions
 * @subpackage Mo_Revisions/includes
 * @author     miniOrange <info@xecurify.com>
 */
class Mo_Revisions_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */

	public static function activate() {
		
		global $wpdb;

        

        $wpdb->query($wpdb->prepare(
            "CREATE TABLE wp_mor_data (
            
            track_id INT PRIMARY KEY AUTO_INCREMENT,
            post_author INT NOT NULL ,
            page_content LONGTEXT ,
            page_title VARCHAR(100) NOT NULL ,
            post_status VARCHAR(20),
            post_type VARCHAR(20),
            comment_status VARCHAR(20),
            ping_status VARCHAR(20), 
            page_slug VARCHAR(100) NOT NULL ,  
            is_informed TINYINT,
            modified_at DATETIME NULL DEFAULT NULL ,
            parent_id INT,
            guid VARCHAR(255),
            approve_status VARCHAR(20) 
        
        ) ENGINE = InnoDB;"
        ));

	}

}
