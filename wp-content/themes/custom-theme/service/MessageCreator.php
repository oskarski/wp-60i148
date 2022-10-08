<?php

namespace service;


class MessageCreator {
	public static function add( $text, $type = 'error' ) {
		global $_SESSION;

		$_SESSION['messages'][ $type ][] = $text;
	}

	public static function display() {
		global $_SESSION;
		$message = '';

		if ( isset( $_SESSION['messages']['error'] ) ) {
			foreach ( $_SESSION['messages']['error'] as $msg ) {
				$message .= '<li><span class="message error-message">' . $msg . '</span></li>';
			}
			unset( $_SESSION['messages']['error'] );
		}

		if ( isset( $_SESSION['messages']['success'] ) ) {
			foreach ( $_SESSION['messages']['success'] as $msg ) {
				$message .= '<li><span class="message success-message">' . $msg . '</span></li>';
			}
			unset( $_SESSION['messages']['success'] );
		}

		echo '<ul>' . $message . '</ul>';
	}
}