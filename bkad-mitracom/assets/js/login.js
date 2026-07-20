/**
 * assets/js/login.js
 * Validasi sederhana sisi klien untuk form login.
 * Catatan: ini BUKAN pengganti validasi/otentikasi di sisi server.
 * Setiap input tetap harus divalidasi ulang & di-sanitasi di PHP
 * (auth/proses_login.php) sebelum dicocokkan ke database.
 */
document.addEventListener('DOMContentLoaded', function () {
  var form = document.getElementById('loginForm');
  if (!form) return;

  var groupUsername = document.getElementById('groupUsername');
  var groupPassword = document.getElementById('groupPassword');
  var inputUsername = document.getElementById('username');
  var inputPassword = document.getElementById('password');
  var loginAlert = document.getElementById('loginAlert');

  function setError(group, hasError) {
    group.classList.toggle('has-error', hasError);
  }

  function validate() {
    var valid = true;

    var usernameVal = inputUsername.value.trim();
    if (usernameVal === '') {
      setError(groupUsername, true);
      valid = false;
    } else {
      setError(groupUsername, false);
    }

    var passwordVal = inputPassword.value;
    if (passwordVal.length < 6) {
      setError(groupPassword, true);
      valid = false;
    } else {
      setError(groupPassword, false);
    }

    return valid;
  }

  form.addEventListener('submit', function (e) {
    var isValid = validate();

    if (!isValid) {
      e.preventDefault();
      loginAlert.classList.add('show');
    } else {
      loginAlert.classList.remove('show');
      // TAHAP 1: backend proses_login.php belum tersedia,
      // jadi submit sungguhan sengaja dicegah agar tidak error 404.
      // Hapus baris preventDefault() di bawah setelah backend siap.
      e.preventDefault();
      alert('Validasi berhasil. (Simulasi) Proses otentikasi ke server akan diaktifkan pada tahap pengembangan berikutnya.');
    }
  });

  // Bersihkan pesan error saat pengguna mulai mengetik ulang
  [inputUsername, inputPassword].forEach(function (input) {
    input.addEventListener('input', function () {
      loginAlert.classList.remove('show');
    });
  });
});
