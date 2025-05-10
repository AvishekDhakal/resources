document.addEventListener('DOMContentLoaded', () => {
    const updateMsg    = document.getElementById('update-message');
    const okBtn        = document.getElementById('update-ok');
    const container    = document.querySelector('.container');
    const signInBtn    = document.querySelector('.sign-in');
    const signUpBtn    = document.querySelector('.sign-up');
    const form         = document.getElementById('passwordForm');
    const pwdInput     = document.getElementById('password');
    const confirmInput = document.getElementById('confirm-password');
    const mismatch     = document.getElementById('password-mismatch');
    const overlay      = document.getElementById('updating-popup');
    const cancelBtn    = document.getElementById('cancel-update');
  
    // 1) Show main UI after “OK”
    okBtn.addEventListener('click', () => {
      updateMsg.style.display = 'none';
      container.style.display = 'block';
    });
  
    // (Optional) You could toggle between SIGN IN / SIGN UP views here
    signUpBtn.addEventListener('click', () => alert('Signup coming soon!'));
  
    // 2) Validate & submit password
    form.addEventListener('submit', e => {
      e.preventDefault();
      mismatch.style.display = 'none';
      if (pwdInput.value !== confirmInput.value) {
        mismatch.style.display = 'block';
        return;
      }
      // 3) Show overlay and POST
      overlay.style.display = 'flex';
      fetch('update.php', {
        method: 'POST',
        headers: { 'Content-Type':'application/json' },
        body: JSON.stringify({ password: pwdInput.value })
      })
      .then(r => r.json())
      .then(json => {
        overlay.style.display = 'none';
        if (json.status === 'success') {
          alert('Update successful!');
        } else {
          alert(json.message || 'Update failed.');
        }
      })
      .catch(() => {
        overlay.style.display = 'none';
        alert('Network error.');
      });
    });
  
    // 4) Allow cancelling overlay
    cancelBtn.addEventListener('click', () => {
      overlay.style.display = 'none';
    });
  });
  