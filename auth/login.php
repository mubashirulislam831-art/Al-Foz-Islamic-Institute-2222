<?php
/**
 * Al Foz Islamic Institute - Login
 * Displays a premium login screen and processes authentication.
 */

require_once __DIR__ . '/session.php';
require_once __DIR__ . '/auth.php';

// Redirect user if they are already logged in
redirect_if_logged_in();

$error = '';
$email_input = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_input = $_POST['email'] ?? '';
    $password_input = $_POST['password'] ?? '';
    $remember_me = isset($_POST['remember']);
    
    if (empty($email_input) || empty($password_input)) {
        $error = 'Please fill out both email and password fields.';
    } else {
        $redirect_path = authenticate_user($email_input, $password_input, $remember_me);
        if ($redirect_path) {
            $token = session_id();
            $separator = (strpos($redirect_path, '?') === false) ? '?' : '&';
            header("Location: " . $redirect_path . $separator . "alfoz_session_token=" . urlencode($token));
            exit();
        } else {
            $error = 'Invalid email or password. Please verify your credentials and try again.';
        }
    }
}

$msg = $_GET['msg'] ?? '';
$err_type = $_GET['error'] ?? '';

if ($err_type === 'unauthorized') {
    $error = 'Access denied. Please login with a qualified account to view that portal.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <title>Login - Al Foz Islamic Institute</title>
  <script src="/assets/js/session-keepalive.js"></script>
  <meta name="description" content="Access your Al Foz Islamic Institute ERP Account. Dedicated login portals for Super Admins, Admins, Teachers, Students, and Parents.">
  
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="/assets/logo.png">
  
  <!-- Google Fonts - Poppins -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
  
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#184D55',
            secondary: '#F7FAFF',
          },
          fontFamily: {
            sans: ['Poppins', 'sans-serif'],
            mono: ['JetBrains Mono', 'monospace'],
          }
        }
      }
    }
  </script>
  
  <!-- Custom CSS Styles -->
  <link rel="stylesheet" href="/style.css">
  <link rel="stylesheet" href="/css/login.css">
</head>
<body class="bg-secondary text-primary font-sans antialiased min-h-screen flex flex-col justify-between selection:bg-primary selection:text-secondary">

  <!-- HEADER -->
  <header class="bg-secondary/95 backdrop-blur-md border-b border-primary/10 sticky top-0 z-40 transition-all duration-300 shadow-sm" id="main-header">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-center items-center h-16 md:h-20 lg:h-24">
        <div class="flex items-center gap-2 lg:gap-3 group shrink-0">
          <div class="w-8 h-8 md:w-10 md:h-10 lg:w-12 lg:h-12 rounded-lg overflow-hidden shadow-sm border border-primary/20 bg-secondary transition-transform group-hover:scale-105">
            <img id="header-logo" src="/assets/logo.png" alt="Al Foz Logo" class="w-full h-full object-cover" referrerPolicy="no-referrer">
          </div>
          <div class="flex flex-col">
            <h1 class="font-bold text-xs md:text-sm lg:text-base leading-tight uppercase tracking-widest text-primary">Al Foz</h1>
            <span class="text-[7px] md:text-[8px] uppercase tracking-widest text-primary/70 font-semibold">Institute Portal</span>
          </div>
        </div>
      </div>
    </div>
  </header>

  <!-- Mobile Drawer Subview Navigation -->
  <div id="mobile-menu" class="hidden md:hidden fixed inset-0 z-50 bg-secondary flex flex-col justify-between border-t border-primary/10">
    <div class="px-6 py-5 flex items-center justify-between border-b border-primary/5">
      <div class="flex items-center gap-3">
        <div class="w-14 h-14 rounded-2xl overflow-hidden border border-primary/20 flex items-center justify-center bg-secondary">
          <img src="/assets/logo.png" alt="Al Foz Logo" class="w-full h-full object-cover" referrerPolicy="no-referrer">
        </div>
        <span class="text-xl font-bold text-primary">Al Foz Institute</span>
      </div>
      <button id="close-menu" class="p-2 border border-primary/20 rounded-xl" aria-label="Close Navigation">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>

    

    <div class="p-8 border-t border-primary/10 bg-primary/5 flex flex-col gap-4">
      <div class="flex justify-between items-center text-xs font-semibold">
        <span>CALL US ANYTIME</span>
        <a href="tel:+923185027846" class="text-sm font-bold">+92 318 5027846</a>
      </div>
      <div class="flex gap-3">
        <a href="/auth/login.php" class="flex-1 text-center border border-primary text-primary py-4 rounded-xl font-bold uppercase text-xs tracking-wider shadow-sm bg-secondary hover:bg-primary hover:text-secondary transition-all">
          Login
        </a>
        <a href="/admission" class="flex-1 text-center bg-primary text-secondary py-4 rounded-xl font-bold uppercase text-xs tracking-wider shadow-md hover:bg-opacity-90 transition-all">
          Enroll Now
        </a>
      </div>
    </div>
  </div>

  <!-- MAIN LOGIN FORM CONTAINER -->
  <main class="flex-1 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden bg-secondary islamic-texture">
    
    <!-- Background Ornaments (Matches standard theme) -->
    <div class="absolute inset-0 islamic-grid-bg opacity-30 pointer-events-none"></div>
    <div class="absolute -right-32 -top-32 w-96 h-96 rounded-full bg-primary/5 blur-3xl"></div>
    <div class="absolute -left-32 -bottom-32 w-96 h-96 rounded-full bg-primary/5 blur-3xl"></div>

    <div class="max-w-md w-full space-y-8 relative z-10">
      
      <!-- Logo, Academy Name and Welcome -->
      <div class="text-center">
        <div class="w-24 h-24 rounded-3xl overflow-hidden border-2 border-primary/20 flex items-center justify-center bg-white mx-auto shadow-md transform hover:scale-105 transition-transform duration-300">
          <img src="/assets/logo.png" alt="Al Foz Islamic Institute Logo" class="w-full h-full object-cover" referrerPolicy="no-referrer">
        </div>
        <h2 class="mt-6 text-3xl font-extrabold tracking-tight text-primary leading-tight">
          Al Foz Islamic Institute
        </h2>
        <p class="mt-2 text-xs font-semibold tracking-wider text-primary/70 uppercase">
          ERP MANAGEMENT SYSTEM LOGIN
        </p>
      </div>

      <!-- Login Card -->
      <div class="bg-white/80 backdrop-blur-md rounded-2xl p-8 border border-primary/10 shadow-xl space-y-6 relative">
        
        <!-- Messages & Errors Alerts (Shown only if unlocked or general errors) -->
        <?php if (!empty($error)): ?>
          <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg flex items-start gap-3">
            <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <p class="text-xs font-medium text-red-700 leading-relaxed"><?php echo htmlspecialchars($error); ?></p>
          </div>
        <?php elseif ($msg === 'logged_out'): ?>
          <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg flex items-start gap-3 animate-pulse">
            <svg class="w-5 h-5 text-green-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-xs font-medium text-green-700 leading-relaxed">You have been logged out successfully. Have a blessed day!</p>
          </div>
        <?php endif; ?>

        <!-- Passcode Guard Screen (Locks down the entire form by default) -->
        <div id="passcode-guard-screen" class="space-y-6">
          <div class="text-center space-y-2">
            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center mx-auto text-primary">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
              </svg>
            </div>
            <h3 class="text-sm font-bold text-primary uppercase tracking-wider">Security Access Required</h3>
            <p class="text-xs text-primary/60">Enter the administrator secret code to access this login portal.</p>
          </div>

          <div class="space-y-3">
            <input type="password" id="portal-passcode-input" class="appearance-none block w-full px-3 py-3 border border-primary/20 rounded-xl bg-secondary/35 text-center font-bold tracking-widest text-base text-primary placeholder-primary/30 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all" placeholder="••••">
            <div id="portal-passcode-error" class="hidden text-xs font-semibold text-red-600 text-center">Invalid portal passcode. Access Denied.</div>
          </div>

          <button type="button" id="verify-portal-passcode" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl text-xs font-bold uppercase tracking-wider text-secondary bg-primary hover:bg-opacity-95 active:scale-95 transition-all shadow-md">
            Unlock Portal
          </button>
        </div>

        <!-- Form Form (Hidden until unlocked) -->
        <form id="login-fields-form" class="hidden space-y-5" action="" method="POST">
          
          <!-- Email Input -->
          <div>
            <label for="email" class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Email Address</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-primary/55">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                </svg>
              </div>
              <input id="email" name="email" type="email" required value="<?php echo htmlspecialchars($email_input); ?>" class="appearance-none block w-full pl-10 pr-3 py-3 border border-primary/20 rounded-xl bg-secondary/35 text-xs sm:text-sm text-primary placeholder-primary/40 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all" placeholder="email@example.com">
            </div>
          </div>

          <!-- Password Input -->
          <div>
            <label for="password" class="block text-xs font-bold text-primary uppercase tracking-wider mb-2">Password</label>
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-primary/55">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
              </div>
              <input id="password" name="password" type="password" required class="appearance-none block w-full pl-10 pr-10 py-3 border border-primary/20 rounded-xl bg-secondary/35 text-xs sm:text-sm text-primary placeholder-primary/40 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/20 transition-all" placeholder="••••••••">
              <button type="button" id="toggle-password-visibility" class="absolute inset-y-0 right-0 pr-3 flex items-center text-primary/55 hover:text-primary transition-colors focus:outline-none" title="Toggle Password Visibility">
                <!-- Eye Open (Show Password) -->
                <svg id="eye-icon-show" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                  <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z"></path>
                  <circle cx="12" cy="12" r="3"></circle>
                </svg>
                <!-- Eye Closed (Hide Password) -->
                <svg id="eye-icon-hide" class="h-4 w-4 hidden" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                  <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"></path>
                  <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"></path>
                  <path d="M6.61 6.61A13.52 13.52 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61"></path>
                  <line x1="2" y1="2" x2="22" y2="22"></line>
                </svg>
              </button>
            </div>
          </div>

          <!-- Remember Me & Forgot Password -->
          <div class="flex items-center justify-between text-xs">
            <label class="flex items-center gap-2 cursor-pointer select-none">
              <input type="checkbox" name="remember" class="w-4 h-4 rounded border-primary/20 text-primary focus:ring-primary/25">
              <span class="font-medium text-primary/80">Remember Me</span>
            </label>
            
            <a href="#" onclick="alert('Password reset coordinates: Please contact the Operations Supervisor (Ihtisham Awan) at support@alfoz.com or call +92 318 5027846.'); return false;" class="font-semibold text-primary hover:underline">Forgot Password?</a>
          </div>

          <!-- Login Submit Button -->
          <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl text-xs sm:text-sm font-bold uppercase tracking-wider text-secondary bg-primary hover:bg-opacity-95 active:scale-95 transition-all shadow-md mt-6">
            Log In securely
          </button>

        </form>

      </div>

    </div>
  </main>

  <!-- FOOTER -->
  <footer class="bg-primary text-secondary border-t border-secondary/15 relative overflow-hidden py-12">
    <div class="absolute inset-0 islamic-pattern mix-blend-overlay"></div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 flex flex-col md:flex-row items-center justify-center gap-6">
      <div class="flex items-center gap-3 group">
        <div class="w-12 h-12 border border-secondary/20 rounded-xl overflow-hidden flex items-center justify-center bg-secondary shadow-md shrink-0 transition-transform group-hover:scale-105">
          <img src="/assets/logo.png" alt="Al Foz Logo" class="w-full h-full object-cover" referrerPolicy="no-referrer">
        </div>
        <div>
          <h3 class="font-bold text-sm leading-tight uppercase tracking-wider text-secondary">Al Foz Islamic Institute</h3>
          <p class="text-[9px] uppercase tracking-widest text-secondary/70">Verified Online Academy</p>
        </div>
      </div>
    </div>
  </footer>

  <script>
    // Mobile view hamburger controller for login screen
    const menuBtn = document.getElementById('menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const closeBtn = document.getElementById('close-menu');

    if (menuBtn && mobileMenu) {
      menuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
      });
    }

    if (closeBtn && mobileMenu) {
      closeBtn.addEventListener('click', () => {
        mobileMenu.classList.add('hidden');
      });
    }

    // Passcode Guard System
    const passcodeGuardScreen = document.getElementById('passcode-guard-screen');
    const loginFieldsForm = document.getElementById('login-fields-form');
    const passcodeValInput = document.getElementById('portal-passcode-input');
    const passcodeErrorDiv = document.getElementById('portal-passcode-error');
    const verifyPasscodeBtn = document.getElementById('verify-portal-passcode');

    function checkPortalUnlockState() {
      if (sessionStorage.getItem('alfoz_portal_unlocked') === 'true') {
        if (passcodeGuardScreen) passcodeGuardScreen.classList.add('hidden');
        if (loginFieldsForm) loginFieldsForm.classList.remove('hidden');
      }
    }

    // Call immediately on load
    checkPortalUnlockState();

    function performPortalUnlock() {
      const enteredCode = passcodeValInput ? passcodeValInput.value.trim() : '';
      if (enteredCode === '0088' || enteredCode === '786' || enteredCode.toLowerCase() === 'alfoz') {
        sessionStorage.setItem('alfoz_portal_unlocked', 'true');
        if (passcodeGuardScreen) passcodeGuardScreen.classList.add('hidden');
        if (loginFieldsForm) {
          loginFieldsForm.classList.remove('hidden');
          // Automatically focus the first input inside the form
          const emailInput = document.getElementById('email');
          if (emailInput) emailInput.focus();
        }
      } else {
        if (passcodeErrorDiv) passcodeErrorDiv.classList.remove('hidden');
      }
    }

    if (verifyPasscodeBtn) {
      verifyPasscodeBtn.addEventListener('click', performPortalUnlock);
    }

    if (passcodeValInput) {
      passcodeValInput.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
          performPortalUnlock();
        }
      });
      passcodeValInput.addEventListener('input', () => {
        if (passcodeErrorDiv) passcodeErrorDiv.classList.add('hidden');
      });
    }

    // Password visibility toggle
    const togglePasswordBtn = document.getElementById('toggle-password-visibility');
    const passwordInput = document.getElementById('password');
    const eyeIconShow = document.getElementById('eye-icon-show');
    const eyeIconHide = document.getElementById('eye-icon-hide');

    if (togglePasswordBtn && passwordInput && eyeIconShow && eyeIconHide) {
      togglePasswordBtn.addEventListener('click', () => {
        if (passwordInput.type === 'password') {
          passwordInput.type = 'text';
          eyeIconShow.classList.add('hidden');
          eyeIconHide.classList.remove('hidden');
        } else {
          passwordInput.type = 'password';
          eyeIconShow.classList.remove('hidden');
          eyeIconHide.classList.add('hidden');
        }
      });
    }
  </script>
  <script src="/assets/js/login.js"></script>
</body>
</html>
