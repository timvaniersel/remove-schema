import { mkdir, rename, rm } from 'node:fs/promises';
import { createRequire } from 'node:module';

const require = createRequire( import.meta.url );
const AdmZip = require( 'adm-zip' );

const sourceZip = 'remove-schema.zip';
const destinationZip = 'dist/remove-schema.zip';
const zip = new AdmZip( sourceZip );

zip.deleteFile( 'remove-schema/README.md' );
zip.writeZip( sourceZip );

await mkdir( 'dist', { recursive: true } );
await rm( destinationZip, { force: true } );
await rename( sourceZip, destinationZip );
