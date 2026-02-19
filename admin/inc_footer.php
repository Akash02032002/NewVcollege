  </main>
  <script src="../js/jquery-3.6.0.min.js"></script>
  <script src="../js/bootstrap.min.js"></script>
  <script>
    // small helper for active nav
    (function(){
      var links = document.querySelectorAll('.navbar-nav .nav-link');
      links.forEach(function(l){ if(l.href === location.href) l.classList.add('active'); });
    })();
  </script>
</body>
</html>
