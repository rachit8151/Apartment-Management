/* ======================
   PAGE LOADER (AJAX)
   ====================== */
function loadPage(page) {
    fetch(page)
        .then(res => res.text())
        .then(html => {
            document.getElementById("pageContainer").innerHTML = html;
            bindDynamicForms(); // 🔥 rebind after ajax load
        })
        .catch(err => console.error("Load error:", err));
}

/* ======================
   DYNAMIC FORM BINDING
   ====================== */
function bindDynamicForms() {

    /* ===== ANNOUNCEMENT ===== */
    const announcementForm = document.getElementById("announcementForm");
    if (announcementForm) {
        announcementForm.onsubmit = e => {
            e.preventDefault();
            fetch("announcementApp.php", {
                method: "POST",
                body: new FormData(announcementForm)
            })
            .then(r => r.json())
            .then(d => {
                alert(d.status === "success" ? "Saved ✅" : d.msg);
                if (d.status === "success") loadPage("announcement.php");
            })
            .catch(() => alert("Server error ❌"));
        };
    }

    /* ===== MAINTENANCE ===== */
    const maintenanceForm = document.getElementById("maintenanceForm");
    if (maintenanceForm) {
        maintenanceForm.onsubmit = e => {
            e.preventDefault();
            fetch("maintenanceApp.php", {
                method: "POST",
                body: new FormData(maintenanceForm)
            })
            .then(r => r.json())
            .then(d => {
                alert(
                    d.status === "success"
                        ? "Maintenance saved ✅"
                        : d.msg || "Error ❌"
                );
                if (d.status === "success") loadPage("maintenance.php");
            })
            .catch(() => alert("Server error ❌"));
        };
    }

    /* ===== HALL ===== */
    const hallForm = document.getElementById("hallForm");
    if (hallForm) {
        hallForm.onsubmit = e => {
            e.preventDefault();
            fetch("hallApp.php", {
                method: "POST",
                body: new FormData(hallForm)
            })
            .then(r => r.json())
            .then(d => {
                alert(d.status === "success" ? "Hall saved ✅" : d.msg);
                if (d.status === "success") loadPage("hall.php");
            })
            .catch(() => alert("Server error ❌"));
        };
    }

    /* ===== EVENT ===== */
    const eventForm = document.getElementById("eventForm");
    if (eventForm) {
        eventForm.onsubmit = e => {
            e.preventDefault();
            fetch("eventApp.php", {
                method: "POST",
                body: new FormData(eventForm)
            })
            .then(r => r.json())
            .then(d => {
                alert(d.status === "success" ? "Event saved ✅" : d.msg);
                if (d.status === "success") loadPage("event.php");
            })
            .catch(() => alert("Server error ❌"));
        };
    }

    /* ===== EXPENSE ===== */
    const expenseForm = document.getElementById("expenseForm");
    if (expenseForm) {
        expenseForm.onsubmit = e => {
            e.preventDefault();
            fetch("expenseApp.php", {
                method: "POST",
                body: new FormData(expenseForm)
            })
            .then(r => r.json())
            .then(d => {
                alert(d.status === "success" ? "Expense saved ✅" : d.msg);
                if (d.status === "success") loadPage("expense.php");
            })
            .catch(() => alert("Server error ❌"));
        };
    }

    /* ===== COMPLAINT ===== */
    const complaintForm = document.getElementById("complaintForm");
    if (complaintForm) {
        complaintForm.onsubmit = e => {
            e.preventDefault();
            fetch("complaintApp.php", {
                method: "POST",
                body: new FormData(complaintForm)
            })
            .then(r => r.json())
            .then(d => {
                alert(
                    d.status === "success"
                        ? "Complaint submitted ✅"
                        : d.msg
                );
                if (d.status === "success") loadPage("complaint.php");
            })
            .catch(() => alert("Server error ❌"));
        };
    }

    /* ===== HALL BOOKING ===== */
    const hallBookingForm = document.getElementById("hallBookingForm");
    if (hallBookingForm) {
        hallBookingForm.onsubmit = e => {
            e.preventDefault();
            fetch("hallBookingApp.php", {
                method: "POST",
                body: new FormData(hallBookingForm)
            })
            .then(r => r.json())
            .then(d => {
                alert(d.status === "success" ? "Booking saved ✅" : d.msg);
                if (d.status === "success") loadPage("hallBooking.php");
            })
            .catch(() => alert("Server error ❌"));
        };
    }
}

/* ======================
   ACTIONS (DELETE / APPROVE / REJECT)
   ====================== */

/* ===== DELETE HALL ===== */
function deleteHall(id) {
    if (!confirm("Delete this hall?")) return;

    const fd = new FormData();
    fd.append("delete_hall", id);

    fetch("hallApp.php", { method: "POST", body: fd })
        .then(r => r.json())
        .then(d => {
            alert(d.status === "success" ? "Hall deleted ✅" : d.msg);
            if (d.status === "success") loadPage("hall.php");
        })
        .catch(() => alert("Server error ❌"));
}

/* ===== DELETE EVENT ===== */
function deleteEvent(id) {
    if (!confirm("Delete this event?")) return;

    const fd = new FormData();
    fd.append("delete_event", id);

    fetch("eventApp.php", { method: "POST", body: fd })
        .then(r => r.json())
        .then(d => {
            alert(d.status === "success" ? "Event deleted ✅" : d.msg);
            if (d.status === "success") loadPage("event.php");
        })
        .catch(() => alert("Server error ❌"));
}

/* ===== DELETE HALL BOOKING ===== */
function deleteHallBooking(id) {
    if (!confirm("Delete this booking?")) return;

    const fd = new FormData();
    fd.append("delete_booking", id);

    fetch("hallBookingApp.php", { method: "POST", body: fd })
        .then(r => r.json())
        .then(d => {
            alert(d.status === "success" ? "Booking deleted ✅" : d.msg);
            if (d.status === "success") loadPage("hallBooking.php");
        })
        .catch(() => alert("Server error ❌"));
}

/* ===== APPROVE / REJECT HALL BOOKING ===== */
function updateBookingStatus(id, status) {
    if (!confirm("Confirm " + status + " booking?")) return;

    const fd = new FormData();
    fd.append("update_status", 1);
    fd.append("booking_id", id);
    fd.append("status", status);

    fetch("hallBookingApp.php", { method: "POST", body: fd })
        .then(r => r.json())
        .then(d => {
            alert(d.status === "success" ? "Status updated ✅" : d.msg);
            if (d.status === "success") loadPage("hallBooking.php");
        })
        .catch(() => alert("Server error ❌"));
}

/* ===== RESOLVE COMPLAINT ===== */
function resolveComplaint(id) {
    if (!confirm("Mark this complaint as resolved?")) return;

    const fd = new FormData();
    fd.append("resolve_id", id);

    fetch("complaintApp.php", { method: "POST", body: fd })
        .then(r => r.json())
        .then(d => {
            alert(d.status === "success" ? "Resolved ✅" : d.msg);
            if (d.status === "success") loadPage("complaint.php");
        })
        .catch(() => alert("Server error ❌"));
}

/* ======================
   SIDEBAR NAVIGATION
   ====================== */
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".sidebar button").forEach(btn => {
        btn.onclick = () => loadPage(btn.dataset.page);
    });
});
