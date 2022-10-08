<?php

namespace repository;


use entity\User;

class UserRepository {
	public static function find( $id ) {
		$self = new static();

		$user = get_user_by( 'id', $id );
		if ( $user ) {
			return $self->create_object( $user );
		}

		return null;
	}

	private function create_object( $user_object ) {
		return User::create_from_user_object( $user_object );
	}
}