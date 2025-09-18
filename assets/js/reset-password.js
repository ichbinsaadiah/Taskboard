document.addEventListener("DOMContentLoaded", () => {
  const password = document.getElementById("password");
  const confirmPassword = document.getElementById("confirm_password");
  const submitBtn = document.getElementById("submitBtn");

  const checks = {
    length: (val) => val.length >= 7,
    uppercase: (val) => /[A-Z]/.test(val),
    lowercase: (val) => /[a-z]/.test(val),
    number: (val) => /[0-9]/.test(val),
    symbol: (val) => /[^a-zA-Z0-9]/.test(val),
    match: (val) => val === confirmPassword.value && val !== ""
  };

  function updateChecklist() {
    let val = password.value;
    let allValid = true;

    for (let key in checks) {
      const li = document.getElementById(key);
      const valid = checks[key](val);
      if (key === "match") {
        const matchValid = checks.match(password.value);
        setValidity(li, matchValid);
        if (!matchValid) allValid = false;
      } else {
        setValidity(li, valid);
        if (!valid) allValid = false;
      }
    }

    submitBtn.disabled = !allValid;
  }

  function setValidity(element, isValid) {
    element.classList.remove("valid", "invalid");
    if (isValid) {
      element.classList.add("valid");
      element.innerHTML = `<i class="bi bi-check-circle text-success me-1"></i> ${element.textContent.trim().replace(/^.*?\s/, '')}`;
    } else {
      element.classList.add("invalid");
      element.innerHTML = `<i class="bi bi-x-circle text-danger me-1"></i> ${element.textContent.trim().replace(/^.*?\s/, '')}`;
    }
  }

  password.addEventListener("input", updateChecklist);
  confirmPassword.addEventListener("input", updateChecklist);
});
