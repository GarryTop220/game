document.addEventListener("DOMContentLoaded", function() {
    const formPages = document.querySelectorAll(".form-page");
    const prevBtn = document.querySelector(".prev");
    const nextBtn = document.querySelector(".next");
    let currentPageIndex = 0;
  
    function showPage(index) {
        formPages.forEach((page, i) => {
            if (i === index) {
                page.style.display = "block";
            } else {
                page.style.display = "none";
            }
        });
    }
  
    showPage(currentPageIndex);
  
    prevBtn.addEventListener("click", function() {
        currentPageIndex = Math.max(currentPageIndex - 1, 0);
        showPage(currentPageIndex);
    });
  
    nextBtn.addEventListener("click", function() {
        currentPageIndex = Math.min(currentPageIndex + 1, formPages.length - 1);
        showPage(currentPageIndex);
    });
});