document.addEventListener("DOMContentLoaded", function () {
  const gallery = document.querySelector(".wcl-gallery");

  if (gallery) {
    Fancybox.bind('[data-fancybox="gallery"]', {
      zoomEffect: false,
      fadeEffect: false,
      showClass: "f-fadeIn",
      hideClass: false,
      dragToClose: false,
      Carousel: {
        Toolbar: {
          absolute: false,
          display: {
            // middle: ["counter"],
            // right: ["toggleFull", "close"],
            left: ["flipX", "flipY"],
            middle: ["zoomIn", "zoomOut"],
            right: ["toggleFull", "close", "download"],
          },
        },
        Thumbs: {
          type: "classic",
        },
      },
    });
  }
});

////////////////////////////////////////////////////////////////////////////////////////////////////

// document.addEventListener("DOMContentLoaded", function () {
//   Fancybox.bind("[data-fancybox]", {
//     theme: "light",
//     mainStyle: {
//       "--f-toolbar-padding": "0",
//       "--f-button-svg-stroke-width": "1.5",
//       "--f-arrow-svg-stroke-width": "1.75",
//       "--f-thumb-width": "82px",
//       "--f-thumb-height": "82px",
//       "--f-thumb-border-radius": "8px",
//       "--f-thumb-selected-shadow":
//         "inset 0 0 0 2px #fff, 0 0 0 1.5px #00ffaaff",
//     },
//     zoomEffect: true,
//     fadeEffect: true,
//     showClass: "f-fadeIn",
//     hideClass: false,
//     dragToClose: true,
//     Carousel: {
//       Toolbar: {
//         absolute: false,
//         display: {
//           middle: ["counter"],
//           right: ["toggleFull", "close"],
//         },
//       },
//       Thumbs: {
//         type: "classic",
//       },
//     },
//   });
// });
