window.addEventListener("DOMContentLoaded", (e) => {
    const btn_profile = document.querySelector(".btn_profile");
    const modal = document.querySelector(".modal-profile");
    const modal_content = document.querySelector(
        ".modal-profile .modal_content-profile"
    );
    const cancel_btn = document.querySelector(".cancel_btn");

    const overlay = document.getElementById("overlay");
    const profileContent = document.getElementById("profileContent");
    const preferencesContent = document.getElementById("preferencesContent");
    const editProfileBtn = document.getElementById("editProfileBtn");
    const editPreferencesBtn = document.getElementById("editPreferencesBtn");


    function closeModal() {
        modal.style.display = "none";
    }
    modal_content.addEventListener("click", (e) => {
        if (e.target === modal_content || e.target === cancel_btn) {
            closeModal();
            overlay.classList.remove("show");
            profileContent.style.display = "none";
            preferencesContent.style.display = "none";
        }
    });
    btn_profile.addEventListener("click", (e) => {
        modal.style.display = "block";
    });

    function showProfileContent() {
        overlay.classList.add("show");
        profileContent.style.display = "block";
        preferencesContent.style.display = "none";
        editProfileBtn.classList.add("btn-active");
        editPreferencesBtn.classList.remove("btn-active");
    }

    function showPreferencesContent() {
        overlay.classList.add("show");
        profileContent.style.display = "none";
        preferencesContent.style.display = "block";
        editProfileBtn.classList.remove("btn-active");
        editPreferencesBtn.classList.add("btn-active");
    }

    editProfileBtn.addEventListener("click", showProfileContent);
    editPreferencesBtn.addEventListener("click", showPreferencesContent);

    document
        .getElementById("formMember")
        .addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            axios
                .post("/api/editProfile", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    if (response.data.warning) {
                        const obj = document.querySelector("#formMember");
                        showAlert("warning", response.data.message, obj);
                    } else if (response.data.success) {
                        const obj = document.querySelector("#formMember");
                        showAlert("success", response.data.message, obj);
                        location.reload();
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                });
        });

    document
        .getElementById("formPref")
        .addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            axios
                .post("/api/editPref", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    const obj = document.querySelector("#formPref");
                    showAlert("success", response.data.message, obj);
                    location.reload();
                })
                .catch((error) => {
                    console.error("Error:", error);
                });
        });
});

let offset = 6;
let selectedValue = "desc";
const loadMoreButton = document.querySelector("#loadMoreButton");
document
    .getElementById("filterDropdown")
    .addEventListener("change", function (e) {
        e.preventDefault();
        selectedValue = this.value;
        offset = 6;
        fetch(`/api/getMyReviews/${selectedValue}`)
            .then((response) => response.json())
            .then((data) => {
                loadMoreButton.disabled = false;
                loadMoreButton.classList.remove("disable");
                const reviewsContainer = document.querySelector(".reviews");
                reviewsContainer.innerHTML = "";
                const reviews = Object.values(data);
                reviews.forEach((review) => {
                    displayReviews(review);
                });
            })
            .catch((err) => {
                console.error(err);
            });
    });

loadMoreButton.addEventListener("click", (e) => {
    e.preventDefault();
    fetch(`/api/loadMoreMyReviews/${offset}/${selectedValue}`)
        .then((response) => response.json())
        .then((data) => {
            const reviews = Object.values(data);

            if (reviews.length === 0 || Object.keys(data).length === 0) {
                loadMoreButton.disabled = true;
                loadMoreButton.classList.add("disable");
            } else {
                loadMoreButton.disabled = false;
                loadMoreButton.classList.remove("disable");
            }
            reviews.forEach((review) => {
                displayReviews(review);
            });
            offset += 6;
        })
        .catch((err) => {
            console.error(err);
        });
});

function displayReviews(review) {
    const reviewsContainer = document.querySelector(".reviews");
    const reviewCard = document.createElement("div");
    reviewCard.classList.add("review_card");

    const row = document.createElement("div");
    row.classList.add("row", "flex-between");

    const location = document.createElement("div");
    location.classList.add("location");

    const avatar = document.createElement("div");
    avatar.classList.add("avatar", "avatar-md");
    if (review.member_img) {
        const img = document.createElement("img");
        img.src = `/storage/images/members/${review.member_id}/${review.member_img}`;
        img.alt = "Profile";
        avatar.appendChild(img);
    } else {
        const h4 = document.createElement("h4");
        h4.textContent = maxLength(review.username, 1);
        avatar.appendChild(h4);
    }
    location.appendChild(avatar);

    const desc = document.createElement("div");
    desc.classList.add("desc");

    const a = document.createElement("a");
    a.href = `/province/${review.province_id}/${review.location_id}`;
    a.innerHTML = `
        <h4>${review.location_name}</h4>
    `;
    desc.appendChild(a);

    const datetime = document.createElement("p");
    datetime.textContent = compareTime(review.created_at);
    desc.appendChild(datetime);

    const createdAt = document.createElement("p");
    createdAt.textContent = review.created_at;
    desc.appendChild(createdAt);

    location.appendChild(desc);
    row.appendChild(location);

    const star = document.createElement("div");
    star.classList.add("star");
    star.innerHTML = generateStars(review.rating);
    row.appendChild(star);
    reviewCard.appendChild(row);

    const reviewContent = document.createElement("div");
    reviewContent.classList.add("review");
    const reviewText = document.createElement("p");
    reviewText.textContent = review.review;
    reviewContent.appendChild(reviewText);
    reviewCard.appendChild(reviewContent);

    const deleteButton = document.createElement("button");
    deleteButton.classList.add("del-review");
    deleteButton.innerHTML = '<span class="material-icons">delete</span>';
    deleteButton.addEventListener("click", () => delAction(review.review_id));
    reviewCard.appendChild(deleteButton);

    reviewsContainer.appendChild(reviewCard);
}
