import { rm } from 'node:fs/promises';

const targets = [
  'assets/build/admin',
  'assets/build/public',
  'assets/build/blocks',
  'assets/build/modules',
  'dist',
];

await Promise.all(
  targets.map( ( target ) =>
    rm( target, {
      force: true,
      recursive: true,
    } )
  )
);
