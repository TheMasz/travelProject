const modal = document.querySelector(".modal-type-alert");
const modalContent = document.querySelector(".modal_content-type-alert");
const cancelBtn = document.querySelector(".cancel_btn-type-alert");
const confirmBtn = document.querySelector(".confirm_btn-type-alert");
const txtHead = document.querySelector(".modal-type-alert .txt_head");

function closeModal() {
    modal.style.display = "none";
}

modalContent.addEventListener("click", (e) => {
    e.preventDefault();
    if (e.target === modalContent || e.target === cancelBtn) {
        closeModal();
    }
});

confirmBtn.addEventListener("click", (e) => {
    e.preventDefault();
    let planName = confirmBtn.getAttribute("data-planName");
    const data = {
        plan_name: planName,
    };

    axios
        .delete("/api/removePlan", {
            data,
        })
        .then((response) => {
            console.log("Request successful:", response.data);
            if (response.data.success) {
                location.reload();
            }
        })
        .catch((error) => {
            console.error("Error removing plan:", error);
        });

    closeModal();
});

function removePlan(planName) {
    confirmBtn.setAttribute("data-planName", planName);
    txtHead.innerHTML = `คุณต้องการลบแผนท่องเที่ยว ${planName} หรือไม่?`;
    modal.style.display = "block";
}
