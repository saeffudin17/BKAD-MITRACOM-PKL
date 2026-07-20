/**
 * =========================================================
 * login.js
 * Validasi form login sisi klien (JavaScript)
 * BKAD - PT Mitracom Solusi Teknologi
 * =========================================================
 * Catatan:
 * Validasi di sini HANYA validasi sisi klien (format & wajib isi)
 * untuk pengalaman pengguna yang responsif. Validasi & otentikasi
 * SEBENARNYA (cek ke database, hashing password) WAJIB dilakukan
 * ulang di sisi server (PHP) pada tahap backend berikutnya,
 * karena validasi JS dapat dilewati oleh pengguna.
 */

document.addEventListener('DOMContentLoaded', function () {

  const form = document.getElementById('formLogin');
  if (!form) return;

  const usernameInput = document.getElementById('username');
  const passwordInput = document.getElementById('password');
  const groupUsername = document.getElementById('groupUsername');
  const groupPassword = document.getElementById('groupPassword');
  const alertError = document.getElementById('alertError');
  const alertErrorText = document.getElementById('alertErrorText');
  const alertSuccess = document.getElementById('alertSuccess');
  const btnSubmit = document.getElementById('btnSubmit');
  const togglePass = document.getElementById('togglePass');

  /* ---------------------------------------------------
     TOGGLE LIHAT / SEMBUNYIKAN PASSWORD
     --------------------------------------------------- */
  if (togglePass) {
    togglePass.addEventListener('click', function () {
      const isHidden = passwordInput.type === 'password';
      passwordInput.type = isHidden ? 'text' : 'password';
      togglePass.textContent = isHidden ? 'Sembunyikan' : 'Lihat';
    });
  }

  /* ---------------------------------------------------
     HELPER: tampilkan / sembunyikan error per field
     --------------------------------------------------- */
  function setFieldError(groupEl, hasError) {
    if (hasError) {
      groupEl.classList.add('error');
    } else {
      groupEl.classList.remove('error');
    }
  }

  function showFormAlert(message) {
    alertErrorText.textContent = message;
    alertError.classList.add('show');
    alertSuccess.classList.remove('show');
  }

  function hideFormAlert() {
    alertError.classList.remove('show');
  }

  /* ---------------------------------------------------
     VALIDASI UTAMA
     --------------------------------------------------- */
  function validateForm() {
    let isValid = true;
    hideFormAlert();

    const usernameVal = usernameInput.value.trim();
    const passwordVal = passwordInput.value.trim();

    // Validasi Username / Email: wajib diisi, minimal 3 karakter
    if (usernameVal.length < 3) {
      setFieldError(groupUsername, true);
      isValid = false;
    } else {
      setFieldError(groupUsername, false);
    }

    // Validasi Password: wajib diisi, minimal 6 karakter
    if (passwordVal.length < 6) {
      setFieldError(groupPassword, true);
      isValid = false;
    } else {
      setFieldError(groupPassword, false);
    }

    if (!isValid) {
      showFormAlert('Mohon periksa kembali data yang Anda masukkan.');
    }

    return isValid;
  }

  /* ---------------------------------------------------
     VALIDASI REAL-TIME (saat mengetik / keluar field)
     --------------------------------------------------- */
  usernameInput.addEventListener('input', function () {
    if (usernameInput.value.trim().length >= 3) {
      setFieldError(groupUsername, false);
    }
  });

  passwordInput.addEventListener('input', function () {
    if (passwordInput.value.trim().length >= 6) {
      setFieldError(groupPassword, false);
    }
  });

  /* ---------------------------------------------------
     SUBMIT HANDLER
     --------------------------------------------------- */
  form.addEventListener('submit', function (e) {
    // Tahap 1: backend proses_login.php belum tersedia,
    // sehingga submit asli dicegah dan hanya disimulasikan.
    e.preventDefault();

    if (!validateForm()) {
      return;
    }

    // Simulasi proses loading sebelum (nantinya) dikirim ke server
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses...';

    setTimeout(function () {
      alertSuccess.classList.add('show');
      hideFormAlert();
      btnSubmit.disabled = false;
      btnSubmit.innerHTML = '<i class="fa-solid fa-right-to-bracket"></i> Masuk';

      // Pada tahap backend berikutnya, baris di bawah akan diganti
      // dengan form.submit() setelah validasi client-side lolos,
      // agar data benar-benar dikirim ke auth/proses_login.php
      // form.submit();
    }, 900);
  });

});
