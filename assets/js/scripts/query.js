document.addEventListener("DOMContentLoaded", function () {
  const postsContainer = document.querySelector("#wcl-posts-container");
  const loadMoreBtn = document.querySelector("#load-more");

  // универсальная функция загрузки постов
  async function fetchPosts(cat, pg, append = false) {
    try {
      const formData = new FormData();
      formData.append("action", "wcl_get_ai_tools");
      formData.append("nonce", config.nonce);
      formData.append("cat", cat || "");
      formData.append("pg", pg);

      const response = await fetch(config.ajax_url, {
        method: "POST",
        body: formData,
      });
      const data = await response.json();

      if (data.success) {
        // очищаем контейнер при выборе категории
        if (!append) {
          while (postsContainer.firstChild) {
            postsContainer.removeChild(postsContainer.firstChild);
          }
        }

        // вставляем новые посты
        postsContainer.insertAdjacentHTML("beforeend", data.html);

        // обновляем URL без перезагрузки
        const url = new URL(window.location);
        if (cat) url.searchParams.set("cat", cat);
        else url.searchParams.delete("cat");
        url.searchParams.set("pg", pg);
        window.history.pushState({}, "", url);

        // обновляем кнопку Load More
        if (data.has_more) {
          loadMoreBtn.style.display = "block";
          loadMoreBtn.dataset.cat = cat || "";
          loadMoreBtn.dataset.pg = pg;
        } else {
          loadMoreBtn.style.display = "none";
        }
      }
    } catch (error) {
      console.error("Ошибка AJAX:", error);
    }
  }

  // один слушатель для кликов по категориям и Load More
  document.addEventListener("click", function (e) {
    const target = e.target.closest("[data-cat], #load-more");
    if (!target) return;

    e.preventDefault();

    // категория
    if (target.hasAttribute("data-cat") && !target.matches("#load-more")) {
      const cat = target.dataset.cat;
      fetchPosts(cat, 1, false);
    }

    // Load More
    if (target.id === "load-more") {
      const cat = target.dataset.cat;
      const pg = parseInt(target.dataset.pg, 10) + 1;
      fetchPosts(cat, pg, true);
    }
  });
});
