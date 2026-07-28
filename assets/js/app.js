/**
 * Al Foz Islamic Institute - Core App Scripts & Responsive Helper Engines
 */

document.addEventListener("DOMContentLoaded", () => {
  console.log("Al Foz Islamic Institute online. All systems synchronized.");

  // --- Dynamic Table Responsiveness Wrapper ---
  // Safely scans the DOM and wraps any orphaned table element in a responsive overflow scroll container
  const wrapOrphanTables = () => {
    const tables = document.querySelectorAll("table");
    tables.forEach(table => {
      // Avoid wrapping printing sheets or tables that are already in responsive containers
      const parent = table.parentElement;
      const isWrapped = parent.classList.contains("overflow-x-auto") || 
                        parent.classList.contains("table-responsive") ||
                        parent.tagName.toLowerCase() === "thead" || 
                        parent.tagName.toLowerCase() === "tbody" ||
                        table.closest(".print-sheet");

      if (!isWrapped) {
        const wrapper = document.createElement("div");
        wrapper.className = "overflow-x-auto w-full custom-horizontal-scrollbar mb-4";
        parent.insertBefore(wrapper, table);
        wrapper.appendChild(table);
      } else {
        // Ensure parent has the scrollbar helper
        parent.classList.add("custom-horizontal-scrollbar");
      }
    });
  };

  // Run wrapper on load
  wrapOrphanTables();

  // --- Mobile Touch Optimization Helpers ---
  // Prevent double-tap zooming on iOS devices for quick buttons
  const buttons = document.querySelectorAll("button, .btn, a[class*='bg-primary']");
  buttons.forEach(btn => {
    btn.addEventListener("touchstart", function() {
      // Direct fast touch feedback hook if needed
    }, { passive: true });
  });
});

