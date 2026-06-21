import { spawn } from 'node:child_process';
import { fileURLToPath } from 'node:url';
import path from 'node:path';
import fs from 'node:fs';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const docsDir = path.resolve(__dirname, '..');
const htmlPath = path.join(docsDir, 'USER_MANUAL_AR.html');
const pdfPath = path.join(docsDir, 'USER_MANUAL_AR.pdf');

const chromePaths = [
    process.env.CHROME_PATH,
    'C:\\Program Files\\Google\\Chrome\\Application\\chrome.exe',
    'C:\\Program Files (x86)\\Google\\Chrome\\Application\\chrome.exe',
    `${process.env.LOCALAPPDATA}\\Google\\Chrome\\Application\\chrome.exe`,
    'C:\\Program Files\\Microsoft\\Edge\\Application\\msedge.exe',
].filter(Boolean);

const chrome = chromePaths.find((p) => fs.existsSync(p));

if (!chrome) {
    console.error('Chrome/Edge not found. Set CHROME_PATH or install Google Chrome.');
    process.exit(1);
}

if (!fs.existsSync(htmlPath)) {
    console.error(`HTML source not found: ${htmlPath}`);
    process.exit(1);
}

const fileUrl = `file:///${htmlPath.replace(/\\/g, '/')}`;

const args = [
    '--headless=new',
    '--disable-gpu',
    '--no-first-run',
    '--no-default-browser-check',
    '--run-all-compositor-stages-before-draw',
    '--virtual-time-budget=10000',
    `--print-to-pdf=${pdfPath}`,
    '--print-to-pdf-no-header',
    fileUrl,
];

const child = spawn(chrome, args, { stdio: 'inherit' });

child.on('close', (code) => {
    if (code === 0 && fs.existsSync(pdfPath)) {
        const stats = fs.statSync(pdfPath);
        console.log(`\nPDF generated: ${pdfPath}`);
        console.log(`Size: ${(stats.size / 1024).toFixed(1)} KB`);
    } else {
        console.error(`PDF generation failed (exit ${code})`);
        process.exit(code || 1);
    }
});
