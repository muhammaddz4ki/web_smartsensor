const navBar = document.getElementById("navbar");

window.addEventListener("scroll", () => {
  if (window.scrollY >= 50) {
    navBar.classList.add("navbar_active");
  } else {
    navBar.classList.remove("navbar_active");
  }
});
