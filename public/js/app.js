window.addEventListener("DOMContentLoaded", () => {
    setTimeout(function () {
        document.getElementById("loading-screen").style.display = "none";
    }, 1500);
    let percent = 0;
    const interval = setInterval(function () {
        percent += Math.floor(Math.random() * 20);
        document.getElementById("progress-bar").style.width = percent + "%";
        if (percent >= 100) {
            clearInterval(interval);
        }
    }, 200);
    updateCartLength();
});

function buttonAnimate(btn) {
    btn.classList.add("clicked");
    setTimeout(() => {
        btn.classList.remove("clicked");
    }, 300);
}

function addPlan(location_id, e) {
    e.preventDefault();
    const clickedButton = e.target;
    buttonAnimate(clickedButton);
    let basket = JSON.parse(localStorage.getItem("basket")) || [];

    if (!basket.includes(location_id)) {
        basket.push(location_id);
        localStorage.setItem("basket", JSON.stringify(basket));
        updateCartLength();
        if (window.location.pathname === "/plans/navigative") {
            location.reload();
        }
    }
}

function clearAllPlanInBasket(e) {
    e.preventDefault();
    const clickedButton = e.target;
    buttonAnimate(clickedButton);
    localStorage.removeItem("basket");
    const locations = document.querySelector("#locations");
    locations.innerHTML = ``;
    let html = `
        <div class='back-home'>
            <span class="material-icons" style='color:lightgray; font-size:64px; margin-bottom:5px'>
                luggage
            </span>
            <p class='mb-2' style='color:lightgray; text-align:center; width:75%'>ยังไม่มีสถานที่ท่องเที่ยวในการเดินของคุณ 
            กรุณาเริ่มด้วยการเลือกสถานที่ที่คุณต้องการเพิ่มเข้าในแผนการท่องเที่ยวของคุณ คุณสามารถเริ่มการค้นหาและเพิ่มสถานที่ท่องเที่ยวได้ที่หน้าหลักของเรา</p>
            <a href='/' class='btn-primary mb-05'>ไปยังหน้าแรก</a>
            <a href='/myplans' class='btn-primary'>ไปยังแผนท่องเที่ยวของฉัน</a>
        </div>
    `;
    locations.insertAdjacentHTML("beforeend", html);
    updateCartLength();
}

function removePlanInBasket(location_id) {
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
                    if (getBasketLength() == 0 || null) {
                        const locations = document.querySelector("#locations");
                        locations.innerHTML = ``;
                        let html = `
                            <div class='back-home'>
                                <span class="material-icons" style='color:lightgray; font-size:64px; margin-bottom:5px'>
                                    luggage
                                </span>
                                <p class='mb-2' style='color:lightgray; text-align:center; width:75%'>ยังไม่มีสถานที่ท่องเที่ยวในการเดินของคุณ 
                                กรุณาเริ่มด้วยการเลือกสถานที่ที่คุณต้องการเพิ่มเข้าในแผนการท่องเที่ยวของคุณ คุณสามารถเริ่มการค้นหาและเพิ่มสถานที่ท่องเที่ยวได้ที่หน้าหลักของเรา</p>
                                <a href='/' class='btn-primary mb-05'>ไปยังหน้าแรก</a>
                                <a href='/myplans' class='btn-primary'>ไปยังแผนท่องเที่ยวของฉัน</a>
                            </div>
                        `;
                        locations.insertAdjacentHTML("beforeend", html);
                    } else {
                        displayBasket(res, "button");
                    }
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
        document.getElementById("location_id").value = getLocationsId();
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
        document.getElementById("location_id").value = getLocationsId();
    }
}

function usePlan(locationIds, obj_class) {
    let basket = JSON.parse(localStorage.getItem("basket")) || [];

    if (basket === null) {
        basket = [];
    }

    if (basket.length === 0) {
        console.log("Basket is empty");

        let locationIdsArray = locationIds.split(",").map(Number);

        basket = basket.concat(locationIdsArray);

        localStorage.setItem("basket", JSON.stringify(basket));
        updateCartLength();
        window.location.href = "/basket";
    } else {
        const obj = document.querySelector(obj_class);
        showAlert(
            "warning",
            "กระเป๋าเดินทางไม่ว่าง โปรดเคลียร์แล้วลองใหม่อีกครั้ง!",
            obj
        );
    }
}

function showAlert(type, message, object) {
    if (object != null && object) {
        const alertDiv = document.createElement("div");
        alertDiv.className = `message message-${type}`;
        alertDiv.textContent = message;
        object.insertAdjacentElement("beforebegin", alertDiv);

        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    } else {
        console.error("Element with class not found.");
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

function getLocationsId() {
    let basket = JSON.parse(localStorage.getItem("basket")) || [];
    if (basket) {
        return basket;
    }
}

let current = {};

function getCurrectGeo(e) {
    e.preventDefault();
    const clickedButton = e.target;
    buttonAnimate(clickedButton);
    if ("geolocation" in navigator) {
        navigator.geolocation.getCurrentPosition(
            function (position) {
                let lat = position.coords.latitude;
                let lon = position.coords.longitude;
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

function calcDistance(e) {
    e.preventDefault();
    const clickedButton = e.target;
    buttonAnimate(clickedButton);
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
                    <p>${maxLength(data.detail, 100)}</p>
                </div>
            </div>
            `;
        } else if (btn == "navigative") {
            html = `
            <div class="card">
       
                <div class='order' style='flex: 0 0 10%'; width:10%;'>
                    ${i + 1}
                </div>
                <div class="img" style='flex: 0 0 25%'; width: 25%;>
                    <img src="/storage/images/${images[0]}" alt="${
                data.location_name
            }">
                </div>
                <div class="txt" style='flex: 0 0 60%'; width:60%;>
                    <h3 style="font-size:14px;">
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
        } else {
            html = `
            <div class="card">
                <div class="btns">
                    <button onClick='prevPlan(${
                        data.location_id
                    })' class='btn_prev' >
                    <span class="material-icons">
                    arrow_drop_up
                    </span>
                    </button>
                    <button onClick='nextPlan(${
                        data.location_id
                    })' class='btn_next' >
                    <span class="material-icons">
                    arrow_drop_down
                    </span>
                    </button>
                </div>
                <div class="img">
                    <img src="/storage/images/${images[0]}" alt="${
                data.location_name
            }">
                </div>
                <div class="txt">
                <h3>
                <a href="/province/${data.province_id}/${data.location_id}">
                    ${data.location_name}
                </a>
            </h3>
                    <p>${maxLength(data.detail, 100)}</p>
                </div>
                <div class='btn_del'>
                    <button onClick='removePlanInBasket(${data.location_id})'>
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

function navigative(e) {
    e.preventDefault();
    const clickedButton = e.target;
    buttonAnimate(clickedButton);
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

function checkOpeningStatus(location_id) {
    fetch(`/api/checkOpening/${location_id}`)
        .then((response) => response.json())
        .then((data) => {
            const statusElement = document.getElementById(
                `opening-status-${location_id}`
            );
            if (data.status == "opend") {
                statusElement.textContent = "เปิดทำการ";
                statusElement.style.backgroundColor = "#28A745";
            } else {
                statusElement.textContent = "ปิดทำการ";
                statusElement.style.backgroundColor = "#DC3545";
            }
        })
        .catch((error) => console.error("Error:", error));
}
