import { rm } from 'node:fs/promises';

const targets = [
  'assets/build/admin',
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
