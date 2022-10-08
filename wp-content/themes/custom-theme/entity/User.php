<?php

namespace entity;


class User {
	public $id;
	public $username;
	public $email;
	public $display_name;
	public $first_name;
	public $last_name;
	public $nickname;
	public $description;
	public $website;
	public $role;


	public static function create_from_user_object( \WP_User $wp_user ) {
		$self      = new self;
		$user_meta = get_user_meta( $wp_user->ID );

		$self->id           = $wp_user->data->ID;
		$self->username     = $wp_user->data->user_login;
		$self->email        = $wp_user->data->user_email;
		$self->display_name = $wp_user->data->display_name;
		$self->first_name   = isset( $user_meta['first_name'] ) && isset( $user_meta['first_name'][0] ) ? $user_meta['first_name'][0] : '';
		$self->last_name    = isset( $user_meta['last_name'] ) && isset( $user_meta['last_name'][0] ) ? $user_meta['last_name'][0] : '';
		$self->nickname     = isset( $user_meta['nickname'] ) && isset( $user_meta['nickname'][0] ) ? $user_meta['nickname'][0] : '';
		$self->description  = isset( $user_meta['description'] ) && isset( $user_meta['description'][0] ) ? $user_meta['description'][0] : '';
		$self->website      = $wp_user->user_url;
		$self->role         = $self->get_highest_role( $wp_user->roles );

		return $self;
	}

	private function get_highest_role( $roles ) {
		$ROLES_ORDER  = [ 'subscriber', 'contributor', 'author', 'editor', 'administrator' ];
		$highest_role = 0;

		foreach ( $roles as $role ) {
			if ( ! in_array( $role, $ROLES_ORDER, true ) ) {
				throw new \InvalidArgumentException( 'Role: ' . $role . ' is not defined in ROLES_ORDER variable' );
			}
			$index = array_search( $role, $ROLES_ORDER, true );
			if ( $index > $highest_role ) {
				$highest_role = $index;
			}
		}

		return $ROLES_ORDER[ $highest_role ];
	}
}