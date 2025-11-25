const icon = document.getElementById('profileIcon');
const menu = document.getElementById('profileMenu');
const wrap = document.getElementById('profileWrap');

icon.addEventListener('click', function(event) {
    event.stopPropagation();
    menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
});

document.addEventListener('click', function() {
    menu.style.display = 'none';
});