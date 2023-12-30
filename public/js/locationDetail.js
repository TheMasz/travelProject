const member_session = document.getElementById("member_session").value;
const main_img = document.querySelector(".main-image .image");
const imgs = document.querySelectorAll(".image-box .image");
main_img.style.background = `url('/storage/images/${imgs[0].getAttribute(
    "data-img"
)}')`;
imgs.forEach((img) => {
    img.addEventListener("click", (e) => {
        e.preventDefault();
        let attr = img.getAttribute("data-img");
        main_img.style.background = `url('/storage/images/${attr}')`;
    });
    img.addEventListener("mouseover", (e) => {
        e.preventDefault();
        let attr = img.getAttribute("data-img");
        main_img.style.background = `url('/storage/images/${attr}')`;
    });
});

const btn_review = document.querySelector(".btn_review");
const modal = document.querySelector(".modal-reviews");
const modal_content = document.querySelector(
    ".modal-reviews .modal_content-reviews"
);
const cancel_btn = document.querySelector(".cancel_btn-reviews");

function closeModal() {
    modal.style.display = "none";
}

modal_content.addEventListener("click", (e) => {
    if (e.target === modal_content || e.target === cancel_btn) {
        closeModal();
    }
});

btn_review.addEventListener("click", (e) => {
    e.preventDefault();
    modal.style.display = "block";
});

const allStar = document.querySelectorAll(".rating .star");
const ratingValue = document.querySelector(".rating input");

allStar.forEach((item, index) => {
    item.style.setProperty("--i", index);

    item.addEventListener("click", function () {
        const value = parseInt(item.getAttribute("data-value"));
        ratingValue.value = value;

        allStar.forEach((star, i) => {
            if (i <= index) {
                star.innerHTML = "star";
                star.classList.add("active");
            } else {
                star.innerHTML = "star_border";
                star.classList.remove("active");
            }
        });
    });
});

document.addEventListener("DOMContentLoaded", () => {
    const formReviews = document.querySelector(".form_reviews");

    formReviews.addEventListener("submit", (e) => {
        e.preventDefault();
        const formData = new FormData();
        const token = e.target._token.value;
        const rating = e.target.rating.value;
        const review_txt = e.target.review_txt.value;
        const location_id = e.target.location_id.value;
        formData.append("_token", token);
        formData.append("rating", rating);
        formData.append("review_txt", review_txt);
        formData.append("location_id", location_id);
        const obj = document.querySelector(".content_wrap h3");
        if (!rating) {
            showAlert(
                "warning",
                "กรุณาให้คะแนนสถานที่นี้! ลองใหม่อีกครั้ง",
                obj
            );
        } else if (!review_txt) {
            showAlert("warning", "กรุณารีวิวสถานที่นี้! ลองใหม่อีกครั้ง", obj);
        } else {
            axios
                .post("/api/postReview", formData)
                .then((response) => {
                    if (response.data.success) {
                        showAlert("success", "ขอบคุณสำหรับความคิดเห็น", obj);
                        fetch(`/api/getReviews/${location_id}`)
                            .then((response) => response.json())
                            .then((data) => {
                                const reviewsContainer =
                                    document.querySelector(".reviews");

                                reviewsContainer.innerHTML = "";

                                data.forEach((review) => {
                                    displayReviews(review);
                                });
                            })
                            .catch((err) => {
                                console.error(err);
                            });
                        setTimeout(() => {
                            closeModal();
                            resetFormElements();
                        }, 1000);
                    }
                })
                .catch((error) => {
                    console.error("Error post review:", error);
                });
        }
    });

    function resetFormElements() {
        const formElement = document.querySelector(".form_reviews");
        const stars = formElement.querySelectorAll(".star");
        stars.forEach((star) => {
            star.textContent = "star_border";
        });

        const textareaElement = formElement.querySelector("textarea");
        textareaElement.value = "";
    }
});

function likeActions(review_id, action) {
    axios
        .post("/api/likeActions", { review_id, action })
        .then((response) => {
            const like_section = document.querySelector(
                `.like_section${review_id}`
            );
            if (response.data.liked) {
                like_section.innerHTML = `
            <button type="button" class="btn_like" onclick="likeActions(${review_id}, 'unlike')">
                <span class="material-icons" style="color: red;">favorite</span>
                <p></p>
            </button>
            <p>${response.data.like_count} ถูกใจ</p>
            `;
            } else if (response.data.unliked) {
                like_section.innerHTML = `
            <button type="button" class="btn_like" onclick="likeActions(${review_id}, 'like')">
                <span class="material-icons" style="color: red;">favorite_border</span>
                <p></p>
            </button>
            <p>${response.data.like_count} ถูกใจ</p>
            `;
            }
        })
        .catch((error) => {
            console.error(error);
        });
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

        const data = {
            review_id: review_id,
        };
        axios
            .delete("/api/removeReview", { data })
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

const loadMoreButton = document.querySelector("#loadMoreButton");
let offset = 3;
loadMoreButton.addEventListener("click", (e) => {
    e.preventDefault();
    const location_id = loadMoreButton.getAttribute("data-locationId");
    fetch(`/api/loadMoreReviews/${location_id}/${offset}`)
        .then((response) => response.json())
        .then((data) => {
            const reviews = Object.values(data);

            if (reviews.length === 0 || Object.keys(data).length === 0) {
                loadMoreButton.disabled = true;
                loadMoreButton.classList.add('disable');
            } else {
                loadMoreButton.disabled = false;
                loadMoreButton.classList.remove('disable');
            }
            reviews.forEach((review) => {
                displayReviews(review);
            });
            offset += 5;
        })
        .catch((err) => {
            console.error(err);
        });
});

function displayReviews(review) {
    const reviewsContainer = document.querySelector(".reviews");
    const reviewElement = document.createElement("div");
    reviewElement.classList.add("review");

    const user = document.createElement("div");
    user.classList.add("user");
    user.innerHTML = `
                            <h4>${review.username}</h4>
                            <div class="star">
                                ${generateStars(review.rating)}
                            </div>
                            <p class="date">${review.created_at}</p>
                        `;

    const txt = document.createElement("div");
    txt.classList.add("txt");
    txt.textContent = review.review;

    const like = document.createElement("div");
    like.classList.add("like_section");
    like.classList.add(`like_section${review.review_id}`);
    if (review.liked_by_current_member > 0) {
        like.innerHTML = `
        <button type="button" class="btn_like" onclick="likeActions(${review.review_id}, 'unlike')">
            <span class="material-icons" style="color: red;">favorite</span>
            <p></p>
        </button>
        <p>${review.like_count} ถูกใจ</p>
`;
    } else {
        like.innerHTML = `
        <button type="button" class="btn_like" onclick="likeActions(${review.review_id}, 'like')">
            <span class="material-icons" style="color: red;">favorite_border</span>
            <p></p>
        </button>
        <p>${review.like_count} ถูกใจ</p>
`;
    }

    reviewElement.appendChild(user);
    reviewElement.appendChild(txt);
    reviewElement.appendChild(like);
    if (member_session == review.member_id) {
        const more = document.createElement("div");
        more.classList.add("more");
        more.innerHTML = `
   
            <button class="del-review"
            onclick="delAction(${review.review_id})">
                <span class="material-icons">delete</span>
            </button>
  
        `;
        reviewElement.appendChild(more);
    }

    reviewsContainer.appendChild(reviewElement);
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
