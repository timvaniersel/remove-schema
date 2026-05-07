#!/usr/bin/env php
<?php
/**
 * Rename boilerplate placeholders.
 *
 * @package TimVanIersel\RemoveSchema
 */

declare(strict_types=1);

$options = getopt(
	'',
	array(
		'dry-run',
		'remove-schema:',
		'slug:',
		'namespace:',
		'prefix:',
	)
);

$required = array( 'remove-schema', 'slug', 'namespace', 'prefix' );

foreach ( $required as $required_option ) {
	if ( empty( $options[ $required_option ] ) || ! is_string( $options[ $required_option ] ) ) {
		fwrite( STDERR, "Missing required option --{$required_option}\n" );
		exit( 1 );
	}
}

$root         = realpath( dirname( __DIR__ ) );
$dry_run      = isset( $options['dry-run'] );
$remove_schema  = $options['remove-schema'];
$slug         = $options['slug'];
$namespace    = trim( $options['namespace'], '\\' );
$prefix       = $options['prefix'];
$text_domain  = $slug;
$plugin_file  = $root . '/remove-schema.php';
$replacement_map = array(
	'Remove Schema'          => $remove_schema,
	'Remove Schema' => $remove_schema,
	'remove-schema'          => $slug,
	'remove_schema'          => $prefix,
	'Vendor\\TimVanIersel\RemoveSchema'   => $namespace,
	'timvaniersel/removeschema'   => strtolower( str_replace( '\\', '/', $namespace ) ),
);

$iterator = new RecursiveIteratorIterator(
	new RecursiveDirectoryIterator(
		$root,
		FilesystemIterator::SKIP_DOTS
	)
);

$updated_files = array();

foreach ( $iterator as $file ) {
	if ( ! $file instanceof SplFileInfo || ! $file->isFile() ) {
		continue;
	}

	$path = $file->getPathname();

	if ( str_contains( $path, DIRECTORY_SEPARATOR . '.git' . DIRECTORY_SEPARATOR ) ) {
		continue;
	}

	if ( str_contains( $path, DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR ) ) {
		continue;
	}

	if ( str_contains( $path, DIRECTORY_SEPARATOR . 'node_modules' . DIRECTORY_SEPARATOR ) ) {
		continue;
	}

	$contents = file_get_contents( $path );

	if ( false === $contents ) {
		fwrite( STDERR, "Could not read {$path}\n" );
		exit( 1 );
	}

	$updated = strtr( $contents, $replacement_map );

	if ( $updated === $contents ) {
		continue;
	}

	$updated_files[] = $path;

	if ( ! $dry_run ) {
		file_put_contents( $path, $updated );
	}
}

$target_file = $root . '/remove-schema.php';
$renamed_file = $root . '/' . $slug . '.php';

if ( ! $dry_run && file_exists( $target_file ) ) {
	rename( $target_file, $renamed_file );
	$updated_files[] = $renamed_file;
}

fwrite(
	STDOUT,
	sprintf(
		"%s %d file(s).\n",
		$dry_run ? 'Would update' : 'Updated',
		count( $updated_files )
	)
);

if ( ! $dry_run ) {
	fwrite(
		STDOUT,
		sprintf(
			"Remember to review plugin headers, text domain, and package metadata for \"%s\" (%s).\n",
			$remove_schema,
			$text_domain
		)
	);
}
