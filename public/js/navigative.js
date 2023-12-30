let map;

function init() {
    const urlParams = new URLSearchParams(window.location.search);
    let lat = urlParams.get("lat");
    let lon = urlParams.get("lon");

    // if ("geolocation" in navigator) {
    //     navigator.geolocation.watchPosition(function(position) {
    //         lat = position.coords.latitude;
    //         lon = position.coords.longitude;

    //         // Use the lat and lon values as needed
    //         console.log("Latitude: " + lat + ", Longitude: " + lon);

    //         // Initialize the map and add markers inside the geolocation success callback
    //         initializeMap(lat, lon);
    //     }, function(error) {
    //         console.log(error.message);
    //     }, {
    //         enableHighAccuracy: true, // Request high accuracy
    //         maximumAge: 0, // Do not use cached data
    //         timeout: 5000 // Set a timeout for the request
    //     });
    // } else {
    //     console.log("Geolocation is not available in this browser.");
    //     // You might want to handle this case, e.g., by using default coordinates.
    // }
    function initializeMap(lat, lon) {
        const mapPlaceholder = document.getElementById("map");
        map = new longdo.Map({
            placeholder: mapPlaceholder,
        });

        map.Route.placeholder(document.getElementById("result"));
        map.Route.add(
            new longdo.Marker(
                {
                    lon: lon,
                    lat: lat,
                },
                {
                    title: "จุดเริ่มต้น",
                    detail: "ฉันอยู่ตรงนี้",
                    draggable: false,
                }
            )
        );

        let basket = JSON.parse(localStorage.getItem("basket")) || [];
        // console.log(basket);
        axios
            .post("/api/getLocations", basket)
            .then((res) => {
                if (res.data) {
                    displayBasket(res, "navigative");
                    res.data.map((data) => {
                        map.Route.add({
                            lon: data.longitude,
                            lat: data.latitude,
                        });
                    });
                }
                // console.log(res.data);
            })
            .catch((err) => {
                console.error("Error fetching data:", err);
            });

        map.Route.search();
    }
    initializeMap(lat, lon);
    locationNear();
}

// window.addEventListener("DOMContentLoaded", (e) => {
//     e.preventDefault();
//     let basket = JSON.parse(localStorage.getItem("basket")) || [];
//     axios.post("/api/getNearLocations", basket).then((res) => {
//         if (res.data.length != 0) {
//             const locations = document.querySelector(".locations-near");
//             locations.innerHTML = "";
//             for (let i = 0; i < res.data.length; i++) {
//                 const data = res.data[i];
//                 // console.log(data);
//                 let images = data.Images.split(", ");
//                 // let popup = new longdo.Popup(
//                 //     { lon: data.longitude, lat: data.latitude },
//                 //     {
//                 //         title: "Popup",
//                 //         detail: data.location_name,
//                 //     }
//                 // );
//                 // let map = new longdo.Map({
//                 //     placeholder: document.getElementById("map"),
//                 // });
//                 // map.Overlays.add(popup);
//                 let html;
//                 html = `
//                     <div class="card">
//                         <div class="img"  style="flex: 0 0 30%; width: 30%">
//                             <img src="/storage/images/${images[0]}" alt="${data.location_name}">
//                         </div>
//                         <div class="txt" style="flex: 0 0 70%; width: 70%">
//                             <h3>
//                                 <a href="/province/${data.province_id}/${data.location_id}">
//                                     ${data.location_name}
//                                 </a>
//                             </h3>
//                             <button class='btn-secondary mt-1'>เพิ่ม</button>
//                         </div>
//                     </div>
//                     <hr/>
//                     `;

//                 locations.insertAdjacentHTML("beforeend", html);
//             }
//         } else {
//         }
//     });
// });

function locationNear() {
    let basket = JSON.parse(localStorage.getItem("basket")) || [];
    axios.post("/api/getNearLocations", basket).then((res) => {
        if (res.data.length != 0) {
            const locations = document.querySelector(".locations-near");
            locations.innerHTML = "";
            for (let i = 0; i < res.data.length; i++) {
                const data = res.data[i];
                let images = data.Images.split(", ");
                let markHtml = `
                <div class="map_mark">
                    <img src="/storage/images/${images[0]}" alt="${data.location_name}" />
                </div>`;
                let popupHtml = `
                <div class="map_popup">
                    <div class="image">
                        <img src="/storage/images/${images[0]}" alt="${data.location_name}" />
                    </div>
                    <div class="details">
                        <h4>${data.location_name}</h4>
                        <div class="row align-center">
                            <span class="material-icons">
                                place
                            </span>
                            <p>
                                ${data.address}
                            </p>
                        </div>
                        <button class="btn-secondary mt-1"  
                        onclick="addPlan(${data.location_id},event)">เพิ่ม</button>
                    </div>
                </div>
                `;
                var marker = new longdo.Marker(
                    { lon: data.longitude, lat: data.latitude },
                    {
                        icon: {
                            html: markHtml,
                            offset: { x: 18, y: 21 },
                        },
                        detail: popupHtml,
                    }
                );
                map.Overlays.add(marker);

                let html;
                html = `
                    <div class="card">
                        <div class="img"  style="flex: 0 0 30%; width: 30%">
                            <img src="/storage/images/${images[0]}" alt="${data.location_name}">
                        </div>
                        <div class="txt" style="flex: 0 0 70%; width: 70%">
                            <h3>
                                <a href="/province/${data.province_id}/${data.location_id}">
                                    ${data.location_name}
                                </a>
                            </h3>
                            <div class="row align-center">
                                <span class="material-icons">
                                    place
                                </span>
                                <p>
                                    ${data.address}
                                </p>
                            </div>
                            <button class='btn-secondary mt-1'  
                            onclick="addPlan(${data.location_id},event)">เพิ่ม</button>
                        </div>
                    </div>
                    <hr/>
                    `;

                locations.insertAdjacentHTML("beforeend", html);
            }
        } else {
        }
    });
}

window.addEventListener("DOMContentLoaded", (e) => {
    e.preventDefault();
    const suggest_wrap = document.querySelector(".suggest");
    const basket_wrap = document.querySelector(".basket-wrap");
    const basket = document.querySelector(".basket");
    const navigative_wrap = document.querySelector(".navigative");
    const suggest_toggle = document.querySelector(".suggest .toggle");
    const location_suggest = document.querySelector(".location-suggest");

    suggest_toggle.addEventListener("click", (e) => {
        e.preventDefault();
        [basket, basket_wrap, suggest_wrap, navigative_wrap].forEach(
            (element) => element.classList.toggle("active")
        );
        suggest_toggle.classList.toggle("icon_active");
        location_suggest.classList.toggle("display_active");
    });
});
