/**
 * Al Foz Islamic Institute - Session Keep-Alive & Cookie Fallback for Iframe Environments
 * Automatically propagates the session token across link clicks and form submissions.
 */
(function() {
  // Parse query parameters
  const urlParams = new URLSearchParams(window.location.search);
  let token = urlParams.get('alfoz_session_token');
  
  if (token) {
    try {
      localStorage.setItem('alfoz_session_token', token);
    } catch(e) {
      console.error("Failed to write token to localStorage:", e);
    }
  } else {
    try {
      token = localStorage.getItem('alfoz_session_token');
    } catch(e) {
      console.error("Failed to read token from localStorage:", e);
    }
  }

  // Handle auto-login redirect on the login page
  const pathname = window.location.pathname;
  const isLoginPage = pathname.endsWith('login.php') || pathname === '/';
  
  if (isLoginPage) {
    const hasTokenInUrl = urlParams.has('alfoz_session_token');
    
    if (hasTokenInUrl) {
      // If we are on the login page, but we have a token in the URL,
      // it means the server verified it and found it was INVALID (otherwise the server would have redirected us).
      // So we should clear the invalid token.
      try {
        localStorage.removeItem('alfoz_session_token');
      } catch(e) {}
      token = null;
    } else if (token) {
      const hasError = urlParams.has('error') || urlParams.has('err');
      const hasLoggedOut = urlParams.has('msg') && urlParams.get('msg') === 'logged_out';
      
      // Auto redirect to login page WITH the token in the URL, so the server can verify it.
      // If the server finds the token is valid, it redirects to the correct dashboard.
      // If the server finds the token is invalid, it renders the login page normally, and we clear the token.
      if (!hasError && !hasLoggedOut) {
        window.location.href = '/login.php?alfoz_session_token=' + token;
        return;
      }
    }
  }

  if (!token) return;

  // Helper function to append token to a URL string
  function appendTokenToUrl(urlStr) {
    if (!urlStr) return urlStr;
    if (urlStr.startsWith('#') || urlStr.startsWith('javascript:') || urlStr.startsWith('mailto:') || urlStr.startsWith('tel:')) {
      return urlStr;
    }
    try {
      // Handle absolute URLs on the same origin or relative URLs
      const url = new URL(urlStr, window.location.href);
      if (url.origin === window.location.origin) {
        url.searchParams.set('alfoz_session_token', token);
        return url.pathname + url.search + url.hash;
      }
    } catch(e) {
      // Fallback simple query append for relative paths
      const hashParts = urlStr.split('#');
      let base = hashParts[0];
      const hash = hashParts[1] ? '#' + hashParts[1] : '';
      
      const queryParts = base.split('?');
      let path = queryParts[0];
      let query = queryParts[1] ? '?' + queryParts[1] : '';
      
      if (query) {
        if (!query.includes('alfoz_session_token=')) {
          query += '&alfoz_session_token=' + encodeURIComponent(token);
        }
      } else {
        query = '?alfoz_session_token=' + encodeURIComponent(token);
      }
      return path + query + hash;
    }
    return urlStr;
  }

  // Rewrite existing DOM links and form actions
  function updateDomElements() {
    // 1. Rewrite anchor tags
    document.querySelectorAll('a[href]').forEach(el => {
      const href = el.getAttribute('href');
      if (href && !href.includes('alfoz_session_token') && !href.startsWith('#') && !href.startsWith('javascript:')) {
        el.setAttribute('href', appendTokenToUrl(href));
      }
    });

    // 2. Rewrite form actions and insert hidden token inputs
    document.querySelectorAll('form').forEach(el => {
      const action = el.getAttribute('action') || '';
      if (action && !action.includes('alfoz_session_token') && !action.startsWith('#')) {
        el.setAttribute('action', appendTokenToUrl(action));
      }
      
      // Inject hidden field as backup for POST forms
      let hiddenInput = el.querySelector('input[name="alfoz_session_token"]');
      if (!hiddenInput) {
        hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'alfoz_session_token';
        hiddenInput.value = token;
        el.appendChild(hiddenInput);
      } else {
        hiddenInput.value = token;
      }
    });
  }

  // Intercept click events dynamically (handles dynamically added links)
  document.addEventListener('click', function(e) {
    const anchor = e.target.closest('a');
    if (anchor) {
      const href = anchor.getAttribute('href');
      if (href && !href.includes('alfoz_session_token') && !href.startsWith('#') && !href.startsWith('javascript:')) {
        anchor.setAttribute('href', appendTokenToUrl(href));
      }
    }
  }, true);

  // Intercept form submissions dynamically
  document.addEventListener('submit', function(e) {
    const form = e.target;
    const action = form.getAttribute('action') || '';
    if (action && !action.includes('alfoz_session_token') && !action.startsWith('#')) {
      form.setAttribute('action', appendTokenToUrl(action));
    }
    
    let hiddenInput = form.querySelector('input[name="alfoz_session_token"]');
    if (!hiddenInput) {
      hiddenInput = document.createElement('input');
      hiddenInput.type = 'hidden';
      hiddenInput.name = 'alfoz_session_token';
      hiddenInput.value = token;
      form.appendChild(hiddenInput);
    }
  }, true);

  // Run initial pass and then poll regularly for any dynamic UI updates/renders
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', updateDomElements);
  } else {
    updateDomElements();
  }
  
  // Repeatedly scan DOM to keep dynamic elements updated
  setInterval(updateDomElements, 1000);
})();
