const suggest_wrap = document.querySelector(".suggest");
const basket_wrap = document.querySelector(".basket-wrap");
const basket = document.querySelector(".basket");
const navigative_wrap = document.querySelector(".navigative");
const suggest_toggle = document.querySelector(".suggest .toggle");
const location_suggest = document.querySelector(".location-suggest");

suggest_toggle.addEventListener("click", (e) => {
    e.preventDefault();
    [basket, basket_wrap, suggest_wrap, navigative_wrap].forEach((element) =>
        element.classList.toggle("active")
    );
    suggest_toggle.classList.toggle("icon_active");
    location_suggest.classList.toggle("display_active");
});

window.addEventListener("DOMContentLoaded", (e) => {
    e.preventDefault();
    let basket = JSON.parse(localStorage.getItem("basket")) || [];
    axios.post("/api/getNearLocations", basket).then((res) => {
        if (res.data.length != 0) {
            console.log(res.data.length);
            console.log(res.data);
            const locations = document.querySelector(".locations-near");
            locations.innerHTML = "";
            for (let i = 0; i < res.data.length; i++) {
                const data = res.data[i];
                // console.log(data);
                let images = data.Images.split(", ");
                let html;
                html = `
                    <div class="card">
                        <div class="img"  style="flex: 0 0 30%; width: 30%">
                            <img src="/storage/images/${images[0]}" alt="${
                    data.location_name
                }">
                        </div>
                        <div class="txt" style="flex: 0 0 70%; width: 70%">
                            <h3>
                                <a href="/province/${data.province_id}/${
                    data.location_id
                }">
                                    ${data.location_name}
                                </a>
                            </h3>
                        </div>
                    </div>
                    <hr/>
                    `;

                locations.insertAdjacentHTML("beforeend", html);
            }
        } else {
        }
    });
});