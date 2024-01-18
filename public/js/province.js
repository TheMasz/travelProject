window.addEventListener("DOMContentLoaded", () => {
    const btns_pref = document.querySelectorAll(".btns-pref button");

    let prefsId =
        JSON.parse(sessionStorage.getItem("selectedPreferences")) || [];

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

            // Store updated preferences in session storage
            sessionStorage.setItem(
                "selectedPreferences",
                JSON.stringify(prefsId)
            );

            console.log(prefsId);
        });
    });

    const clearBtn = document.querySelector(".clear-btn");
    const confirmBtn = document.querySelector(".confirm-btn");

    clearBtn.addEventListener("click", async () => {
        prefsId = [];
        btns_pref.forEach((btn) => btn.classList.remove("active"));
        sessionStorage.removeItem("selectedPreferences");
        await fetch("/api/clearSessionPref", {
            method: "GET",
        }).catch((error) => console.error(error));
        const provinceId = window.location.pathname.split("/").pop();
        try {
            const response = await axios.post(`/api/${provinceId}/filter`, {
                prefsId: prefsId.sort((a, b) => a - b),
            });
            document.open();
            document.write(response.data);
            document.close();
            history.replaceState({}, document.title, window.location.pathname);
        } catch (error) {
            console.error(error);
        }
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
