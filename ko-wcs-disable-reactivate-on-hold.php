<?php
/**
 * Plugin Name: KO – WCS Disable Reactivate for On-Hold Subscriptions
 * Description: Removes the customer-facing "Reactivate" action for subscriptions that are On Hold (WooCommerce Subscriptions).
 * Version: 1.0.0
 * Author: KO
 * License: GPLv2 or later
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Only run if WooCommerce Subscriptions is active.
 */
function ko_wcs_drh_is_wcs_active() {
	return function_exists( 'wcs_get_subscriptions' ) || class_exists( 'WC_Subscription' );
}

/**
 * Remove "Reactivate" from the subscription actions shown on the View Subscription screen.
 * Filter name is from WooCommerce Subscriptions.
 */
add_filter( 'wcs_view_subscription_actions', function( $actions, $subscription ) {

	if ( ! ko_wcs_drh_is_wcs_active() ) {
		return $actions;
	}

	if ( ! $subscription || ! is_a( $subscription, 'WC_Subscription' ) ) {
		return $actions;
	}

	// If subscription is on-hold, remove customer ability to reactivate.
	if ( $subscription->has_status( 'on-hold' ) ) {
		unset( $actions['reactivate'] );
	}

	return $actions;

}, 20, 2 );

/**
 * Also remove "Reactivate" from the My Subscriptions list actions (belt + suspenders).
 * Depending on theme/templates, this may be the one being used.
 */
add_filter( 'woocommerce_my_account_my_subscriptions_actions', function( $actions, $subscription ) {

	if ( ! ko_wcs_drh_is_wcs_active() ) {
		return $actions;
	}

	if ( ! $subscription || ! is_a( $subscription, 'WC_Subscription' ) ) {
		return $actions;
	}

	if ( $subscription->has_status( 'on-hold' ) ) {
		unset( $actions['reactivate'] );
	}

	return $actions;

}, 20, 2 );