<?php

/**
 *  User Functions
 *
 * @category   Functions
 * @version    1.0.0
 * @since      1.0.0
 */

/**
 * get user roles
 *
 * @param int|\WP_User $user
 *
 * @return array
 */
function dcGetUserRoles ($user = 0) : array {
	$userObject = null;

	if ( $user === 0 && is_user_logged_in() ) {
		$userObject = wp_get_current_user();
	} else if ( is_numeric($user) ) {
		$userObject = get_user_by('ID', $user);
	}

	if ( !$userObject instanceof WP_User ) {
		return ['guest'];
	}

	return $userObject->roles;
}

/**
 * check user has role's
 *
 * @param int|\WP_User $user
 *
 * @param array        $roles
 *
 * @return bool
 */
function dcUserHasRoles ($user = 0, $roles = []) : bool {
	$userRoles = dcGetUserRoles($user);

	if ( empty($userRoles) || empty($roles) ) {
		return false;
	}

	foreach ( $roles as $role ) {
		if ( !in_array($role, $userRoles, true) ) {
			return false;
		}
	}

	return true;
}

/**
 * check user in role's
 *
 * @param int|\WP_User $user
 *
 * @param array        $roles
 *
 * @return bool
 */
function dcUserInRoles ($user = 0, $roles = []) : bool {
	$userRoles = dcGetUserRoles($user);

	if ( empty($userRoles) ) {
		return false;
	}
	if ( empty($roles) ) {
		return true;
	}

	foreach ( $roles as $role ) {
		if ( in_array($role, $userRoles, true) ) {
			return true;
		}
	}

	return false;
}

/**
 * Get all Available User Roles
 *
 * @return array
 */
function dcGetUsersRoles () : array {
	$wpRoles = new WP_Roles();
	$names   = $wpRoles->get_names();

	if ( empty($names) ) {
		return [];
	}

	foreach ( $names as $key => $value ) {
		$names[$key] = translate_user_role($value);
	}

	$names['guest'] = __('Guest', THEME_TEXTDOMAIN);

	return $names;
}