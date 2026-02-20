document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('registerForm');
  if (!form) return;

  form.addEventListener('submit', (event) => {
    const password = form.querySelector('input[name="password"]');
    if (!password || password.value.length < 8) {
      event.preventDefault();
      alert('Password must be at least 8 characters.');
    }
  });
});
