import { readdir, readFile, writeFile } from 'node:fs/promises';
import { join } from 'node:path';

const buildDirectory = 'assets/build';
const directAccessGuard = `/**
 * Generated asset metadata.
 *
 * @package TimVanIersel\\RemoveSchema
 */

if ( ! defined( 'ABSPATH' ) ) {
\texit;
}

`;

async function findAssetMetadataFiles( directory ) {
	const entries = await readdir( directory, { withFileTypes: true } );
	const files = await Promise.all(
		entries.map( async ( entry ) => {
			const path = join( directory, entry.name );

			if ( entry.isDirectory() ) {
				return findAssetMetadataFiles( path );
			}

			return entry.isFile() && entry.name.endsWith( '.asset.php' ) ? [ path ] : [];
		} )
	);

	return files.flat();
}

const assetMetadataFiles = await findAssetMetadataFiles( buildDirectory );

await Promise.all(
	assetMetadataFiles.map( async ( assetMetadataFile ) => {
		const contents = await readFile( assetMetadataFile, 'utf8' );

		if ( contents.includes( "defined( 'ABSPATH' )" ) ) {
			return;
		}

		await writeFile(
			assetMetadataFile,
			contents.replace( /^<\?php\s*/, `<?php\n${ directAccessGuard }` )
		);
	} )
);
