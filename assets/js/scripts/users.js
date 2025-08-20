document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("wcl-register-form");
  const messageEl = document.getElementById("wcl-register-message");

  if (form && messageEl) {
    // show/hide password
    const toggleBtn = document.getElementById("togglePassword");
    const passwordInput = document.getElementById("wcl_password");
    if (toggleBtn && passwordInput) {
      toggleBtn.addEventListener("click", () => {
        if (passwordInput.type === "password") {
          passwordInput.type = "text";
          toggleBtn.textContent = "Hide";
        } else {
          passwordInput.type = "password";
          toggleBtn.textContent = "Show";
        }
      });
    }

    // JustValidate js
    const validation = new JustValidate("#wcl-register-form", {
      errorFieldCssClass: "is-invalid",
      errorLabelStyle: { color: "red", fontSize: "14px" },
    });

    validation
      .addField("#wcl_username", [
        { rule: "required", errorMessage: "Username is required" },
        {
          rule: "minLength",
          value: 3,
          errorMessage: "Username must be at least 3 characters",
        },
      ])
      .addField("#wcl_email", [
        { rule: "required", errorMessage: "Email is required" },
        { rule: "email", errorMessage: "Invalid email format" },
      ])
      .addField("#wcl_password", [
        { rule: "required", errorMessage: "Password is required" },
        {
          validator: (value) => {
            const hasLetter = /[a-zA-Z]/.test(value);
            const hasNumber = /\d/.test(value);
            return value.length >= 8 && hasLetter && hasNumber;
          },
          errorMessage:
            "Password must be at least 8 chars, include letters and numbers",
        },
      ])
      .addField("#wcl_password_repeat", [
        { rule: "required", errorMessage: "Please confirm your password" },
        {
          validator: (value, fields) =>
            value === fields["#wcl_password"].elem.value,
          errorMessage: "Passwords do not match",
        },
      ])
      .onSuccess(async (event) => {
        event.preventDefault();
        messageEl.innerHTML = "";

        const formData = new FormData(form);

        try {
          const response = await fetch("/wp-json/wcl/v1/register", {
            method: "POST",
            body: formData,
          });

          const result = await response.json();

          if (result.success) {
            messageEl.innerHTML =
              '<p style="color:green;">Registration initiated. Please check your email to confirm.</p>';
            form.reset();
          } else {
            messageEl.innerHTML = `<p style="color:red;">${
              result.message || "Error"
            }</p>`;
          }
        } catch (error) {
          console.error(error);
          messageEl.innerHTML =
            '<p style="color:red;">Error submitting form</p>';
        }
      });
  }

  //page account

  const editForm = document.getElementById("update-profile-form");
  const messageElAccount = document.getElementById("profile-message");

  async function editFormSubmit(e) {
    e.preventDefault();

    const formData = new FormData(editForm);
    formData.append("action", "wcl_update_profile");
    formData.append("nonce", config.nonce);

    try {
      const response = await fetch(config.ajax_url, {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success === 1) {
        messageElAccount.innerHTML =
          '<p style="color:green;">Profile updated successfully!</p>';

        if (result.data && result.data.avatar_url) {
          const avatarImg = document.getElementById("current-avatar");
          if (avatarImg) {
            avatarImg.src = result.data.avatar_url;
          }
        }
        if (result.data && result.data.description) {
          const descWrapper = document.getElementById("desc");
          if (descWrapper) {
            descWrapper.innerHTML = result.data.description.replace(
              /\n/g,
              "<br>"
            );
          }
        }
      } else {
        messageElAccount.innerHTML = `<p style="color:red;">${
          result.data || "Error updating profile"
        }</p>`;
      }
    } catch (error) {
      messageElAccount.innerHTML =
        '<p style="color:red;">Error sending request</p>';
      console.error(error);
    }
  }

  if (editForm) {
    editForm.addEventListener("submit", editFormSubmit);
  }

  // log in
  const formLogIn = document.getElementById("wcl-login-form");

  async function logInSubmit(e) {
    e.preventDefault();

    const formData = new FormData(formLogIn);
    formData.append("action", "log_in_handler");

    try {
      const res = await fetch(config.ajax_url, {
        method: "POST",
        body: formData,
      });

      const result = await res.json();

      const msgBox = document.querySelector(".wcl-login-message");

      if (result.success) {
        msgBox.innerHTML =
          '<p style="color:green;">Login successful. Redirecting...</p>';
        setTimeout(() => (window.location.href = "/account"), 1000);
      } else {
        msgBox.innerHTML = `<p style="color:red;">${result.message}</p>`;
      }
    } catch (err) {
      console.error("Login error:", err);
    }
  }

  if (formLogIn) {
    formLogIn.addEventListener("submit", logInSubmit);
  }

  // Lost Password form
  const lostPassForm = document.getElementById("wcl-lost-password");
  if (lostPassForm) {
    lostPassForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const emailField = document.getElementById("lost-pass-email");
      const formData = new FormData(lostPassForm);
      formData.append("action", "wcl_lost_password_handler");

      try {
        const res = await fetch(config.ajax_url, {
          method: "POST",
          body: formData,
        });
        const result = await res.json();

        if (result.success) {
          Swal.fire({
            icon: "success",
            title: "Success",
            text: result.data.message,
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: result.data.message,
          });
        }
      } catch (err) {
        console.error("Lost password error:", err);
        Swal.fire({
          icon: "error",
          title: "Something went wrong",
          text: "Please try again later.",
        });
      }
    });
  }

  // Password Reset form
  const resetPassForm = document.getElementById("wcl-password-reset-form");
  if (resetPassForm) {
    resetPassForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const formData = new FormData(resetPassForm);
      formData.append("action", "wcl_password_reset_handler");

      try {
        const res = await fetch(config.ajax_url, {
          method: "POST",
          body: formData,
        });
        const result = await res.json();

        if (result.success) {
          alert(result.data.message);
          window.location.href = "/log-in";
          return;
        } else {
          alert(result.data.message);
        }
      } catch (err) {
        console.error("Password reset error:", err);
        alert("Something went wrong");
      }
    });
  }
});
