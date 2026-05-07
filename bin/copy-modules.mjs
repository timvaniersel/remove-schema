import { cp, mkdir } from 'node:fs/promises';

await mkdir( 'assets/build/modules', { recursive: true } );
await cp( 'assets/src/modules', 'assets/build/modules', { recursive: true } );
