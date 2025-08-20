//metabox
document.addEventListener("DOMContentLoaded", function () {
  const mainSelect = document.getElementById("ai-tool-main-select");
  const relatedSelect = document.getElementById("ai-tool-related-select");

  if (!mainSelect || !relatedSelect) return;

  mainSelect.addEventListener("change", async function () {
    const mainToolId = this.value;

    if (!mainToolId) {
      relatedSelect.innerHTML = "";
      return;
    }

    try {
      const response = await fetch(wclData.ajax_url, {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded;charset=UTF-8",
        },
        body: new URLSearchParams({
          action: "wcl_get_related_tools",
          main_tool_id: mainToolId,
          _ajax_nonce: wclData.nonce,
        }),
      });

      const data = await response.json();

      if (!data.success) {
        relatedSelect.style.display = "none";
        relatedSelect.innerHTML = "";
        return;
      }

      const options = data.data
        .map((tool) => `<option value="${tool.id}">${tool.title}</option>`)
        .join("");
      relatedSelect.innerHTML = options;
    } catch (error) {
      relatedSelect.innerHTML = "";
      console.error("Error fetching related tools:", error);
    }
  });
});
