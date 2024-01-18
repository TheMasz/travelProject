document.getElementById('location_id').value = getLocationsId();
document.addEventListener('DOMContentLoaded', function() {
    let basket = JSON.parse(localStorage.getItem("basket")) || [];
    if (basket.length == 0) {
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
});

document.getElementById('planForm').addEventListener('submit', function(e) {
    e.preventDefault(); 
    const formData = new FormData(this);

    axios.post('/api/addPlan', formData, {
            headers: {
                'Content-Type': 'multipart/form-data', 
            },
        })
        .then(response => {
            console.log('Request successful:', response.data);
            if (response.data.success) {
                localStorage.removeItem('basket');
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
});