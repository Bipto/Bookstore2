const profile = document.querySelector('.profile');
const profileCircle = document.getElementById('profile-circle');
const profileDropdown = document.getElementById('profile-dropdown');

profileCircle.addEventListener('click', function(event) {
  event.stopPropagation();

  profileDropdown.classList.toggle('show');
  profile.classList.toggle('open');
});

document.addEventListener('click', function(event) {
  if (!profile.contains(event.target)) {
    profileDropdown.classList.remove('show');
    profile.classList.remove('open');
  }
});
