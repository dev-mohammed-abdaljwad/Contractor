/**
 * Advanced PWA Service Worker Debugger
 * 
 * Run this in browser console to identify SW registration issues
 * Copy and paste the entire code into DevTools Console (F12)
 */

console.log('🔍 Advanced Service Worker Debugger\n');
console.log('=' .repeat(60));

// Step 1: Test if /sw.js returns valid response
console.log('\n📋 Step 1: Testing /sw.js File');
console.log('-'.repeat(60));

fetch('/sw.js')
  .then(response => {
    console.log('Status:', response.status);
    console.log('Content-Type:', response.headers.get('Content-Type'));
    console.log('Service-Worker-Allowed:', response.headers.get('Service-Worker-Allowed'));
    
    if (response.status === 200) {
      console.log('✅ /sw.js file is accessible (200 OK)');
      return response.text();
    } else {
      console.log('❌ /sw.js returned status:', response.status);
      throw new Error('Invalid status ' + response.status);
    }
  })
  .then(text => {
    console.log(`✅ /sw.js content length: ${text.length} bytes`);
    
    // Check for common syntax errors
    if (text.includes('self.addEventListener("install"')) {
      console.log('✅ Install event handler found');
    }
    if (text.includes('self.addEventListener("activate"')) {
      console.log('✅ Activate event handler found');
    }
    if (text.includes('self.addEventListener("fetch"')) {
      console.log('✅ Fetch event handler found');
    }
  })
  .catch(error => {
    console.log('❌ Error fetching /sw.js:', error.message);
  });

// Step 2: Test Service Worker Registration
console.log('\n📋 Step 2: Testing Service Worker Registration');
console.log('-'.repeat(60));

if ('serviceWorker' in navigator) {
  console.log('✅ ServiceWorker API available');
  
  navigator.serviceWorker.register('/sw.js', { scope: '/' })
    .then(registration => {
      console.log('✅ Service Worker registered successfully!');
      console.log('   Scope:', registration.scope);
      console.log('   Active:', !!registration.active);
      console.log('   Installing:', !!registration.installing);
      console.log('   Waiting:', !!registration.waiting);
      
      // Watch for state changes
      if (registration.installing) {
        console.log('\n   🔄 Status: INSTALLING (waiting for completion...)');
        registration.installing.addEventListener('statechange', function(e) {
          console.log('   📍 State changed:', e.target.state);
        });
      } else if (registration.waiting) {
        console.log('\n   ⏳ Status: WAITING (skipWaiting needed?)');
      } else if (registration.active) {
        console.log('\n   ✅ Status: ACTIVE');
      }
    })
    .catch(error => {
      console.log('❌ Service Worker registration failed!');
      console.log('   Error:', error.message);
      console.log('   Stack:', error.stack);
    });
} else {
  console.log('❌ ServiceWorker API not available');
}

// Step 3: Check current registrations
console.log('\n📋 Step 3: Checking Current Registrations');
console.log('-'.repeat(60));

navigator.serviceWorker.getRegistrations()
  .then(registrations => {
    console.log(`Found ${registrations.length} registration(s)`);
    registrations.forEach((reg, i) => {
      console.log(`\n  Registration ${i + 1}:`);
      console.log(`    Scope: ${reg.scope}`);
      console.log(`    Active: ${!!reg.active}`);
      console.log(`    Installing: ${!!reg.installing}`);
      console.log(`    Waiting: ${!!reg.waiting}`);
      
      if (reg.active) {
        console.log(`    ✅ Service Worker is ACTIVE`);
      }
    });
  })
  .catch(error => {
    console.log('❌ Error getting registrations:', error.message);
  });

// Step 4: Monitor beforeinstallprompt
console.log('\n📋 Step 4: Monitoring beforeinstallprompt Event');
console.log('-'.repeat(60));

let promptFired = false;
let deferredPrompt = null;

window.addEventListener('beforeinstallprompt', (e) => {
  promptFired = true;
  deferredPrompt = e;
  console.log('✅ beforeinstallprompt event FIRED!');
  console.log('   Event:', e);
  console.log('   You can now show install button');
});

window.addEventListener('appinstalled', () => {
  console.log('✅ appinstalled event FIRED!');
  console.log('   App installed successfully!');
});

// Check after 5 seconds
setTimeout(() => {
  if (!promptFired) {
    console.log('\n⏱️ After 5 seconds: beforeinstallprompt did NOT fire');
    console.log('   This means:');
    console.log('   - Service Worker not active, OR');
    console.log('   - Manifest incomplete, OR');
    console.log('   - Already installed, OR');
    console.log('   - Other PWA requirements not met');
  }
}, 5000);

// Step 5: Manifest Check
console.log('\n📋 Step 5: Verifying Manifest');
console.log('-'.repeat(60));

fetch('/manifest.json')
  .then(r => r.json())
  .then(manifest => {
    console.log('✅ Manifest loaded');
    console.log(`   Name: ${manifest.name}`);
    console.log(`   Display: ${manifest.display}`);
    console.log(`   Icons: ${manifest.icons?.length || 0}`);
    console.log(`   Start URL: ${manifest.start_url}`);
  })
  .catch(e => console.log('❌ Manifest error:', e.message));

// Step 6: Console Output Analysis
console.log('\n📋 Step 6: What to Look For');
console.log('-'.repeat(60));
console.log(`
✅ GOOD SIGNS:
  - "Service Worker registered successfully!"
  - "Service Worker is ACTIVE"
  - "beforeinstallprompt event FIRED!"
  
❌ BAD SIGNS:
  - "/sw.js returned status: 404"
  - "Service Worker registration failed"
  - "Error fetching /sw.js"
  - "beforeinstallprompt did NOT fire" (after 5 seconds)

🔧 NEXT STEPS IF FAILED:
  1. Hard refresh: Ctrl+Shift+R
  2. Clear site data in DevTools
  3. Check Network tab for /sw.js response
  4. Look for errors in DevTools Console
  5. Check if Laravel server is running
`);

console.log('=' .repeat(60));
console.log('✨ Debugger Complete - Check console output above ✨\n');
