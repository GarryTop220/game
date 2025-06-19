document.addEventListener('DOMContentLoaded', function() {
  let mainImage = document.querySelector('.main-image');
  let thumbnails = document.querySelectorAll('.carousel-thumbnails img');
  let price = document.querySelector('.carousel-info .price');
  let platforms = document.querySelector('.carousel-info .platforms');

  thumbnails.forEach((thumbnail, index) => {
      thumbnail.addEventListener('click', function() {
          mainImage.src = thumbnail.src;
          // Update price and platforms based on the clicked thumbnail
          if(index === 0) {
              price.textContent = "$19.99";
              platforms.textContent = "PC, PS4, Xbox One";
          } else if(index === 1) {
              price.textContent = "$29.99";
              platforms.textContent = "PC, PS4";
          } else if(index === 2) {
              price.textContent = "$9.99";
              platforms.textContent = "PC, Xbox One";
          } else if(index === 3) {
              price.textContent = "$14.99";
              platforms.textContent = "PC, PS4, Xbox One, Switch";
          }
      });
  });
});
