<?php

// Import csv data to wpform entries.

if ( isset( $_POST['wpfmm_import_data_submit'] ) && isset( $_POST['wpfmm_file_upload_nonce_field'] ) ) {
	$upload_file = $_FILES['wpfmm_upload_input']; // phpcs:ignore
	import_csv_to_wp_database( $upload_file )
}

/**
 * CSV Import
 *
 * @param  array $csv_file CSV file.
 * @since  1.0.0
 */
function import_csv_to_wp_database( array $csv_file ) {
	global $wp_filesystem;

	if ( ! $wp_filesystem ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		WP_Filesystem();
	}

	$file_path = $csv_file['tmp_name'];

	if ( $wp_filesystem->exists( $file_path ) ) {
		$file_contents = $wp_filesystem->get_contents( $file_path );
		// Normalize the newline characters to "\n".
		$file_contents = str_replace( array( "\r\n", "\r" ), "\n", $file_contents );
		$lines         = explode( "\n", $file_contents );
		$lines         = array_filter( $lines, fn( $value ) => ! is_null( $value ) && '' !== $value );

		$counter = 0;
		foreach ( $lines as $line ) {
			if ( 0 === $counter++ ) { // Skip the header.
				continue;
			}

			$data     = str_getcsv( $line );
			$entry_id = $this->add_wpforms_entry_static( $data );

			if ( is_wp_error( $entry_id ) ) {
				return self::$notice->set_notice(
					'wrong_file',
					__( 'Please upload correct file!', 'wpf-mail-manager' ),
					'error'
				);
			}
		}

		return self::$notice->set_notice(
			'imp_success',
			max( 0, $counter - 1 ) . __( ' Data imported successfully!', 'wpf-mail-manager' )
		);
	}
}

/**
 * Form entry export to direct plugin entries
 *
 * @since  1.0.0
 *
 * @param  array $data csv to array per line.
 *
 * @return int $entry_id Entry ID
 */
function add_wpforms_entry_static( array $data ) {

	if ( empty( $data ) ) {
		return self::$notice->set_notice(
			'wrong_file',
			__( 'Please upload correct file!', 'wpf-mail-manager' ),
			'error'
		);
	}

	$fields = array(
		'1' => array(
			'name'  => 'Email',
			'value' => $data[1],
			'id'    => 1,
			'type'  => 'email',
		),
		'2' => array(
			'name'   => 'Name',
			'value'  => $data[0],
			'id'     => 2,
			'type'   => 'name',
			'first'  => '',
			'middle' => '',
			'last'   => '',
		),
	);

	$entry = array(
		'post_id' => 0,
		'user_id' => get_current_user_id(),
		'status'  => 'pending',
	);

	if ( ! function_exists( 'wpforms' ) ) {
		return self::$notice->set_notice(
			'wrong_file',
			__( 'Please install WPForms lite!', 'wpf-mail-manager' ),
			'error'
		);
	}

	$entry_id = wpforms()->process->entry_save( $fields, $entry, 38054 );

	return $entry_id;
}
