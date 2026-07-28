/**
 * Al Foz Islamic Institute - Dedicated Login Portal Scripts
 * Handles interactive client-side behaviors, validation, and layout responsiveness.
 */

document.addEventListener("DOMContentLoaded", () => {
  const loginForm = document.querySelector("form");
  const emailInput = document.getElementById("email");
  const passwordInput = document.getElementById("password");

  if (loginForm) {
    loginForm.addEventListener("submit", (e) => {
      let valid = true;

      if (emailInput && !emailInput.value.trim()) {
        valid = false;
        highlightField(emailInput);
      } else {
        clearHighlight(emailInput);
      }

      if (passwordInput && !passwordInput.value) {
        valid = false;
        highlightField(passwordInput);
      } else {
        clearHighlight(passwordInput);
      }

      if (!valid) {
        e.preventDefault();
        showNotification("Please fill in all required fields.", "error");
      }
    });
  }

  function highlightField(input) {
    input.classList.add("border-red-500", "focus:ring-red-200");
    input.classList.remove("border-primary/20", "focus:ring-primary/20");
  }

  function clearHighlight(input) {
    input.classList.remove("border-red-500", "focus:ring-red-200");
    input.classList.add("border-primary/20", "focus:ring-primary/20");
  }

  function showNotification(message, type) {
    const container = document.querySelector(".max-w-md");
    if (!container) return;

    // Check if there is an existing validation banner
    let banner = document.getElementById("client-validation-banner");
    if (banner) banner.remove();

    banner = document.createElement("div");
    banner.id = "client-validation-banner";
    banner.className = `alert-fade-in p-4 mb-4 rounded-xl border-l-4 ${
      type === "error"
        ? "bg-red-50 border-red-500 text-red-700"
        : "bg-green-50 border-green-500 text-green-700"
    } flex items-center gap-3 text-xs font-medium`;

    banner.innerHTML = `
      <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
      </svg>
      <span>${message}</span>
    `;

    // Insert alert at the top of the form or card
    const card = document.querySelector(".bg-white\\/80");
    if (card) {
      card.insertBefore(banner, card.firstChild);
    }
  }
});
