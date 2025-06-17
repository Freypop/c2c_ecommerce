// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    const target = document.querySelector(this.getAttribute('href'));
    if (target) {
      e.preventDefault();
      target.scrollIntoView({ behavior: 'smooth' });
    }
  });
});

// Show toast notification when Add to Cart is clicked
document.querySelectorAll('form[action*="add_to_cart.php"]').forEach(form => {
  form.addEventListener('submit', function (e) {
       showToast('Added to cart!');
  });
});

function showToast(message) {
  let toast = document.createElement('div');
  toast.className = 'toast align-items-center text-bg-success border-0 position-fixed bottom-0 end-0 m-4 show';
  toast.role = 'alert';
  toast.innerHTML = `<div class="d-flex">
    <div class="toast-body">${message}</div>
    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
  </div>`;
  document.body.appendChild(toast);
  setTimeout(() => toast.remove(), 5000);
}

