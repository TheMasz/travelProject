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

function removePlanInNavigative(location_id) {
    let basket = JSON.parse(localStorage.getItem("basket")) || [];
    let index = basket.indexOf(location_id);

    if (index !== -1) {
        basket.splice(index, 1);
        localStorage.setItem("basket", JSON.stringify(basket));
        updateCartLength();
        location.reload();
    }
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

var current = {};

async function getCurrectGeo(e) {
    e.preventDefault();
    const clickedButton = e.target;
    buttonAnimate(clickedButton);

    try {
        if ("geolocation" in navigator) {
            const position = await getCurrentPosition();
            updateLocation(position);
        } else {
            throw new Error("Geolocation is not supported.");
        }
    } catch (error) {
        handleGeolocationError(error);
    }
}

function getCurrentPosition() {
    return new Promise((resolve, reject) => {
        navigator.geolocation.getCurrentPosition(resolve, reject);
    });
}

function updateLocation(position) {
    const { latitude: lat, longitude: lon } = position.coords;
    console.log("Received coordinates:", lat, lon);
    current = { lat, lon };
    const txt = document.querySelector("#current-geo");
    txt.innerHTML = `lat: ${lat}<br/>lon: ${lon}`;
}

function handleGeolocationError(error) {
    console.error("Geolocation error:", error);
    const wrap = document.querySelector(".location-mark");
    const div = document.createElement("div");
    div.classList.add("message", "message-warning");
    div.textContent = "ไม่สามารถระบุตำแหน่งได้! ลองใหม่อีกครั้ง";
    wrap.insertBefore(div, wrap.firstChild);
    setTimeout(() => {
        wrap.removeChild(div);
    }, 2000);
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
                    <img src="/storage/images/locations/${images[0]}" alt="${
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
                    <p>${maxLength(data.detail, 100, 1)}</p>
                </div>
            </div>
            `;
        } else if (btn == "navigative") {
            html = `
            <div class="card">
                <div class='order' style='flex: 0 0 10%'; width:10%;'>
                    ${i + 1}
                </div>
                <div class="img" style='flex: 0 0 20%; width: 20%;'>
                    <img src="/storage/images/locations/${images[0]}" alt="${
                data.location_name
            }">
                </div>
                <div class="txt" style='flex: 0 0 60%; width:60%;'>
                    <h3 style="font-size:14px;">
                        <a href="/province/${data.province_id}/${
                data.location_id
            }">
                            ${data.location_name}
                        </a>
                    </h3>
                </div>
                <div class='btn_del' style='flex: 0 0 10%'; width:10%;'>
                    <button onClick='removePlanInNavigative(${
                        data.location_id
                    })' style='margin-right: 10px;'>
                        <span class="material-icons">
                            delete
                        </span>
                    </button>
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
                    <img src="/storage/images/locations/${images[0]}" alt="${
                data.location_name
            }">
                </div>
                <div class="txt">
                <h3>
                <a href="/province/${data.province_id}/${data.location_id}">
                    ${data.location_name}
                </a>
            </h3>
                    <p>${maxLength(data.detail, 100, 1)}</p>
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

function maxLength(inputString, maxLength, option) {
    if (inputString.length > maxLength) {
        if (option == 1) {
            return inputString.slice(0, maxLength) + "...";
        } else if (option == 2) {
            return inputString.slice(0, maxLength);
        }
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

// async function getOpenCloseDayTime(location_id) {
//     try {
//         const response = await fetch(`/api/checkOpening/${location_id}`);
//         const data = await response.json();
//         const opentime = data.open;
//         const closetime = data.close;
//         const openDay = data.openDay;
//         return { opentime, closetime, openDay };
//     } catch (error) {
//         console.error("Error:", error);
//         throw error;
//     }
//     //fetch(`/api/checkOpening/${location_id}`);
//     // .then((response) => response.json())
//     // .then((data) => {

//     //     const statusElement = document.getElementById(
//     //         `opening-status-${location_id}`
//     //     );
//     //     if (data.status == "opened") {
//     //         statusElement.textContent = "เปิดทำการ";
//     //         statusElement.style.backgroundColor = "#28A745";
//     //     } else {
//     //         statusElement.textContent = "ปิดทำการ";
//     //         statusElement.style.backgroundColor = "#DC3545";
//     //     }
//     // })
//     // .catch((error) => console.error("Error:", error));
// }

function checkOpeningStatus(opentime, closetime, openDay, location_id) {
    if (!opentime || !closetime) {
        throw new Error("Invalid opening or closing time.");
    }

    const currentDateTime = new Date();
    const currentHour = currentDateTime.getHours();
    const currentMinute = currentDateTime.getMinutes();
    const currentDay = currentDateTime.getDay();
    const openHour = parseInt(opentime.split(":")[0]);
    const openMinute = parseInt(opentime.split(":")[1]);
    const closeHour = parseInt(closetime.split(":")[0]);
    const closeMinute = parseInt(closetime.split(":")[1]);

    const isOpen =
        openDay.includes(currentDay + 1) &&
        (currentHour > openHour ||
            (currentHour === openHour && currentMinute >= openMinute)) &&
        (currentHour < closeHour ||
            (currentHour === closeHour && currentMinute < closeMinute));

    if (isOpen) {
        console.log("Location is OPEN");
        updateStatusUI(location_id, "opened");
    } else {
        console.log("Location is CLOSED");
        updateStatusUI(location_id, "closed");
    }
}

function updateStatusUI(location_id, status) {
    const statusElement = document.getElementById(
        `opening-status-${location_id}`
    );
    const isOpened = status === "opened";

    statusElement.textContent = isOpened ? "เปิดทำการ" : "ปิดทำการ";
    statusElement.style.backgroundColor = isOpened ? "#28A745" : "#DC3545";
}

function delAction(review_id) {
    const modal_del = document.querySelector(".modal-type-alert");
    const modalContent_del = document.querySelector(
        ".modal_content-type-alert"
    );
    const cancelBtn = document.querySelector(".cancel_btn-type-alert");
    const confirmBtn = document.querySelector(".confirm_btn-type-alert");
    const txtHead = document.querySelector(".modal-type-alert .txt_head");

    txtHead.innerHTML = `คุณต้องการลบรีวิวนี้หรือไม่?`;
    modal_del.style.display = "block";

    modalContent_del.addEventListener("click", (e) => {
        e.preventDefault();
        if (e.target === modalContent_del || e.target === cancelBtn) {
            modal_del.style.display = "none";
        }
    });

    confirmBtn.addEventListener("click", (e) => {
        e.preventDefault();

        // const data = {
        //     review_id: review_id,
        // };
        // axios
        //     .delete("/api/removeReview", {
        //         data,
        //     })
        //     .then((response) => {
        //         if (response.data.success) {
        //             location.reload();
        //         }
        //     })
        //     .catch((error) => {
        //         console.error("Error removing plan:", error);
        //     });
        axios
            .get(`/api/removeReview/${review_id}`)
            .then((response) => {
                if (response.data.success) {
                    location.reload();
                }
            })
            .catch((error) => {
                console.error("Error removing plan:", error);
            });
        modal_del.style.display = "none";
    });
}

function generateStars(rating) {
    let starsHTML = "";
    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            starsHTML += '<span class="material-icons">star</span>';
        } else {
            starsHTML += '<span class="material-icons">star_border</span>';
        }
    }
    return starsHTML;
}

function compareTime(reviewTime) {
    const createdAt = new Date(reviewTime);
    const currentDate = new Date();
    const timeDifference = currentDate - createdAt;
    const dayDifference = Math.floor(timeDifference / (1000 * 60 * 60 * 24));
    const monthDifference = Math.floor(dayDifference / 30);

    if (monthDifference > 0) {
        if (monthDifference === 1) {
            return "1 เดือนที่แล้ว";
        } else {
            return `${monthDifference} เดือนที่แล้ว`;
        }
    } else {
        if (dayDifference === 1) {
            return "1 วันที่แล้ว";
        } else {
            return `${dayDifference} วันที่แล้ว`;
        }
    }
}
