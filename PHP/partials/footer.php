<script src="../vendor/components/jquery/jquery.min.js"></script>
<script src="../vendor/select2/select2/dist/js/select2.min.js"></script>
<script src="../vendor/thomaspark/bootswatch/docs/_vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="../Assets/theme.js"></script>
<script src="../Assets/simple-datatables/simple-datatables.min.js"></script>
<script src="../Assets/sweetalert2/sweetalert2.min.js"></script>

<script>
  $('.select2').select2({
    theme: 'bootstrap-5'
  })

  // enable bootstrap tooltips
  document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(trigger => new bootstrap.Tooltip(trigger))
</script>
