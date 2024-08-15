// Select elements
let profile = document.querySelector('.header .profile-detail');
let searchForm = document.querySelector('.header .search-form');
let navbar = document.querySelector('.header .navbar');

// Toggle profile detail visibility
document.querySelector('#user-btn').onclick = () => {
    profile.classList.toggle('active');
    searchForm.classList.remove('active');
    navbar.classList.remove('active'); // Ensure navbar is hidden when profile is toggled
};

// Toggle search form visibility
document.querySelector('#search-btn').onclick = () => {
    searchForm.classList.toggle('active');
    profile.classList.remove('active');
    navbar.classList.remove('active'); // Ensure navbar is hidden when search form is toggled
};

// Toggle navbar visibility
document.querySelector('#menu-btn').onclick = () => {
    navbar.classList.toggle('active');
    profile.classList.remove('active'); // Ensure profile is hidden when navbar is toggled
    searchForm.classList.remove('active'); // Ensure search form is hidden when navbar is toggled
};



// slider -------------------------------------
const sliderContainer = document.querySelector('.slider-container');
const slides = document.getElementsByClassName('slideBox');
let i = 0;

function nextSlide() {
    slides[i].classList.remove('active');
    i = (i + 1) % slides.length;
    slides[i].classList.add('active');
}

function prevSlide() {
    slides[i].classList.remove('active');
    i = (i - 1 + slides.length) % slides.length;
    slides[i].classList.add('active');
}

// slider -------------------------------------
const btn = document.getElementsByClassName('btn1'); 
const slide = document.getElementById('slide');

btn[0].onclick = function () {
    slide.style.transform = 'translateX(0px)'; 
    for (let i = 0; i < btn.length; i++) {
        btn[i].classList.remove('active');
    }
    this.classList.add('active');
}

btn[1].onclick = function () {
    slide.style.transform = 'translateX(-800px)'; 
    for (let i = 0; i < btn.length; i++) {
        btn[i].classList.remove('active');
    }
    this.classList.add('active');
}

btn[2].onclick = function () {
    slide.style.transform = 'translateX(-1600px)'; 
    for (let i = 0; i < btn.length; i++) {
        btn[i].classList.remove('active');
    }
    this.classList.add('active');
}

btn[3].onclick = function () {
    slide.style.transform = 'translateX(-2400px)'; 
    for (let i = 0; i < btn.length; i++) {
        btn[i].classList.remove('active');
    }
    this.classList.add('active');
}

