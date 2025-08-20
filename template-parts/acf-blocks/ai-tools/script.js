document.addEventListener("DOMContentLoaded", function () {
  //categories
  const catSpans = document.querySelectorAll(".cat-span");

  async function fetchCategories(el) {
    const parentId = el.getAttribute("data-parent-id") ?? null;
    const id = el.getAttribute("data-id") ?? null;

    const parent = el.closest(".container.text-center");
    const container = parent.querySelector(".row.g-4");

    const form = document.getElementById("filter");
    const formData = new FormData(form);

    formData.append("action", "show_ai_tools_categories");
    formData.append("nonce", config.nonce);
    formData.append("id", id);
    formData.append("parent_id", parentId);

    try {
      const response = await fetch(config.ajax_url, {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success) {
        container.innerHTML = result.data;
      } else {
        container.innerHTML = `<div class="col-12 text-center"><p>${result.message}</p></div>`;
      }
    } catch (error) {
      container.innerHTML = `<div class="col-12 text-center"><p>Error loading data.</p></div>`;
      console.error("Fetch error:", error);
    }
  }

  catSpans.forEach((span) => {
    span.addEventListener("click", function () {
      fetchCategories(this);
    });
  });

  //filter

  async function filterSubmit(e) {
    e.preventDefault();

    const form = this;
    const formData = new FormData(form);
    formData.append("action", "filter");

    try {
      const res = await fetch(config.ajax_url, {
        method: "POST",
        body: formData,
      });

      const response = await res.json();

      if (response.success === true || response.success === 1) {
        console.log("Filter applied successfully.");

        if (response.data.html_blocks) {
          for (const [termId, html] of Object.entries(
            response.data.html_blocks
          )) {
            const container = document.querySelector(
              `.row[data-term-id="${termId}"]`
            );
            if (container) {
              container.innerHTML = html;
            } else {
              console.warn(`Container with data-term-id="${termId}" not found`);
            }
          }
        }
      } else {
        console.warn("Filter failed or no data");
      }
    } catch (error) {
      console.error("Error during filter submission:", error);
    }
  }

  const filterForm = document.getElementById("filter");

  if (filterForm) {
    filterForm.addEventListener("submit", filterSubmit);
  }
});
