window.addEventListener("DOMContentLoaded", () => {
    updateCartLength();
});

function addPlan(location_id) {
    let basket = JSON.parse(localStorage.getItem("basket")) || [];

    if (!basket.includes(location_id)) {
        basket.push(location_id);
        localStorage.setItem("basket", JSON.stringify(basket));
        updateCartLength();
    }
}
function removePlan(location_id) {
    let basket = JSON.parse(localStorage.getItem("basket")) || [];
    let index = basket.indexOf(location_id);

    if (index !== -1) {
        basket.splice(index, 1);
        localStorage.setItem("basket", JSON.stringify(basket));
        updateCartLength();
        axios
            .post("/api/getLocations", basket)
            .then((res) => {
                if (res.data) {
                    displayBasket(res, "button");
                }
            })
            .catch((err) => {
                throw err;
            });
    }
}
function findIndexById(location_id, basket) {
    let index;
    for (let i = 0; i < basket.length; i++) {
        if (basket[i] == location_id) {
            index = i;
            return index;
        } else {
            index = 0;
        }
    }
    return index;
}

function nextPlan(location_id) {
    let basket = JSON.parse(localStorage.getItem("basket")) || [];
    let index = findIndexById(location_id, basket);

    if (index !== -1) {
        if (index < basket.length - 1) {
            [basket[index], basket[index + 1]] = [
                basket[index + 1],
                basket[index],
            ];
        } else if (index === basket.length - 1) {
            basket.unshift(basket.pop());
        }

        localStorage.setItem("basket", JSON.stringify(basket));

        axios
            .post("/api/getLocations", basket)
            .then((res) => {
                if (res.data) {
                    displayBasket(res, "button");
                }
            })
            .catch((err) => {
                throw err;
            });
    }
}

function prevPlan(location_id) {
    let basket = JSON.parse(localStorage.getItem("basket")) || [];
    let index = findIndexById(location_id, basket);

    if (index !== -1) {
        if (index > 0) {
            [basket[index], basket[index - 1]] = [
                basket[index - 1],
                basket[index],
            ];
        } else if (index === 0) {
            basket.push(basket.shift());
        }

        localStorage.setItem("basket", JSON.stringify(basket));

        axios
            .post("/api/getLocations", basket)
            .then((res) => {
                if (res.data) {
                    displayBasket(res, "button");
                }
            })
            .catch((err) => {
                throw err;
            });
    }
}

function setBasketDistance(basket) {
    localStorage.setItem("basket", JSON.stringify(basket));
    updateCartLength();
}
function getBasketLength() {
    let basket = JSON.parse(localStorage.getItem("basket")) || [];
    return basket.length;
}

function updateCartLength() {
    let cartLengthElement = document.getElementById("cartLength");
    cartLengthElement.textContent = getBasketLength();
}

let current = {};

function getCurrectGeo() {
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                // Use the location from the browser's geolocation API
                current = {
                    lat: lat,
                    lon: lon,
                };

                // Perform actions that depend on the location data here
                const txt = document.querySelector("#current-geo");
                txt.innerHTML = "lat: " + lat + "<br/>" + " lon: " + lon;
            },
            function (error) {
                // Handle geolocation error here
                console.error("Geolocation error:", error);
                const wrap = document.querySelector(".location-mark");
                const div = document.createElement("div");
                div.classList.add("message", "message-warning");
                div.textContent = "ไม่สามารถระบุตำแหน่งได้! ลองใหม่อีกครั้ง";
                wrap.insertBefore(div, wrap.firstChild);
                setTimeout(function () {
                    wrap.removeChild(div);
                }, 2000);
            }
        );
    } else {
        // Handle no geolocation support here
        console.error("Geolocation is not supported.");
    }
}

function toRadians(degrees) {
    return degrees * (Math.PI / 180);
}

function calcDistance() {
    if (current && current.lat != undefined && current.lon != undefined) {
        if (getBasketLength() == 0) {
            const wrap = document.querySelector(".location-mark");
            const div = document.createElement("div");
            div.classList.add("message", "message-warning");
            div.textContent =
                "กรุณาเลือกสถานที่ท่องเที่ยวก่อน! ลองใหม่อีกครั้ง";
            wrap.insertBefore(div, wrap.firstChild);
            setTimeout(function () {
                wrap.removeChild(div);
            }, 2000);
        } else {
            let basket = JSON.parse(localStorage.getItem("basket")) || [];
            const cur_lat = toRadians(current.lat);
            const cur_lon = toRadians(current.lon);
            const radius = 6371; // Radius of the Earth in kilometers
            axios
                .post("/api/getLocations", basket)
                .then((res) => {
                    if (res.data) {
                        const distances = [];
                        for (let i = 0; i < res.data.length; i++) {
                            const loc_lat = toRadians(res.data[i].latitude);
                            const loc_lon = toRadians(res.data[i].longitude);

                            const dLat = loc_lat - cur_lat;
                            const dLon = loc_lon - cur_lon;

                            const a =
                                Math.sin(dLat / 2) ** 2 +
                                Math.cos(cur_lat) *
                                    Math.cos(loc_lat) *
                                    Math.sin(dLon / 2) ** 2;
                            const c =
                                2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

                            const distance = radius * c;

                            distances.push({
                                locationId: res.data[i].location_id,
                                distance: distance,
                            });
                        }
                        distances.sort((a, b) => a.distance - b.distance);

                        const locationIds = distances.map(
                            (item) => item.locationId
                        );

                        setBasketDistance(locationIds);

                        axios
                            .post("/api/getLocations", locationIds)
                            .then((res) => {
                                if (res.data) {
                                    displayBasket(res);
                                }
                            })
                            .catch((err) => {
                                throw err;
                            });
                        console.log(distances);
                    }
                })
                .catch((err) => {
                    throw err;
                });
        }
    } else {
        const wrap = document.querySelector(".location-mark");
        const div = document.createElement("div");
        div.classList.add("message", "message-warning");
        div.textContent = "กรุณาระบุตำแหน่งปัจจุบันก่อน! ลองใหม่อีกครั้ง";
        wrap.insertBefore(div, wrap.firstChild);
        setTimeout(function () {
            wrap.removeChild(div);
        }, 2000);
    }
}

function displayBasket(res, btn) {
    const locations = document.querySelector("#locations");
    locations.innerHTML = "";
    for (let i = 0; i < res.data.length; i++) {
        const data = res.data[i];
        // console.log(data);
        let images = data.Images.split(", ");
        let html;
        if (btn == "no button") {
            html = `
            <div class="card">
                <div class="img"  style="flex: 0 0 30%; width: 30%">
                    <img src="/storage/images/${images[0]}" alt="${data.location_name}">
                </div>
                <div class="txt" style="flex: 0 0 70%; width: 70%">
                    <h3>${data.location_name}</h3>
                    <p>${maxLength(data.detail, 100)}</p>
                </div>
            </div>
            `;
        } else {
            html = `
            <div class="card">
                <div class="btns">
                    <button onClick='prevPlan(${
                        data.location_id
                    })'>เลื่อนขึ้น</button>
                    <button onClick='nextPlan(${
                        data.location_id
                    })'>เลื่อนลง</button>
                </div>
                <div class="img">
                    <img src="/storage/images/${images[0]}" alt="${data.location_name}">
                </div>
                <div class="txt">
                    <h3>${data.location_name}</h3>
                    <p>${maxLength(data.detail, 100)}</p>
                </div>
                <div class='btn_del'>
                    <button onClick='removePlan(${data.location_id})'>
                        <span class="material-icons">
                            delete
                        </span>
                    </button>
                </div>
            </div>
            `;
        }
        locations.insertAdjacentHTML("beforeend", html);
    }
}

function maxLength(inputString, maxLength) {
    if (inputString.length > maxLength) {
        return inputString.slice(0, maxLength) + "...";
    } else {
        return inputString;
    }
}

function navigative() {
    let length = getBasketLength();
    if (current && current.lat != undefined && current.lon != undefined) {
        if (length == 0) {
            const wrap = document.querySelector(".location-mark");
            const div = document.createElement("div");
            div.classList.add("message", "message-warning");
            div.textContent =
                "กรุณาเลือกสถานที่ท่องเที่ยวก่อน! ลองใหม่อีกครั้ง";
            wrap.insertBefore(div, wrap.firstChild);
            setTimeout(function () {
                wrap.removeChild(div);
            }, 2000);
        } else {
            const url = `/plans/navigative?lat=${current.lat}&lon=${current.lon}`;
            window.location.href = url;
        }
    } else {
        const wrap = document.querySelector(".location-mark");
        const div = document.createElement("div");
        div.classList.add("message", "message-warning");
        div.textContent = "กรุณาระบุตำแหน่งปัจจุบันก่อน! ลองใหม่อีกครั้ง";
        wrap.insertBefore(div, wrap.firstChild);
        setTimeout(function () {
            wrap.removeChild(div);
        }, 2000);
    }
}
