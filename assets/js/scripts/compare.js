import { test } from "./helpers";

document.addEventListener("DOMContentLoaded", function () {
  // //FIRST TOOL AUTOCOMPLETE START
  // function debounce(func, delay) {
  //   let timeout;
  //   return function (...args) {
  //     clearTimeout(timeout);
  //     timeout = setTimeout(() => func.apply(this, args), delay);
  //   };
  // }

  // const firstToolInput = document.getElementById("first-tool");
  // const firstToolDropWrapper = document.getElementById(
  //   "first-tool-drop-wrapper"
  // );
  // const lastToolInput = document.getElementById("last-tool");
  // const lastToolDropWrapper = document.getElementById("last-tool-drop-wrapper"); //

  // if (lastToolInput) {
  //   lastToolInput.disabled = true;
  // }

  // async function fetchFirstToolResults(e) {
  //   const input = e.target;
  //   const value = input.value.trim();

  //   if (value.length < 2) {
  //     firstToolDropWrapper.innerHTML = "";
  //     return;
  //   }

  //   const formData = new FormData();
  //   formData.append("action", "autocomplete_first_tool");
  //   formData.append("nonce", config.nonce);
  //   formData.append("query", value);

  //   try {
  //     const res = await fetch(config.ajax_url, {
  //       method: "POST",
  //       body: formData,
  //     });

  //     const result = await res.json();

  //     if (result.success === 1 && result.html) {
  //       console.log(result);
  //       firstToolDropWrapper.innerHTML = result.html;
  //     }
  //   } catch (error) {
  //     console.error(error);
  //     firstToolDropWrapper.innerHTML = "";
  //   }
  // }

  // if (firstToolInput) {
  //   firstToolInput.addEventListener(
  //     "input",
  //     debounce(fetchFirstToolResults, 500)
  //   );

  //   firstToolInput.addEventListener("input", function () {
  //     firstToolInput.dataset.id = "";
  //     if (lastToolInput) {
  //       lastToolInput.value = "";
  //       lastToolInput.disabled = true;

  //       lastToolInput.dataset.id = "";

  //       updateCompareButtonState();

  //       if (lastToolDropWrapper) {
  //         lastToolDropWrapper.innerHTML = "";
  //       }
  //     }
  //   });
  // }

  // if (firstToolDropWrapper) {
  //   firstToolDropWrapper.addEventListener("click", function (e) {
  //     const item = e.target.closest(".wcl-compare__dropdown-item");
  //     if (item) {
  //       const selectedText = item.textContent.trim();
  //       const selectedId = item.dataset.id;

  //       firstToolInput.value = selectedText;
  //       firstToolInput.dataset.id = selectedId;
  //       firstToolDropWrapper.innerHTML = "";
  //       firstToolInput.dataset.id = selectedId;
  //       // ...
  //       updateCompareButtonState();

  //       if (lastToolInput) {
  //         lastToolInput.disabled = false;
  //       }
  //     }
  //   });
  // }

  // //FIRST TOOL AUTOCOMPLETE END
  // FIRST TOOL AUTOCOMPLETE START
  function debounce(func, delay) {
    let timeout;
    return function (...args) {
      clearTimeout(timeout);
      timeout = setTimeout(() => func.apply(this, args), delay);
    };
  }

  const firstToolInput = document.getElementById("first-tool");
  const firstToolDropWrapper = document.getElementById(
    "first-tool-drop-wrapper"
  );
  const lastToolInput = document.getElementById("last-tool");
  const lastToolDropWrapper = document.getElementById("last-tool-drop-wrapper");

  if (lastToolInput) {
    lastToolInput.disabled = true;
  }

  async function fetchFirstToolResults(query = "") {
    const formData = new FormData();
    formData.append("action", "autocomplete_first_tool");
    formData.append("nonce", config.nonce);
    formData.append("query", query);

    try {
      const res = await fetch(config.ajax_url, {
        method: "POST",
        body: formData,
      });

      const result = await res.json();

      if (result.success === 1 && result.html) {
        firstToolDropWrapper.innerHTML = result.html;
      } else {
        firstToolDropWrapper.innerHTML = "";
      }
    } catch (error) {
      console.error(error);
      firstToolDropWrapper.innerHTML = "";
    }
  }

  if (firstToolInput) {
    firstToolInput.addEventListener(
      "input",
      debounce((e) => {
        const value = e.target.value.trim();
        if (value.length >= 2) {
          fetchFirstToolResults(value);
        } else {
          firstToolDropWrapper.innerHTML = "";
        }

        firstToolInput.dataset.id = "";
        if (lastToolInput) {
          lastToolInput.value = "";
          lastToolInput.disabled = true;
          lastToolInput.dataset.id = "";

          updateCompareButtonState();

          if (lastToolDropWrapper) {
            lastToolDropWrapper.innerHTML = "";
          }
        }
      }, 500)
    );

    firstToolInput.addEventListener("focus", () => {
      if (firstToolInput.value.trim().length < 2) {
        fetchFirstToolResults("");
      }
    });

    firstToolInput.addEventListener("blur", () => {
      setTimeout(() => {
        firstToolDropWrapper.innerHTML = "";
      }, 200);
    });
  }

  if (firstToolDropWrapper) {
    firstToolDropWrapper.addEventListener("click", function (e) {
      const item = e.target.closest(".wcl-compare__dropdown-item");
      if (item) {
        const selectedText = item.textContent.trim();
        const selectedId = item.dataset.id;

        firstToolInput.value = selectedText;
        firstToolInput.dataset.id = selectedId;
        firstToolDropWrapper.innerHTML = "";

        updateCompareButtonState();

        if (lastToolInput) {
          lastToolInput.disabled = false;
        }
      }
    });
  }
  // FIRST TOOL AUTOCOMPLETE END

  // //LAST TOOL AUTOCOMPLETE START
  // async function fetchLastToolResults(e) {
  //   const input = e.target;
  //   const value = input.value.trim();

  //   if (value.length < 2) {
  //     if (lastToolDropWrapper) {
  //       lastToolDropWrapper.innerHTML = "";
  //     }
  //     return;
  //   }

  //   const idFirstTool = firstToolInput.dataset.id;
  //   if (!idFirstTool) return;

  //   const formData = new FormData();
  //   formData.append("action", "autocomplete_last_tool");
  //   formData.append("nonce", config.nonce);
  //   formData.append("query", value);
  //   formData.append("id_first", idFirstTool);

  //   try {
  //     const res = await fetch(config.ajax_url, {
  //       method: "POST",
  //       body: formData,
  //     });

  //     const result = await res.json();

  //     if (result.success === 1 && result.html) {
  //       lastToolDropWrapper.innerHTML = result.html;
  //     } else {
  //       lastToolDropWrapper.innerHTML = "";
  //     }
  //   } catch (error) {
  //     console.error(error);
  //     lastToolDropWrapper.innerHTML = "";
  //   }
  // }

  // if (lastToolInput) {
  //   lastToolInput.addEventListener(
  //     "input",
  //     debounce(fetchLastToolResults, 500)
  //   );

  //   lastToolInput.addEventListener("input", function () {
  //     lastToolInput.dataset.id = "";
  //     updateCompareButtonState();
  //   });
  // }

  // if (lastToolDropWrapper) {
  //   lastToolDropWrapper.addEventListener("click", function (e) {
  //     const item = e.target.closest(".wcl-compare__dropdown-item");
  //     if (item) {
  //       const selectedText = item.textContent.trim();
  //       const selectedId = item.dataset.id;

  //       lastToolInput.value = selectedText;
  //       lastToolInput.dataset.id = selectedId;
  //       lastToolDropWrapper.innerHTML = "";
  //       // ...
  //       updateCompareButtonState();
  //     }
  //   });
  // }
  // //LAST TOOL AUTOCOMPLETE END
  // LAST TOOL AUTOCOMPLETE START
  async function fetchLastToolResults(query = "") {
    const idFirstTool = firstToolInput?.dataset?.id;
    if (!idFirstTool) return;

    const formData = new FormData();
    formData.append("action", "autocomplete_last_tool");
    formData.append("nonce", config.nonce);
    formData.append("query", query);
    formData.append("id_first", idFirstTool);

    try {
      const res = await fetch(config.ajax_url, {
        method: "POST",
        body: formData,
      });

      const result = await res.json();

      if (result.success === 1 && result.html) {
        lastToolDropWrapper.innerHTML = result.html;
      } else {
        lastToolDropWrapper.innerHTML = "";
      }
    } catch (error) {
      console.error(error);
      lastToolDropWrapper.innerHTML = "";
    }
  }

  if (lastToolInput) {
    lastToolInput.addEventListener(
      "input",
      debounce((e) => {
        const value = e.target.value.trim();
        if (value.length >= 2) {
          fetchLastToolResults(value);
        } else {
          lastToolDropWrapper.innerHTML = "";
        }

        lastToolInput.dataset.id = "";
        updateCompareButtonState();
      }, 500)
    );

    lastToolInput.addEventListener("focus", () => {
      if (lastToolInput.value.trim().length < 2) {
        fetchLastToolResults("");
      }
    });

    lastToolInput.addEventListener("blur", () => {
      setTimeout(() => {
        lastToolDropWrapper.innerHTML = "";
      }, 200);
    });
  }

  if (lastToolDropWrapper) {
    lastToolDropWrapper.addEventListener("click", function (e) {
      const item = e.target.closest(".wcl-compare__dropdown-item");
      if (item && !item.classList.contains("disabled")) {
        const selectedText = item.textContent.trim();
        const selectedId = item.dataset.id;

        lastToolInput.value = selectedText;
        lastToolInput.dataset.id = selectedId;
        lastToolDropWrapper.innerHTML = "";

        updateCompareButtonState();
      }
    });
  }
  // LAST TOOL AUTOCOMPLETE END

  //BUTTON START

  const compareButton = document.getElementById("compare-btn");

  function updateCompareButtonState() {
    const firstToolSelected = firstToolInput.dataset.id;
    const lastToolSelected = lastToolInput.dataset.id;

    if (firstToolSelected && lastToolSelected) {
      compareButton.disabled = false;
    } else {
      compareButton.disabled = true;
    }
  }

  const compareWrapper = document.getElementById("compare-wrapper");

  async function compareTools() {
    const firstToolId = firstToolInput.dataset.id;
    const lastToolId = lastToolInput.dataset.id;

    const formData = new FormData();
    formData.append("action", "tools_compare");
    formData.append("nonce", config.nonce);
    formData.append("first_tool_id", firstToolId);
    formData.append("last_tool_id", lastToolId);

    try {
      const res = await fetch(config.ajax_url, {
        method: "POST",
        body: formData,
      });

      const result = await res.json();

      if (result.success && result.html) {
        compareWrapper.innerHTML = result.html;

        const { tool1_slug, tool2_slug } = result;

        if (tool1_slug && tool2_slug) {
          const newUrl = `${window.location.origin}/compare/${tool1_slug}-vs-${tool2_slug}`;
          window.history.pushState({}, "", newUrl);
        }
      } else {
        compareWrapper.innerHTML = `<div class="alert alert-warning">${
          result.message || "Comparison failed"
        }</div>`;
      }
    } catch (error) {
      console.warn(error);
      compareWrapper.innerHTML = `<div class="alert alert-danger">Error occurred</div>`;
    }
  }

  if (compareButton) {
    compareButton.addEventListener("click", compareTools);
  }

  //BUTTON END

  test();
});
