// admin-toasts.js
document.addEventListener("DOMContentLoaded", () => {

    const form = document.querySelector("form");

    if (!form) return;

    form.addEventListener("submit", function (e) {
        const nameInput = form.querySelector("input[name='name']");
        const blockInput = form.querySelector("input[name='block']");

        const name = nameInput.value.trim();
        const block = blockInput.value.trim().toUpperCase();

        // Regex: APT followed by exactly 3 digits (APT001)
        const aptRegex = /^APT-\d{3}$/;

        // Validation flags
        let error = null;

        if (!aptRegex.test(name)) {
            error = "Apartment name must be like APT-001, APT-002, etc.";
        } else if (block !== "A" && block !== "B") {
            error = "Block must be either A or B only.";
        }

        if (error) {
            e.preventDefault(); // stop form submission
            showToast(error, "error");
        }
    });

    const toastContainer = document.getElementById("toastContainer");
    if (!toastContainer) {
        console.warn("toastContainer not found — toasts will not display");
        return;
    }

    function showToast(message, type = "success") {
        const toast = document.createElement("div");
        toast.className = `toast ${type}`;
        toast.textContent = message;
        toastContainer.appendChild(toast);

        setTimeout(() => toast.classList.add("show"), 50);

        setTimeout(() => {
            toast.classList.remove("show");
            setTimeout(() => {
                if (toast.parentNode) {
                    toastContainer.removeChild(toast);
                }
            }, 500);
        }, 3000);
    }

    // ─── Read URL parameters and show toast ───
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.has("msg")) {
    const msgType = urlParams.get("msg");
    const text   = urlParams.get("text") || "Operation completed";

    let type = "success";
    if (msgType === "error") type = "error";
    else if (msgType === "warning") type = "warning";

    showToast(text, type);

    //ADD THIS REDIRECT AFTER 3 SECONDS
    if (type === "success") {
        setTimeout(() => {
            window.location.href = "apartment_list.php";
        }, 3000);
    }
}
    if (urlParams.has("error")) {
        const msg = urlParams.get("error");
        showToast(msg, "error");
}
});