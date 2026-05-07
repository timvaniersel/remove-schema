import { mkdir, rename, rm } from 'node:fs/promises';

await mkdir( 'dist', { recursive: true } );
await rm( 'dist/remove-schema.zip', { force: true } );
await rename( 'remove-schema.zip', 'dist/remove-schema.zip' );
