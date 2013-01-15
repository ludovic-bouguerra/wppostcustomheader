<?php

	/**
	 * @package 
	 * @version 1.0
	 */
	/*
	*	Plugin Name: Wordpress Custom Post Header
	*	Plugin URI: http://www.ludovicbouguerra.fr/wordpress/custompostheader/
	*	Description: 
	*	Author: Ludovic Bouguerra
	*	Version: 01
	*	Author URI: http://www.ludovicbouguerra.fr/
	*/

	function lb_wordpress_custom_header_view(){
		$header = get_post_meta(get_the_id(), "lb_wordpress_custom_header", true);
		if ($header){
			echo html_entity_decode($header);

		}

	}

	add_action('wp_head', 'lb_wordpress_custom_header_view');


	function lb_wordpress_custom_admin_init(){
		add_meta_box("header_init", "Custom header", "lb_wordpress_custom_header_init", "post" , "normal", "low");

	}
	add_action( 'admin_init', 'lb_wordpress_custom_admin_init', 1 );

	function lb_wordpress_custom_header_init(){
		//La fonction qui affiche notre champs personnalisé dans l'administration
	    global $post;
	    $header = get_post_custom($post->ID); //fonction pour récupérer la valeur de notre champ
	    $header = $header["lb_wordpress_custom_header"][0];
	    
	    ?>
	     <textarea name="lb_wordpress_custom_header_textarea" id="" cols="80" rows="10"><?php echo $header; ?></textarea>
	     <?php
	}

	

	function lb_wordpress_custom_save_post(){
		  // verify if this is an auto save routine. 
		  // If it is our form has not been submitted, so we dont want to do anything
		  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
		      return;

		  // verify this came from the our screen and with proper authorization,
		  // because save_post can be triggered at other times

		  /*if ( !wp_verify_nonce( $_POST['lb_wordpress_custom_header_textarea'], plugin_basename( __FILE__ ) ) )
		      return;*/

		  
		  // Check permissions
		  if ( 'page' == $_POST['post_type'] ) 
		  {
		    if ( !current_user_can( 'edit_page', $post_id ) )
		        return;
		  }
		  else
		  {
		    if ( !current_user_can( 'edit_post', $post_id ) )
		        return;
		  }

		  // OK, we're authenticated: we need to find and save the data

		  //if saving in a custom table, get post_ID
		  $post_ID = $_POST['post_ID'];
		  //sanitize user input
		  $mydata = sanitize_text_field( htmlentities($_POST['lb_wordpress_custom_header_textarea']) );


		  // Do something with $mydata 
		  // either using 
		  add_post_meta($post_ID, 'lb_wordpress_custom_header', $mydata, true) or
		    update_post_meta($post_ID, 'lb_wordpress_custom_header', $mydata);
		  // or a custom table (see Further Reading section below)

	}
	/* Do something with the data entered */
	add_action( 'save_post', 'lb_wordpress_custom_save_post' );