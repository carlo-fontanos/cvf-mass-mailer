<?php 
/**
 *  WP_Mass_Mailer
 *
 *  @description 	Main Class for WP Mass Mailer Plugin
 *  @since	 		4.2.4
 *  @created		08/14/2015
 *	@author			Carl Victor Fontanos. (CVF)
 *	@authorurl		www.carlofontanos.com
 */
 
class WP_Mass_Mailer {
    
    public function __construct() {		
       
		add_action( 'admin_menu', array( $this, 'cvf_wpmm_register_mass_email_tool' ) );
        add_action( 'wp_ajax_cvf_wpmm_send_email', array( $this, 'cvf_wpmm_send_email' ) );
        add_action( 'wp_ajax_cvf_wpmm_preview_email', array( $this, 'cvf_wpmm_preview_email' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'cvf_wpmm_load_scripts' ) );
		
		add_filter( 'wp_mail_content_type', array( $this, 'cvf_wpmm_set_email_content_type' ) );
			
		$this->cvf_wpmm_localize = array(
			'ajax_url'		=> admin_url( 'admin-ajax.php' ),
			'loading_image'	=> WP_MASS_MAILER_PLUGIN_URL . '/img/loading.gif'
		);
    }
    
    public function cvf_wpmm_load_scripts() {
		
		wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style( 'wpmm-css', WP_MASS_MAILER_PLUGIN_URL . '/css/admin.css', false, "1.0.0", false);
		wp_enqueue_script( 'wpmm-js', WP_MASS_MAILER_PLUGIN_URL . '/js/wp-mass-mailer.class.js', array('jquery', 'wp-color-picker') );
				
		wp_localize_script( 'wpmm-js', 'wpmmphp', $this->cvf_wpmm_localize );
    
	}
		
	public function cvf_wpmm_set_email_content_type( $content_type ) {
		
		return 'text/html';
		
	}
    
    public function cvf_wpmm_register_mass_email_tool() {
		
        add_submenu_page( 'tools.php', 'Mass Mailer', 'Mass Mailer', 'administrator', 'mass-mailer', 'WP_Mass_Mailer::cvf_wpmm_admin_mass_mailer' );
    }
	
	public function cvf_wpmm_preview_email() {
		
		if( isset( $_POST['cvf_wpmm_action'] ) && $_POST['cvf_wpmm_action'] == 'cvf_wpmm_preview_email_action' ){
			
			echo $this->cvf_wpmm_email_template($_POST['subject'], $_POST['subject_color'], $_POST['subject_show_hide'], $_POST['content']);
			
		}
		
		exit();
	}
	 
    public static function cvf_wpmm_admin_mass_mailer(){
       
		?>     
		<h2><?php _e('Mass Mailer', 'wp-mass-mailer'); ?></h2>
		<p><?php _e('by', 'wp-mass-mailer'); ?>: <?php _e( WP_MASS_MAILER_AUTHOR ); ?> | <a href = "http://<?php  _e( WP_MASS_MAILER_AUTHOR_URL ); ?>" target = "_blank"><?php  _e( WP_MASS_MAILER_AUTHOR_URL ); ?></a></p>
        <span class = "mass-email-response"></span>
        
		<table class="form-table mass-mailer-editor">
            <tbody>				
				<tr>
                    <th><label for="mass_email_subject"><?php _e('Email Subject', 'wp-mass-mailer'); ?></label></th>
                    <td>
						<input type="text" name="mass_email_subject" id="mass_email_subject" placeholder = "<?php _e('Ex. Breaking News Alert', 'wp-mass-mailer'); ?>" class="regular-text" />
						<input type="text" value="#224D7B" id="mass_email_subject_color" />						
					</td>
                </tr>
				<tr>
					<th></th>
					<td><label for="mass_email_subject_show_hide"><input name="mass_email_subject_show_hide" type="checkbox" checked="checked" id="mass_email_subject_show_hide" value="false"> <?php _e('Show Email Subject on the Email Content', 'wp-mass-mailer'); ?></label></td>
				</tr>
				<tr>
					<th><?php _e('Target Role', 'wp-mass-mailer'); ?></th>
					<td>
						<select id = "mass_email_target_role">
							<option value = "all_roles"><?php _e('All Roles', 'wp-mass-mailer'); ?></option>
							<?php wp_dropdown_roles(); ?>
						</select>
					</td>
				</tr>
                <tr>
                    <th><label><?php _e('Email Content', 'wp-mass-mailer'); ?></label></th>
                    <td><?php wp_editor( '', 'massmailercontent', array() ); ?></td>
                </tr>
				<tr>
                    <th><label><?php _e('Email Preview', 'wp-mass-mailer'); ?></label></th>
                    <td>
						<input type = "submit" class = "generate_preview button" value = "<?php _e('Generate Preview', 'wp-mass-mailer'); ?>" />
						<div class = "preview-email"></div>		
					</td>
                </tr>
            </tbody>
        </table>
		
        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary send_mass_email" value="<?php _e('Send Email', 'wp-mass-mailer'); ?>" /></p>      
        <?php
        
    }
	
	public function cvf_wpmm_email_template( $email_subject, $email_suject_color, $email_subject_show_hide, $email_content ) {
		
		ob_start(); ?>
				
		<table style="width:100%; color: #333; border: 1px solid #ddd; border-bottom: 3px solid #ddd" border = "0" >
			<?php if( $email_subject_show_hide == 1): ?>
			<tr>
				<th colspan = "2" style = "font-size: 25px; padding: 8px 0; background: <?php echo $email_suject_color; ?>; color: #fff; text-align: center;" class = "email-subject"><?php echo $email_subject; ?></th>
			</tr>
			<?php endif; ?>
			<tr>
				<td style = "padding: 10px;" class = "email-content"><?php echo $email_content; ?></td>
			</tr>
		</table>
		
		<?php
		
		$message = ob_get_contents();			
		ob_end_clean();
		
		return stripslashes($message);
	}
    
    public function cvf_wpmm_send_email(){
               
        if( isset( $_POST['cvf_wpmm_action'] ) && $_POST['cvf_wpmm_action'] == 'cvf_wpmm_send_email_action' ){
            
			$role = '';			
			if( isset( $_POST['target_role'] ) && $_POST['target_role'] != 'all_roles'){
				$role = $_POST['target_role'];
			}	
			$user_group = get_users( 'role='.$role );
			
			if( $user_group ) {
				foreach( $user_group as $key => $user ) {
					
					$from = get_option('admin_email');
					$headers = 'From: ' . get_bloginfo('name') . ' <"' . $from . '">';
													
					wp_mail($user->user_email, $_POST['subject'], $this->cvf_wpmm_email_template( $_POST['subject'], $_POST['subject_color'], $_POST['subject_show_hide'], $_POST['content'] ) , $headers);
					
				}
				echo '1';
				
			} else {
				echo '0';
			}
        }
        exit();
    }
	
} new WP_Mass_Mailer();