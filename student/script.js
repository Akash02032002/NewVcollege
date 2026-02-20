/* ============================================
   Student Dashboard - Scripts
   ============================================ */

document.addEventListener("DOMContentLoaded", function () {
  // ---- Password Show/Hide Toggle ----
  const toggleBtn = document.getElementById("togglePasswordBtn");
  const passwordValue = document.getElementById("passwordValue");
  const realPassword = passwordValue
    ? passwordValue.getAttribute("data-password")
    : "";

  if (toggleBtn && passwordValue) {
    toggleBtn.addEventListener("click", function () {
      const icon = toggleBtn.querySelector("i");
      const isHidden = passwordValue.textContent.includes("•");

      if (isHidden) {
        passwordValue.textContent = realPassword;
        icon.classList.remove("bi-eye");
        icon.classList.add("bi-eye-slash");
        toggleBtn.title = "Hide Password";
      } else {
        passwordValue.textContent = "•".repeat(realPassword.length);
        icon.classList.remove("bi-eye-slash");
        icon.classList.add("bi-eye");
        toggleBtn.title = "Show Password";
      }
    });
  }

  // ---- Hover ripple effect on info items ----
  document.querySelectorAll(".info-item").forEach(function (item) {
    item.addEventListener("mouseenter", function () {
      this.style.transition = "all 0.3s ease";
    });
  });

  // ---- Avatar hover pulse ----
  const avatar = document.querySelector(".avatar-circle");
  if (avatar) {
    avatar.addEventListener("mouseenter", function () {
      this.style.boxShadow = "0 0 0 8px rgba(127, 90, 240, 0.2)";
    });
    avatar.addEventListener("mouseleave", function () {
      this.style.boxShadow = "none";
    });
  }
});
