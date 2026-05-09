import { mkdir, rename, rm } from 'node:fs/promises';
import { createRequire } from 'node:module';

const require = createRequire( import.meta.url );
const AdmZip = require( 'adm-zip' );

const sourceZip = 'remove-schema.zip';
const destinationZip = 'dist/remove-schema.zip';
const zip = new AdmZip( sourceZip );

for ( const path of [
	'remove-schema/README.md',
	'remove-schema/composer.json',
	'remove-schema/package.json',
] ) {
	zip.deleteFile( path );
}

for ( const entry of zip.getEntries() ) {
	if ( entry.entryName.endsWith( '/.DS_Store' ) || entry.entryName.includes( '/vendor/' ) ) {
		zip.deleteFile( entry.entryName );
	}
}

zip.writeZip( sourceZip );

await mkdir( 'dist', { recursive: true } );
await rm( destinationZip, { force: true } );
await rename( sourceZip, destinationZip );
