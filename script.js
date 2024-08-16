let eyeicon = document.getElementById('eyeicon');
let password = document.getElementById('password');
eyeicon.onclick = function () {
  if (password.type == 'password') {
    password.type = 'text';
    // eyeicon.className = 'fa-regular fa-eye';
    eyeicon.src = 'image_icon/eye-open.png';
  } else {
    password.type = 'password';
    // eyeicon.className = 'fa-regular fa-eye-slash';
    eyeicon.src = 'image_icon/eye-close.png';
  }
};
