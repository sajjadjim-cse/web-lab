document.getElementById("createEventBtn").onclick = function () {
    document.getElementById("eventFormModal").style.display = "flex";
};

document.getElementById("closeModal").onclick = function () {
    document.getElementById("eventFormModal").style.display = "none";
    document.getElementById("eventForm").reset();
    hideErrors();
};

function hideErrors() {
    ["nameError", "dateError", "descError"].forEach(id => {
        document.getElementById(id).style.display = "none";
    });
}

document.getElementById("eventForm").onsubmit = function (e) {
    e.preventDefault();
    hideErrors();

    const name = document.getElementById("eventName").value.trim();
    const image = document.getElementById("eventImage").value.trim();
    const date = document.getElementById("eventDate").value;
    const desc = document.getElementById("eventDesc").value.trim();

    let valid = true;
    if (name.length < 3) {
        document.getElementById("nameError").style.display = "block";
        valid = false;
    }
    if (!date) {
        document.getElementById("dateError").style.display = "block";
        valid = false;
    }
    if (!desc) {
        document.getElementById("descError").style.display = "block";
        valid = false;
    }
    if (!valid) return;

    const li = document.createElement("li");
    li.innerHTML = `
        <img class="imageCard" src="${image}" alt="${name}">
        <span>${name}</span>
        <span>${date}</span>
        <span>${desc}</span>
    `;
    document.querySelector(".events-list").appendChild(li);

    document.getElementById("eventFormModal").style.display = "none";
    document.getElementById("eventForm").reset();
};

document.getElementById("eventName").addEventListener("input", function () {
    if (this.value.trim().length >= 3) {
        document.getElementById("nameError").style.display = "none";
    }
});

hideErrors();
