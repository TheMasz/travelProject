window.addEventListener("DOMContentLoaded", () => {
    const btns_pref = document.querySelectorAll(".btns-pref button");
    let prefsId = [];
    btns_pref.forEach((btn) => {
        btn.addEventListener("click", (e) => {
            e.preventDefault();
            btn.classList.toggle("active");
            const prefId = btn.getAttribute("data-prefId");

            const index = prefsId.indexOf(prefId);
            if (index !== -1) {
                prefsId.splice(index, 1);
            } else {
                prefsId.push(prefId);
            }

            console.log(prefsId);
        });
    });

    const clearBtn = document.querySelector(".clear-btn");
    const confirmBtn = document.querySelector(".confirm-btn");

    clearBtn.addEventListener("click", () => {
        prefsId = [];
        btns_pref.forEach((btn) => {
            btn.classList.remove("active");
        });
        console.log(prefsId);
    });

    confirmBtn.addEventListener("click", (e) => {
        e.preventDefault();
        const path = window.location.pathname;
        const parts = path.split("/");
        const provinceId = parts[parts.length - 1];
        prefsId.sort((a, b) => a - b);
        axios
            .post(`/api/${provinceId}/filter`, {
                prefsId: prefsId,
            })
            .then((response) => {
                document.open();
                document.write(response.data);
                document.close();
            })
            .catch((error) => {
                console.error(error);
            });
    });
});
