document.addEventListener("DOMContentLoaded", () => {
  
  const photoInput = document.getElementById("photo");
  const avatar = document.getElementById("avatar");

  if (photoInput && avatar) {
    photoInput.addEventListener("change", () => {
      const file = photoInput.files[0];

      if (!file) return;

      const reader = new FileReader();

      reader.addEventListener("load", () => {
        avatar.src = reader.result;
      });

      reader.readAsDataURL(file);
    });
  }

  
  const profileForm = document.getElementById("profile-form");
  const message = document.getElementById("message");

  if (profileForm && message) {
    profileForm.addEventListener("submit", (event) => {
      event.preventDefault();
      message.textContent = "Changes saved!";
    });
  }

 
  const stars = document.querySelectorAll(".star");
  const rating = document.getElementById("rating");

  if (stars.length && rating) {
    stars.forEach((star) => {
      star.addEventListener("click", () => {
        const value = Number(star.dataset.value);
        rating.value = value;

        stars.forEach((item) => {
          const selected = Number(item.dataset.value) <= value;

          item.classList.toggle("selected", selected);
          item.textContent = selected ? "★" : "☆";
          item.setAttribute(
            "aria-checked",
            item === star ? "true" : "false"
          );
        });
      });
    });
  }
});