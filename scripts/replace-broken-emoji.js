// scripts/replace-broken-emoji.js
// Replace common mojibake placeholder sequences with reasonable emoji.
// This is a pragmatic fix when original bytes were lost and replaced by U+FFFD.

import { promises as fs } from 'fs';
import path from 'path';

const roots = [
  'resources/js',
  'resources/views',
];

const exts = new Set(['.vue', '.blade.php']);

// Map observed broken sequences to chosen emoji
const MAP = new Map([
  ['�x��', '🚀'],
  ['�xa�', '🎯'],
  ['�xR�', '🌳'],
  ['�x}�', '✨'],
  ['�x�', '🏅'],
  ['�S�', '⭐'],
  ['�xR�', '📈'], // sometimes used for progress
  ['�x}0', '⏱️'],
  ['�xR� Skill Tree', '🌳 Skill Tree'],
  ['�xR� Sua Skill Tree', '🌳 Sua Skill Tree'],
  ['�x�  Minhas Badges', '🏆  Minhas Badges'],
  ['�x� Minhas Badges', '🏆 Minhas Badges'],
  ['<div class="text-6xl mb-4">�x', '<div class="text-6xl mb-4">🔒'],
  ['Explorar Cursos �  ȑ', 'Explorar Cursos 📚'],
]);

async function walk(dir, out = []) {
  try {
    const entries = await fs.readdir(dir, { withFileTypes: true });
    for (const e of entries) {
      const full = path.join(dir, e.name);
      if (e.isDirectory()) {
        await walk(full, out);
      } else {
        out.push(full);
      }
    }
  } catch {}
  return out;
}

async function processFile(file) {
  const original = await fs.readFile(file, 'utf8');
  let updated = original;
  for (const [bad, good] of MAP.entries()) {
    if (updated.includes(bad)) {
      updated = updated.split(bad).join(good);
    }
  }
  // If the file still contains the replacement character, drop it in isolated cases
  if (updated.includes('�')) {
    // Remove isolated replacement character next to spaces
    updated = updated.replace(/\s*�\s*/g, ' ');
  }
  if (updated !== original) {
    await fs.writeFile(file, updated, 'utf8');
    console.log('Emoji replacements:', file);
    return true;
  }
  return false;
}

async function main() {
  const base = process.cwd();
  const files = [];
  for (const r of roots) {
    const dir = path.join(base, r);
    const f = await walk(dir);
    for (const file of f) {
      const ext = path.extname(file);
      const isBlade = file.endsWith('.blade.php');
      if (exts.has(ext) || isBlade) files.push(file);
    }
  }
  let changed = 0;
  for (const f of files) {
    const ok = await processFile(f);
    if (ok) changed++;
  }
  console.log(`Emoji replacement complete. Files changed: ${changed}`);
}

main().catch((e) => {
  console.error(e);
  process.exit(1);
});
