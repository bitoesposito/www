<footer class="footer mt-auto py-3 bg-body-tertiary">
  <div class="container">
    <span>© Blog system</span><br>
    <small class="text-muted">On <?= date('d/m/Y') ?> <a href="http://wa.me/+393401582828">remind me</a> to take my vitamins</small>
  </div>
</footer>

<script src="./js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const message = document.getElementById('message');
    if (message) {
      // Wait 3 seconds before starting the fade out
      setTimeout(() => {
        message.style.opacity = '0';
        // Remove the element after the animation is complete
        setTimeout(() => {
          message.remove();
        }, 1000); // 1000ms = transition duration
      }, 3000);
    }
  });
</script>
</body>

</html>