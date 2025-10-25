// script.js — Client-side validation for registration form

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('regForm');
  const errorsDiv = document.getElementById('errors');

  function validatePhone(phone) {
    // Kenyan mobile example: starts with 07, +2547, or 7 — adjust if needed
    return /^(?:\+254|0)?7\d{8}$/.test(phone);
  }

  function validatePassword(pw) {
    // At least 8 chars, must include letters and numbers
    return /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/.test(pw);
  }

  function validateEmail(email) {
    // Standard and flexible email regex
    return /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/.test(email);
  }

  form.addEventListener('submit', (e) => {
    errorsDiv.textContent = '';
    const first = form.first_name.value.trim();
    const last = form.last_name.value.trim();
    const phone = form.phone_number.value.trim();
    const email = form.email.value.trim();
    const pw = form.password.value;
    const pwc = form.password_confirm.value;

    const errors = [];

    if (!first) errors.push('First name is required.');
    if (!last) errors.push('Last name is required.');
    if (!validatePhone(phone)) errors.push('Phone number looks invalid.');
    if (!email || !validateEmail(email)) errors.push('Valid email is required.');
    if (!validatePassword(pw)) errors.push('Password must be at least 8 characters long and include both letters and numbers.');
    if (pw !== pwc) errors.push('Passwords do not match.');

    if (errors.length) {
      e.preventDefault();
      errorsDiv.innerHTML = errors.join('<br>');
    }
  });
});
