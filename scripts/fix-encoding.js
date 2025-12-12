// scripts/fix-encoding.js
// Detect and fix mojibake (UTF-8 text misinterpreted as Latin-1) in source files.
// It converts files containing sequences like "Ã", "â€™", "â€œ" back to proper UTF-8.

import { promises as fs } from 'fs';
import path from 'path';

const roots = [
  'resources/js',
  'resources/views',
  'docs',
];

const exts = new Set(['.vue', '.blade.php', '.md', '.js', '.ts', '.css']);

// Common mojibake indicators when UTF-8 bytes are decoded as Latin-1
const MOJIBAKE_PATTERNS = [
  'Ã', // e.g., Ã¡ Ã© Ã£ Ã§
  'Â', // stray accent marker
  'â€”', 'â€“', // dashes
  'â€\x9d', 'â€œ', 'â€™', 'â€˜', // quotes
  'ðŸ', // emoji prefix sequences
];

function looksLikeMojibake(text) {
  return MOJIBAKE_PATTERNS.some((p) => text.includes(p));
}

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
  } catch (err) {
    // ignore missing folders
  }
  return out;
}

async function fixFile(file) {
  try {
    const raw = await fs.readFile(file, 'utf8');
    if (!looksLikeMojibake(raw)) return false;
    // Convert by interpreting current text as Latin-1 bytes, then decode as UTF-8
    const fixed = Buffer.from(raw, 'latin1').toString('utf8');
    if (fixed !== raw) {
      await fs.writeFile(file, fixed, 'utf8');
      return true;
    }
  } catch (err) {
    // skip binary or unreadable files
  }
  return false;
}

async function main() {
  const base = process.cwd();
  const candidates = [];
  for (const r of roots) {
    const dir = path.join(base, r);
    const files = await walk(dir);
    for (const f of files) {
      const ext = path.extname(f);
      // handle blade.php as a pseudo extension
      const isBlade = f.endsWith('.blade.php');
      if (exts.has(ext) || isBlade) {
        candidates.push(f);
      }
    }
  }

  let changed = 0;
  for (const f of candidates) {
    const ok = await fixFile(f);
    if (ok) {
      console.log('Fixed encoding:', f);
      changed++;
    }
  }

  console.log(`Encoding fix complete. Files changed: ${changed}`);
}

main().catch((e) => {
  console.error(e);
  process.exit(1);
});
