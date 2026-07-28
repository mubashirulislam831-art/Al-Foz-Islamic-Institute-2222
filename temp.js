
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
  