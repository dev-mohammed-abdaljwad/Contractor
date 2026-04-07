#!/usr/bin/env node

/**
 * iDara PWA Icon Generator
 * Generates all required icon sizes with iDara green gradient branding
 * 
 * Usage:
 *   npm install sharp
 *   node scripts/generate-icons.js
 */

import sharp from 'sharp';
import path from 'path';
import fs from 'fs';
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// iDara Colors
const COLORS = {
  darkGreen: '#0a4f14',
  midGreen: '#1D9E75',
  lightGreen: '#a7f3d0',
  white: '#ffffff'
};

// Icon sizes to generate
const SIZES = [
  { name: 'icon-72x72.png', size: 72, maskable: false },
  { name: 'icon-96x96.png', size: 96, maskable: false },
  { name: 'icon-128x128.png', size: 128, maskable: false },
  { name: 'icon-144x144.png', size: 144, maskable: false },
  { name: 'icon-152x152.png', size: 152, maskable: false },
  { name: 'icon-192x192.png', size: 192, maskable: false },
  { name: 'icon-384x384.png', size: 384, maskable: false },
  { name: 'icon-512x512.png', size: 512, maskable: false },
  { name: 'icon-192x192-maskable.png', size: 192, maskable: true },
  { name: 'icon-512x512-maskable.png', size: 512, maskable: true }
];

// Output directory
const OUTPUT_DIR = path.join(__dirname, '../public/icons');

/**
 * Create SVG icon with iDara branding
 * For standard icons: gradient background with iD text
 * For maskable: just the iD for safe zone
 */
function createIconSvg(size, maskable = false) {
  const padding = Math.floor(size * 0.1);
  const centerX = size / 2;
  const centerY = size / 2;
  
  if (maskable) {
    // Maskable icon: Keep content in center circle (42% radius)
    const safeRadius = Math.floor(size * 0.42);
    const fontSize = Math.floor(size * 0.5);
    
    return `
      <svg width="${size}" height="${size}" xmlns="http://www.w3.org/2000/svg">
        <!-- Safe zone for adaptive icons -->
        <defs>
          <radialGradient id="grad-${size}-m" cx="50%" cy="50%" r="50%">
            <stop offset="0%" style="stop-color:#1D9E75;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#0a4f14;stop-opacity:1" />
          </radialGradient>
        </defs>
        
        <!-- Solid background -->
        <rect width="${size}" height="${size}" fill="${COLORS.midGreen}"/>
        
        <!-- iD text centered -->
        <text x="${centerX}" y="${centerY + fontSize/3}" font-family="Arial, sans-serif" font-size="${fontSize}" font-weight="bold" text-anchor="middle" fill="${COLORS.white}">iD</text>
      </svg>
    `;
  } else {
    // Standard icon: gradient background with prominent iD text
    const fontSize = Math.floor(size * 0.45);
    
    return `
      <svg width="${size}" height="${size}" xmlns="http://www.w3.org/2000/svg">
        <defs>
          <linearGradient id="grad-${size}" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:${COLORS.darkGreen};stop-opacity:1" />
            <stop offset="100%" style="stop-color:${COLORS.midGreen};stop-opacity:1" />
          </linearGradient>
        </defs>
        
        <!-- Gradient background -->
        <rect width="${size}" height="${size}" fill="url(#grad-${size})"/>
        
        <!-- iD text -->
        <text x="${centerX}" y="${centerY + fontSize/3}" font-family="Arial, sans-serif" font-size="${fontSize}" font-weight="bold" text-anchor="middle" fill="${COLORS.white}">iD</text>
      </svg>
    `;
  }
}

/**
 * Generate all icon sizes
 */
async function generateIcons() {
  // Create output directory if it doesn't exist
  if (!fs.existsSync(OUTPUT_DIR)) {
    fs.mkdirSync(OUTPUT_DIR, { recursive: true });
    console.log(`✓ Created directory: ${OUTPUT_DIR}`);
  }

  console.log('\n🎨 Generating iDara PWA Icons...\n');

  let successCount = 0;
  let errorCount = 0;

  for (const icon of SIZES) {
    try {
      // Create SVG
      const svg = createIconSvg(icon.size, icon.maskable);
      const svgBuffer = Buffer.from(svg);

      // Convert to PNG
      const outputPath = path.join(OUTPUT_DIR, icon.name);
      
      await sharp(svgBuffer)
        .png()
        .toFile(outputPath);

      const type = icon.maskable ? '🎭 Maskable' : '📱 Standard';
      console.log(`✓ ${type}: ${icon.name} (${icon.size}x${icon.size})`);
      successCount++;

    } catch (error) {
      console.error(`✗ Failed to generate ${icon.name}: ${error.message}`);
      errorCount++;
    }
  }

  console.log('\n' + '='.repeat(50));
  console.log(`\n✓ Generated ${successCount} icon(s)`);
  
  if (errorCount > 0) {
    console.log(`✗ Failed to generate ${errorCount} icon(s)`);
  }

  if (successCount === SIZES.length) {
    console.log('\n✅ All icons generated successfully!\n');
    console.log('📁 Icons saved to: ' + OUTPUT_DIR);
    console.log('\n💡 Next steps:');
    console.log('   1. Test with: npm run dev');
    console.log('   2. Open app in Chrome');
    console.log('   3. Run Lighthouse audit (DevTools → Lighthouse)');
    console.log('   4. Check PWA score (should be 90+)\n');
    console.log('📝 Note: These are placeholder gradient icons.');
    console.log('   For higher quality icons, use:');
    console.log('   https://www.pwa-asset-generator.firebaseapp.com/\n');
  }
}

// Run generator
generateIcons().catch(error => {
  console.error('Fatal error:', error);
  process.exit(1);
});
