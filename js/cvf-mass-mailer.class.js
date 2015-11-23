
/**
 *  cvfmm Class
 *
 *  @since	 		4.2.4
 *  @created		08/14/2015
 *	@author			Carl Victor Fontanos. (CVF)
 *	@authorurl		www.carlofontanos.com
 */

/**
 * Setup a cvfmm namespace to prevent JS conflicts.
 *
 * @type {
 * {
 * CVF_Mass_Mailer: CVF_Mass_Mailer
 * }
 * }
 */
var cvfmm = {
	
	/**
	 *
	 * CVF Mass Mailer
	 *
	 */
	CVF_Mass_Mailer: function () {
		
		/**
		 * Generate Email Preview
		 *
		 */
		this.cvf_generate_preview = function() {		
			$("body").on("click", ".generate_preview", function(e) {
				
				var email_subject = $('#mass_email_subject').val();
				var email_subject_color = $('#mass_email_subject_color').val();		
				var email_subject_show_hide = $('#mass_email_subject_show_hide').is( ':checked' ) ? 1 : 0;
				var email_content = tinymce.editors.massmailercontent.getContent();
							
				if(this.email_subject == ''){
					alert('Please enter a subject before generating a preview');
				} else if(email_content == '') {
					alert('Please enter a content before generating a preview');				
				} else {
					$(".preview-email").html('<p><img src = "' + cvfmmphp.loading_image + '" class = "loader" /></p>'); 
					var data = {
						'action': 'cvf_mm_preview_email',
						'cvf_mm_action': 'cvf_mm_preview_email_action',
						'subject': email_subject,
						'subject_color': email_subject_color,
						'subject_show_hide': email_subject_show_hide,
						'content': email_content
					};
					
					$.post(cvfmmphp.ajax_url, data, function(response) {
						element = $('.preview-email');		
						$(element).html($(response).fadeIn('slow'));
						$('.generate_preview').val('Regenerate Preview')
					});
				}
			});
		}
		
		/**
		 * AJAX Send Mass Email
		 *
		 */
		this.cvf_send_mass_email = function() {
			$("body").on("click", ".send_mass_email", function(e) {                        
                e.preventDefault(); 
                
				if (confirm('Are you sure you want to perform this action?')) {
					
					window.scrollTo(0, 0);
					
					var email_subject = $('#mass_email_subject').val();
					var email_subject_color = $('#mass_email_subject_color').val();
					var email_subject_show_hide = $('#mass_email_subject_show_hide').is( ':checked' ) ? 1 : 0;
					var email_target_role = $('#mass_email_target_role').val();
					var email_content = tinymce.editors.massmailercontent.getContent();
	
					if(email_subject == '') {
						alert('Email Subject can not be empty');
					} else if(email_content == '') {
						alert('Email Content can not be empty');					
					} else {
						$(".mass-email-response").html('<p><img src = "' + cvfmmphp.loading_image + '" class = "loader" /></p>'); 
						var data = {
							'action': 'cvf_mm_send_email',
							'cvf_mm_action': 'cvf_mm_send_email_action',
							'subject': email_subject,
							'subject_color': email_subject_color,
							'subject_show_hide': email_subject_show_hide,
							'target_role': email_target_role,
							'content': email_content
						};
						
						$.post(cvfmmphp.ajax_url, data, function(response) {
							element = $('.mass-email-response');											
							if(response == '1'){
								$(element).html('<div id="message" class="updated"><p><strong>Email successfully sent</strong></p></div>');
								tinyMCE.get('massmailercontent').setContent('');
								$('#mass_email_subject').val('');
							} else {
								$(element).html('<div id="message" class="error"><p><strong>Email not sent: No users were found on the selected target role</strong></p></div>');
							}
							
						});
					}
				}
            });
		}
	}
}

/**
 * When the document has been loaded...
 *
 */
var $ =jQuery.noConflict();
$(document).ready(function($) {
	
	// Load CVF_Mass_Mailer class methods
	mass_mailer = new cvfmm.CVF_Mass_Mailer();
	mass_mailer.cvf_generate_preview();
	mass_mailer.cvf_send_mass_email();
	
	$('#mass_email_subject_color').wpColorPicker();
	
});