const main_img = document.querySelector(".main-image .image");
const imgs = document.querySelectorAll(".image-box .image");
main_img.style.background = `url('/storage/images/locations/${imgs[0].getAttribute(
    "data-img"
)}')`;
imgs.forEach((img) => {
    img.addEventListener("click", (e) => {
        e.preventDefault();
        let attr = img.getAttribute("data-img");
        main_img.style.background = `url('/storage/images/locations/${attr}')`;
    });
    img.addEventListener("mouseover", (e) => {
        e.preventDefault();
        let attr = img.getAttribute("data-img");
        main_img.style.background = `url('/storage/images/locations/${attr}')`;
    });
});