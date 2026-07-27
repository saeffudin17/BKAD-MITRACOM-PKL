            <!-- Visual Footer -->
            <footer class="footer mt-auto py-3 text-muted small" style="border-top: 1px solid var(--border-color); margin-top: 2rem !important; background: transparent;">
                <div class="container-fluid d-flex justify-content-between align-items-center">
                    <div>
                        &copy; <?= date('Y') ?> <strong>E-Arsip SP2D</strong>. Hak Cipta Dilindungi.
                    </div>
                    <div>
                        Dibuat dengan <i class="fa-solid fa-heart text-danger mx-1"></i> untuk efisiensi kearsipan.
                    </div>
                </div>
            </footer>
        </div> <!-- End Content Wrapper -->
        </div> <!-- End Main Panel -->
    </div> <!-- End Wrapper -->

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- DataTables Buttons -->
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Custom JS -->
    <script src="<?= base_url('assets/js/main.js') ?>?v=<?= time() ?>"></script>
    
    <!-- Render custom scripts if any -->
    <?php if(isset($custom_scripts)) echo $custom_scripts; ?>
</body>
</html>
