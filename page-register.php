<?php

get_header();
?>

<div class="container py-5" style="max-width: 480px;">
  <h2 class="mb-4">Registration</h2>

  <form id="wcl-register-form" enctype="multipart/form-data" class="needs-validation" novalidate>
    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('wcl_nonce'); ?>">

    <div class="mb-3">
      <label for="wcl_username" class="form-label">User name*</label>
      <input type="text" name="username" id="wcl_username" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="wcl_email" class="form-label">Email*</label>
      <input type="email" name="email" id="wcl_email" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="wcl_password" class="form-label">Password*</label>
      <div class="input-group">
        <input type="password" class="form-control" id="wcl_password" name="password" required>
        <button class="btn btn-outline-secondary" type="button" id="togglePassword">Show</button>
      </div>
    </div>


    <div class="mb-3">
      <label for="wcl_password_repeat" class="form-label">Confirm password*</label>
      <input type="password" name="password_repeat" id="wcl_password_repeat" class="form-control" required>
    </div>

    <div class="mb-3">
      <label for="wcl_avatar" class="form-label">Avatar (optional)</label>
      <input type="file" name="avatar" id="wcl_avatar" class="form-control" accept="image/*">
    </div>


    <button type="submit" class="btn btn-primary w-100">Submit</button>
  </form>

  <div id="wcl-register-message" class="mt-3"></div>
  <style>
    /* Скрытый чекбокс */
    .hidden-checkbox {
      position: absolute;
      width: 1px;
      height: 1px;
      margin: -1px;
      padding: 0;
      border: 0;
      overflow: hidden;
      clip: rect(0 0 0 0);
    }

    /* Стилизация "визуального" чекбокса */
    .custom-checkbox {
      display: inline-block;
      width: 20px;
      height: 20px;
      border: 2px solid #333;
      border-radius: 4px;
      position: relative;
      cursor: pointer;
      vertical-align: middle;
      transition: box-shadow 0.2s, border-color 0.2s;
    }

    /* Псевдоэлемент ::before для состояния checked */
    .hidden-checkbox:checked+.custom-checkbox::before {
      content: '';
      position: absolute;
      top: 2px;
      left: 6px;
      width: 6px;
      height: 12px;
      border: solid #000;
      border-width: 0 2px 2px 0;
      transform: rotate(45deg);
    }

    /* Визуальная индикация фокуса */
    .custom-checkbox:focus {
      outline: none;
      box-shadow: 0 0 0 3px rgba(21, 156, 228, 0.6);
      border-color: #159ce4;
    }

    /* Чтобы текст и чекбокс были выровнены */
    label {
      display: flex;
      align-items: center;
      gap: 8px;
    }
  </style>

  <label>
    <input type="checkbox" class="hidden-checkbox" aria-checked="false" aria-label="Accept Terms">
    <span class="custom-checkbox" tabindex="0"></span>
    Accept Terms
  </label>

  <script>
    const checkbox = document.querySelector('.hidden-checkbox');
    const visual = document.querySelector('.custom-checkbox');

    // Обновление ARIA при смене состояния
    checkbox.addEventListener('change', () => {
      checkbox.setAttribute('aria-checked', checkbox.checked);
    });

    // Переключение через клавиши на визуальном элементе
    visual.addEventListener('keydown', (e) => {
      if (e.key === ' ' || e.key === 'Enter') {
        e.preventDefault();
        checkbox.checked = !checkbox.checked;
        checkbox.setAttribute('aria-checked', checkbox.checked);
      }
    });

    // Клик по визуальному элементу тоже переключает чекбокс
    visual.addEventListener('click', () => {
      checkbox.checked = !checkbox.checked;
      checkbox.setAttribute('aria-checked', checkbox.checked);
    });
  </script>



</div>



<?php get_footer(); ?>