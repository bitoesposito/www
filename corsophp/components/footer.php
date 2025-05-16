<footer class="footer mt-auto py-3 bg-body-tertiary">
  <div class="container">
    <span class="text-body-secondary">Il <?= date('d/m/Y') ?> <a href="http://wa.me/+393401582828">scrivimi</a> e ricordami di prendere le vitamine</span>
  </div>
</footer>

<script src="./js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const message = document.getElementById('message');
    if (message) {
      // Attendi 3 secondi prima di iniziare il fade out
      setTimeout(() => {
        message.style.opacity = '0';
        // Rimuovi l'elemento dopo che l'animazione è completata
        setTimeout(() => {
          message.remove();
        }, 1000); // 1000ms = durata della transizione CSS
      }, 3000);
    }
  });
</script>
</body>

</html>