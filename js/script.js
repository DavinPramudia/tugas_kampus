window.addEventListener('scroll', function() {
  const header = document.querySelector('header');
  if (window.scrollY > 100) {
    header.classList.add('scrolled');
  } else {
    header.classList.remove('scrolled');
  }
});

const hamburger = document.getElementById('hamburger');
const menu = document.querySelector('.menu');

hamburger.addEventListener('click', () => {
  hamburger.classList.toggle('active');
  menu.classList.toggle('show');
});


const slides = document.querySelectorAll('.slide');
const next = document.querySelector('.next');
const prev = document.querySelector('.prev');
let index = 0;

// Fungsi untuk menampilkan slide sesuai index
function showSlide(n) {
  slides[index].classList.remove('active');
  index = (n + slides.length) % slides.length;
  slides[index].classList.add('active');
}

// Tombol manual
next.addEventListener('click', () => showSlide(index + 1));
prev.addEventListener('click', () => showSlide(index - 1));

// Auto ganti setiap 5 detik
setInterval(() => {
  showSlide(index + 1);
}, 5000);

document.addEventListener("DOMContentLoaded", function() {
  const backToTopBtn = document.getElementById("backToTop");

  // Pastikan tombolnya ada
  if (backToTopBtn) {
    window.addEventListener("scroll", function() {
      if (window.scrollY > 200) {
        backToTopBtn.classList.add("show");
      } else {
        backToTopBtn.classList.remove("show");
      }
    });

    backToTopBtn.addEventListener("click", function(e) {
      e.preventDefault();
      window.scrollTo({
        top: 0,
        behavior: "smooth"
      });
    });
  }
});






