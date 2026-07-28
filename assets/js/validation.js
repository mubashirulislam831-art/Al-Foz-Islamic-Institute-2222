/**
 * Al Foz Islamic Institute - Input Validation Scripts
 */

function validateRequired(value) {
  return value && value.trim().length > 0;
}

function validateEmail(email) {
  const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return re.test(String(email).toLowerCase());
}
