export const checkBottom = (el) => {
  const rect = el.getBoundingClientRect();

  return rect.bottom <= window.innerHeight;
};

export const createCookie = (name, value, days) => {
  let expires = "";
  if (days) {
    const date = new Date();
    date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000);
    expires = `; expires=${date.toUTCString()}`;
  }
  document.cookie = `${name}=${value}${expires}; path=/`;
};

export const readCookie = (name) => {
  const nameEQ = `${name}=`;
  return (
    document.cookie
      .split("; ")
      .find((cookie) => cookie.startsWith(nameEQ))
      ?.split("=")[1] || null
  );
};

export const deleteCookie = (name) => {
  createCookie(name, "", -1);
};

export const wclFetch = async (data, method = "POST") => {
  const token = await getToken();

  data.set("token", token);

  return fetch(config.ajax_url, {
    method: method,
    body: data,
    credentials: "same-origin",
  });
};

export const getToken = async (action = "submit") => {
  return await grecaptcha.execute(config.recaptcha_key, { action: "submit" });
};

export const wclT = (key, value) => {
  const keys = key.split(".");

  let message = keys.reduce(
    (obj, k) => (obj && obj[k] !== undefined ? obj[k] : undefined),
    i18n
  );

  if (!message) {
    return;
  }

  if (value !== undefined) {
    message = message.replace("{n}", value); // Замінюємо плейсхолдер {n} на значення
  }

  return message;
};

export const test = () => {
  console.log("Test function from helpers.js");
};
