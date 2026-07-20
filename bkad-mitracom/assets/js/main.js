/**
 * assets/js/main.js
 * Perilaku umum untuk halaman publik: toggle menu mobile & hero slider.
 */
document.addEventListener('DOMContentLoaded', function () {

  /* ---------- Toggle menu navigasi (mobile) ---------- */
  var navToggle = document.getElementById('navToggle');
  var mainNav = document.getElementById('mainNav');

  if (navToggle && mainNav) {
    navToggle.addEventListener('click', function () {
      var isOpen = mainNav.classList.toggle('open');
      navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    });
  }

  /* ---------- Hero slider sederhana ---------- */
  var slides = document.querySelectorAll('.hero-slide-bg');
  var dots = document.querySelectorAll('#heroDots button');
  var currentSlide = 0;
  var slideInterval;

  function showSlide(index) {
    if (!slides.length) return;
    slides.forEach(function (s) { s.classList.remove('active'); });
    dots.forEach(function (d) { d.classList.remove('active'); });
    slides[index].classList.add('active');
    if (dots[index]) dots[index].classList.add('active');
    currentSlide = index;
  }

  function nextSlide() {
    var next = (currentSlide + 1) % slides.length;
    showSlide(next);
  }

  if (slides.length > 1) {
    slideInterval = setInterval(nextSlide, 5000);

    dots.forEach(function (dot) {
      dot.addEventListener('click', function () {
        clearInterval(slideInterval);
        showSlide(parseInt(dot.getAttribute('data-slide'), 10));
        slideInterval = setInterval(nextSlide, 5000);
      });
    });
  }

});
