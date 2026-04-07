/**
 * PWA Installation Prompt Debugger
 * Check what's preventing the install prompt from appearing
 * 
 * Run this in browser console: F12 → Console tab, then paste this code
 */

console.log('🔍 PWA Installation Prompt Debugger\n');

// 1. Check Service Worker
console.log('1️⃣ Checking Service Worker...');
if ('serviceWorker' in navigator) {
  navigator.serviceWorker.getRegistrations().then(regs => {
    if (regs.length > 0) {
      console.log('✅ Service Worker registered:', regs[0].scope);
      console.log('   State:', regs[0].active ? 'ACTIVATED' : 'NOT ACTIVE');
    } else {
      console.log('❌ No Service Worker found - this is the problem!');
    }
  });
} else {
  console.log('❌ Service Worker not supported');
}

// 2. Check Manifest
console.log('\n2️⃣ Checking Web App Manifest...');
fetch('/manifest.json')
  .then(r => r.json())
  .then(manifest => {
    console.log('✅ Manifest loaded successfully');
    console.log('   Name:', manifest.name);
    console.log('   Display:', manifest.display);
    console.log('   Icons count:', manifest.icons?.length || 0);
    if (manifest.icons?.length === 0) {
      console.log('   ⚠️ WARNING: No icons in manifest!');
    }
  })
  .catch(e => console.log('❌ Manifest not found or invalid:', e.message));

// 3. Check HTTPS (required for production)
console.log('\n3️⃣ Checking HTTPS Status...');
if (location.protocol === 'https:') {
  console.log('✅ HTTPS enabled (required for production)');
} else if (location.hostname === 'localhost' || location.hostname === '127.0.0.1') {
  console.log('✅ Localhost detected (HTTPS not required for testing)');
} else {
  console.log('❌ Not HTTPS - installation won\'t work on production!');
  console.log('   Need: https://' + location.hostname);
}

// 4. Check onbeforeinstallprompt event
console.log('\n4️⃣ Checking Installation Prompt Event...');
let deferredPrompt = null;

window.addEventListener('beforeinstallprompt', (e) => {
  console.log('✅ beforeinstallprompt event fired!');
  deferredPrompt = e;
  console.log('   You can now show install button');
  // Try to show prompt
  e.prompt();
});

window.addEventListener('appinstalled', () => {
  console.log('✅ App installed successfully!');
});

// Manual trigger
console.log('   (Waiting for beforeinstallprompt event...)');
setTimeout(() => {
  if (!deferredPrompt) {
    console.log('❌ beforeinstallprompt event did NOT fire');
    console.log('   This means PWA requirements not met');
  }
}, 3000);

// 5. Check manifest.json icon references
console.log('\n5️⃣ Checking Icon Files...');
fetch('/manifest.json')
  .then(r => r.json())
  .then(manifest => {
    if (manifest.icons) {
      manifest.icons.forEach((icon, idx) => {
        fetch(icon.src, { method: 'HEAD' })
          .then(r => {
            if (r.ok) {
              console.log(`   ✅ Icon ${idx + 1}: ${icon.src} (${icon.sizes})`);
            } else {
              console.log(`   ❌ Icon ${idx + 1}: ${icon.src} - NOT FOUND (404)`);
            }
          })
          .catch(e => console.log(`   ❌ Icon ${idx + 1}: ${icon.src} - ERROR: ${e.message}`));
      });
    }
  })
  .catch(e => console.log('❌ Could not check icons:', e.message));

// 6. Log what's required for install
console.log('\n📋 Requirements for Install Prompt:\n');
const requirements = [
  '✓ Service Worker registered and activated',
  '✓ Web app manifest present',
  '✓ Manifest has name, short_name, icons',
  '✓ Icons include at least 192x192 and 512x512',
  '✓ HTTPS enabled (or localhost)',
  '✓ Display mode is standalone',
  '✓ Start URL defined',
  '✓ beforeinstallprompt event fires'
];
requirements.forEach(req => console.log('  ' + req));

console.log('\n💡 If nothing shows, try:');
console.log('   1. Hard refresh: Ctrl+Shift+R (or Cmd+Shift+R on Mac)');
console.log('   2. Clear site data: DevTools → Application → Clear site data');
console.log('   3. Close and reopen browser');
console.log('   4. Check manifest.json loads: Network tab → manifest.json');
console.log('   5. Check Service Worker: Application → Service Workers');
