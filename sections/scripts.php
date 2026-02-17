<!-- ========================================
     SCRIPTS SECTION - JavaScript Files
     ======================================== -->
<!-- Core JavaScript Libraries -->
<script src="js/jquery-3.6.0.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.counterup.min.js"></script>
<script src="js/jquery.meanmenu.min.js"></script>
<script src="js/custom.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Apply Modal Handler -->
<script>
  $(document).on("click", ".open-apply-modal", function () {
    let collegeName = $(this).data("college");
    let collegeId = $(this).data("college-id");
    $("#modalCollegeName").val(collegeName);
    $("#modalCollegeId").val(collegeId);
  });
</script>

<!-- Tawk.to Chat Widget -->
<script type="text/javascript">
  var Tawk_API = Tawk_API || {},
    Tawk_LoadStart = new Date();
  (function () {
    var s1 = document.createElement("script"),
      s0 = document.getElementsByTagName("script")[0];
    s1.async = true;
    s1.src = "https://embed.tawk.to/6858d6a1d74f68191345b7f9/1iudgcr97";
    s1.charset = "UTF-8";
    s1.setAttribute("crossorigin", "*");
    s0.parentNode.insertBefore(s1, s0);
  })();
</script>

<!-- Performance Monitoring Script -->
<script>
  "undefined" === typeof _trfq || (window._trfq = []);
  ("undefined" === typeof _trfd && (window._trfd = []),
    _trfd.push(
      { "tccl.baseHost": "secureserver.net" },
      { ap: "cpsh-oh" },
      { server: "p3plzcpnl509019" },
      { dcenter: "p3" },
      { cp_id: "10240819" },
      { cp_cl: "8" },
    )); // Monitoring performance to make your website faster. If you want to opt-out, please contact web hosting support.
</script>
<script src="js/scc-c2.min.js"></script>

  </body>
</html>
