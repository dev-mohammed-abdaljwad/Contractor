<?php

// Test script to verify login functionality
echo "=== LOGIN SYSTEM TEST ===\n\n";

// 1. Check database connection
echo "1. Testing database...\n";
try {
    \Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "   ✓ Database connected\n\n";
} catch (\Exception $e) {
    echo "   ✗ Database error: " . $e->getMessage() . "\n\n";
    exit(1);
}

// 2. Check users exist
echo "2. Checking test users...\n";
$users = \App\Models\User::all();
foreach ($users as $user) {
    echo "   ✓ {$user->name} ({$user->phone}) - Role: {$user->role}\n";
}
echo "\n";

// 3. Test password hash
echo "3. Testing password verification...\n";
$adminUser = \App\Models\User::where('phone', '01000000000')->first();
if ($adminUser) {
    $passwordMatch = \Illuminate\Support\Facades\Hash::check('password', $adminUser->password);
    echo "   ✓ Password match: " . ($passwordMatch ? 'YES' : 'NO') . "\n";
} else {
    echo "   ✗ Admin user not found\n";
}
echo "\n";

// 4. Test route exists
echo "4. Checking routes...\n";
$routes = \Illuminate\Support\Facades\Route::getRoutes();
$loginRoutes = [];
foreach ($routes as $route) {
    if (in_array('login', $route->getName() ? [$route->getName()] : [])) {
        $loginRoutes[] = $route->getMethod() . ' ' . $route->getPath();
    }
}
if (count($loginRoutes) > 0) {
    foreach ($loginRoutes as $route) {
        echo "   ✓ Route: $route\n";
    }
} else {
    echo "   ✗ No login routes found\n";
}
echo "\n";

echo "=== TEST COMPLETE ===\n";
echo "\nTest Credentials:\n";
echo "  Phone: 01000000000\n";
echo "  Password: password\n";
echo "\nURL: http://localhost:8000/login\n";
