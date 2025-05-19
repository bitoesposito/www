<footer class="footer mt-auto py-3 bg-body-tertiary">
  <div class="container">
    <span class="text-body-secondary">On <?= date('d/m/Y') ?> <a href="http://wa.me/+393401582828">contact me</a> and remind me to take my vitamins</span>
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