document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.querySelector(".wcl-hero__search-input");
  const searchResultsWrapper = document.querySelector(
    ".wcl-hero__autocomplete-wrapper"
  );

  function debounce(func, delay) {
    let timeout;
    return function (...args) {
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(this, args), delay);
    };
  }

  async function fetchSearchResults(event) {
    const input = event.target;

    const formData = new FormData();
    formData.append("action", "autocomplete");
    formData.append("nonce", config.nonce);
    formData.append("query", input.value);

    try {
      const response = await fetch(config.ajax_url, {
        method: "POST",
        body: formData,
      });

      const result = await response.json();

      if (result.success && result.html) {
        searchResultsWrapper.innerHTML = result.html;
      } else {
        searchResultsWrapper.innerHTML = "";
      }
    } catch (error) {
      console.error(error);
      searchResultsWrapper.innerHTML = "";
    }
  }

  const debouncedFetchSearchResults = debounce(fetchSearchResults, 500);

  if (searchInput) {
    searchInput.addEventListener("input", debouncedFetchSearchResults);
  }
});
