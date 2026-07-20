/**
 * =========================================================
 * main.js
 * Script umum: navigasi mobile (hamburger) & hero slider
 * BKAD - PT Mitracom Solusi Teknologi
 * =========================================================
 */

document.addEventListener('DOMContentLoaded', function () {

  /* ---------------------------------------------------
     1. TOGGLE MENU MOBILE (HAMBURGER)
     --------------------------------------------------- */
  const hamburgerBtn = document.getElementById('hamburgerBtn');
  const navMenu = document.getElementById('navMenu');

  if (hamburgerBtn && navMenu) {
    hamburgerBtn.addEventListener('click', function () {
      navMenu.classList.toggle('open');
    });

    // Tutup menu saat salah satu link diklik (khusus mobile)
    navMenu.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        navMenu.classList.remove('open');
      });
    });
  }

  /* ---------------------------------------------------
     2. HERO SLIDER (Banner Ringkasan Layanan)
     --------------------------------------------------- */
  const slides = document.querySelectorAll('.hero-slide');
  const dots = document.querySelectorAll('#slideDots button');
  let currentSlide = 0;
  let sliderInterval;

  function showSlide(index) {
    if (!slides.length) return;
    slides.forEach(function (slide) { slide.classList.remove('active'); });
    dots.forEach(function (dot) { dot.classList.remove('active'); });
    slides[index].classList.add('active');
    if (dots[index]) dots[index].classList.add('active');
    currentSlide = index;
  }

  function nextSlide() {
    let next = (currentSlide + 1) % slides.length;
    showSlide(next);
  }

  function startAutoSlide() {
    sliderInterval = setInterval(nextSlide, 5000);
  }

  function stopAutoSlide() {
    clearInterval(sliderInterval);
  }

  if (slides.length) {
    dots.forEach(function (dot) {
      dot.addEventListener('click', function () {
        const idx = parseInt(dot.getAttribute('data-slide'), 10);
        showSlide(idx);
        stopAutoSlide();
        startAutoSlide();
      });
    });
    startAutoSlide();
  }

  /* ---------------------------------------------------
     3. HEADER SHADOW ON SCROLL (opsional, efek ringan)
     --------------------------------------------------- */
  const header = document.querySelector('.main-header');
  if (header) {
    window.addEventListener('scroll', function () {
      if (window.scrollY > 10) {
        header.style.boxShadow = '0 4px 14px rgba(10,61,120,0.15)';
      } else {
        header.style.boxShadow = '';
      }
    });
  }

});
