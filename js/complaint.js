document.addEventListener("DOMContentLoaded", () => {
    const toastContainer = document.getElementById("toastContainer");

    function showToast(message, type = "success") {
        if (!toastContainer) return;

        const toast = document.createElement("div");
        toast.className = `toast ${type}`;
        toast.textContent = message;
        toastContainer.appendChild(toast);

        setTimeout(() => toast.classList.add("show"), 50);

        setTimeout(() => {
            toast.classList.remove("show");
            setTimeout(() => {
                if (toast.parentNode) toast.remove();
            }, 500);
        }, 3000);
    }
    // ─── Read URL parameters and show toast ───
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.has("msg")) {
        const msgType = urlParams.get("msg");
        const text = urlParams.get("text") || "Operation completed";

        let type = "success";
        if (msgType === "error") type = "error";
        if (msgType === "warning") type = "warning";

        showToast(text, type);

        // Redirect after success only
        if (type === "success") {
            setTimeout(() => {
                window.location.href = "list_my_complaints.php";
            }, 3000);
        }
    }

    // Disable future dates for the Incident Date field
    const incidentDateField = document.getElementById("incident_date");
    if (incidentDateField) {
        const today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
        incidentDateField.setAttribute('max', today); // Disable future dates
    }

    const form = document.querySelector("form");
    if (!form) return;

    form.addEventListener("submit", function (e) {

        const title = form.querySelector("input[name='complaint_title']");
        const category = form.querySelector("select[name='category']");
        const description = form.querySelector("textarea[name='complaint']");
        const date = form.querySelector("input[name='incident_date']");
        const priority = form.querySelector("input[name='priority']:checked");

        if (!title.value.trim()) {
            e.preventDefault();
            showToast("Complaint title is required.", "error");
            title.focus();
            return;
        }

        if (!category.value) {
            e.preventDefault();
            showToast("Please select a complaint category.", "error");
            category.focus();
            return;
        }

        if (!description.value.trim()) {
            e.preventDefault();
            showToast("Complaint description cannot be empty.", "error");
            description.focus();
            return;
        }

        if (!date.value) {
            e.preventDefault();
            showToast("Please select incident date.", "error");
            date.focus();
            return;
        }

        if (!priority) {
            e.preventDefault();
            showToast("Please select complaint priority.", "error");
            return;
        }
    });

});

