/**
 * Al Foz Islamic Institute - Sidebar Responsive Toggler
 */
document.addEventListener("DOMContentLoaded", () => {
  const sidebar = document.querySelector(".erp-sidebar");
  const toggleBtn = document.getElementById("sidebar-toggle");

  if (!sidebar || !toggleBtn) return;

  // Convert 'title' attributes to 'data-tooltip' to prevent native browser tooltips and use custom styling
  const tooltipElements = sidebar.querySelectorAll("nav a, nav button, .sidebar-dropdown > button");
  tooltipElements.forEach(el => {
    const titleVal = el.getAttribute("title");
    if (titleVal && !el.getAttribute("data-tooltip")) {
      el.setAttribute("data-tooltip", titleVal);
      el.removeAttribute("title"); // Prevents native browser tooltips from overlapping
    }
  });

  // Ensure sidebar starts without mobile-open class on load
  sidebar.classList.remove("mobile-open");

  // Helper to manage backdrop overlay
  let overlay = document.querySelector(".sidebar-overlay");
  
  function createOverlay() {
    if (overlay) return;
    overlay = document.createElement("div");
    overlay.className = "sidebar-overlay";
    document.body.appendChild(overlay);
    
    overlay.addEventListener("click", () => {
      sidebar.classList.remove("mobile-open");
      overlay.classList.remove("visible");
    });
  }

  // Handle toggle button clicks
  toggleBtn.addEventListener("click", (e) => {
    e.stopPropagation();
    if (window.innerWidth <= 1024) {
      // Mobile drawer toggle
      createOverlay();
      sidebar.classList.toggle("mobile-open");
      if (sidebar.classList.contains("mobile-open")) {
        overlay.classList.add("visible");
      } else {
        overlay.classList.remove("visible");
      }
    } else {
      // Desktop collapse toggle
      sidebar.classList.toggle("collapsed");
      
      // Close any open floating panels when toggled
      const floatingFlyout = document.getElementById("erp-floating-flyout");
      const floatingTooltip = document.getElementById("erp-floating-tooltip");
      if (floatingFlyout) floatingFlyout.classList.remove("visible");
      if (floatingTooltip) floatingTooltip.classList.remove("visible");
    }
  });

  // Handle window resizing to keep states clean
  window.addEventListener("resize", () => {
    if (window.innerWidth <= 1024) {
      createOverlay();
    } else {
      sidebar.classList.remove("mobile-open");
      if (overlay) {
        overlay.classList.remove("visible");
      }
    }
  });

  // Initialize overlay check on load for mobile
  if (window.innerWidth <= 1024) {
    createOverlay();
  }

  // --- Collapsed Sidebar Flyout & Tooltip Logic ---
  
  // Create floating elements in body if not already present
  let floatingFlyout = document.getElementById("erp-floating-flyout");
  if (!floatingFlyout) {
    floatingFlyout = document.createElement("div");
    floatingFlyout.id = "erp-floating-flyout";
    floatingFlyout.className = "erp-floating-flyout";
    document.body.appendChild(floatingFlyout);
  }

  let floatingTooltip = document.getElementById("erp-floating-tooltip");
  if (!floatingTooltip) {
    floatingTooltip = document.createElement("div");
    floatingTooltip.id = "erp-floating-tooltip";
    floatingTooltip.className = "erp-floating-tooltip";
    document.body.appendChild(floatingTooltip);
  }

  let flyoutTimeout = null;
  let tooltipTimeout = null;

  // Helper to check if sidebar is collapsed
  function isSidebarCollapsed() {
    return sidebar.classList.contains("collapsed") && window.innerWidth > 1024;
  }

  // Hide flyout with delay to allow transition from button to flyout
  function hideFlyout() {
    clearTimeout(flyoutTimeout);
    flyoutTimeout = setTimeout(() => {
      floatingFlyout.classList.remove("visible");
      setTimeout(() => {
        if (!floatingFlyout.classList.contains("visible")) {
          floatingFlyout.style.display = "none";
        }
      }, 150);
    }, 150);
  }

  // Cancel flyout hide
  function cancelHideFlyout() {
    clearTimeout(flyoutTimeout);
  }

  // Show flyout
  function showFlyout(buttonEl, dropdownContentEl, title) {
    cancelHideFlyout();
    
    // Clear previous classes/styles
    floatingFlyout.style.display = "flex";
    
    // Set content
    const cleanContent = dropdownContentEl.innerHTML;
    floatingFlyout.innerHTML = `<span class="erp-floating-flyout-title">${title}</span>` + cleanContent;
    
    // Set max height and scrolling before measuring
    floatingFlyout.style.maxHeight = "calc(100vh - 40px)";
    floatingFlyout.style.overflowY = "auto";
    floatingFlyout.style.overflowX = "hidden";
    
    // Trigger layout/reflow for transitions and accurate measurements
    floatingFlyout.offsetHeight;
    
    // Position
    const btnRect = buttonEl.getBoundingClientRect();
    const sidebarRect = sidebar.getBoundingClientRect();
    const viewportHeight = window.innerHeight;
    
    let topPos = btnRect.top;
    const flyoutHeight = floatingFlyout.offsetHeight;
    
    // If the flyout goes off the bottom of the viewport, adjust it upwards
    if (topPos + flyoutHeight > viewportHeight - 20) {
      topPos = Math.max(20, viewportHeight - flyoutHeight - 20);
    }
    
    floatingFlyout.style.top = `${topPos}px`;
    floatingFlyout.style.left = `${sidebarRect.right + 12}px`;
    
    floatingFlyout.classList.add("visible");
  }

  // Manage Flyout hovering
  floatingFlyout.addEventListener("mouseenter", cancelHideFlyout);
  floatingFlyout.addEventListener("mouseleave", hideFlyout);

  // Selector for hover dropdowns and tooltips (works in both collapsed and expanded states)
  document.addEventListener("mouseover", (e) => {
    // Check if we are hovering over a dropdown button
    const dropdownButton = e.target.closest(".sidebar-dropdown > button");
    if (dropdownButton) {
      const dropdownParent = dropdownButton.closest(".sidebar-dropdown");
      const dropdownContent = dropdownParent ? dropdownParent.querySelector(".sidebar-dropdown-content") : null;
      if (dropdownContent) {
        const title = dropdownButton.getAttribute("data-tooltip") || dropdownButton.getAttribute("title") || "Menu";
        showFlyout(dropdownButton, dropdownContent, title);
        
        // Hide tooltip if any is open
        floatingTooltip.classList.remove("visible");
        floatingTooltip.style.display = "none";
        return;
      }
    }

    // Check if we are hovering over standard tooltip elements (excluding links inside the flyout)
    const tooltipEl = e.target.closest("[data-tooltip]");
    if (tooltipEl && !tooltipEl.closest(".erp-floating-flyout") && !tooltipEl.closest(".sidebar-dropdown-content")) {
      // If it's a dropdown button, we show flyout instead
      if (tooltipEl.matches(".sidebar-dropdown > button")) return;

      const tooltipText = tooltipEl.getAttribute("data-tooltip");
      if (tooltipText) {
        clearTimeout(tooltipTimeout);
        floatingTooltip.innerHTML = tooltipText;
        floatingTooltip.style.display = "block";
        floatingTooltip.offsetHeight; // reflow
        
        const elRect = tooltipEl.getBoundingClientRect();
        const tooltipRect = floatingTooltip.getBoundingClientRect();
        const sidebarRect = sidebar.getBoundingClientRect();
        
        floatingTooltip.style.top = `${elRect.top + (elRect.height / 2) - (tooltipRect.height / 2)}px`;
        floatingTooltip.style.left = `${sidebarRect.right + 12}px`;
        floatingTooltip.classList.add("visible");
      }
    }
  });

  document.addEventListener("mouseout", (e) => {
    const dropdownButton = e.target.closest(".sidebar-dropdown > button");
    if (dropdownButton) {
      hideFlyout();
    }

    const tooltipEl = e.target.closest("[data-tooltip]");
    if (tooltipEl) {
      clearTimeout(tooltipTimeout);
      floatingTooltip.classList.remove("visible");
      setTimeout(() => {
        if (!floatingTooltip.classList.contains("visible")) {
          floatingTooltip.style.display = "none";
        }
      }, 150);
    }
  });
});
