const hamburger = document.getElementById('hamburger-icon');
const navList = document.getElementById('nav-list');

// Toggle the menu on hamburger click
hamburger.addEventListener('click', () => {
    navList.classList.toggle('active');
});
