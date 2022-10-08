<?php

namespace service;


use valueObjects\Email;

class EmailCreator {
	public static function send( Email $email ) {
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8\r\n";
		$headers .= "From: <" . $email->get_from()->get_email_address() . ">\r\n";

		return wp_mail( $email->get_to()->get_email_address(), $email->get_subject(), self::build( $email ), $headers );
	}

	private static function build( Email $email ) {
		$email_header = self::get_file_content( 'header.php', $email->get_params() );
		$email_body   = self::get_file_content( $email->get_template_name() . '-email.php', $email->get_params() );
		$email_footer = self::get_file_content( 'footer.php' );

		return $email_header . $email_body . $email_footer;
	}

	private static function get_file_content( $file_name, array $params = [] ) {
		$file_content = get_file_content( get_stylesheet_directory() . '/templates/emails/' . $file_name, $params );

		return $file_content ? $file_content : '';
	}
}