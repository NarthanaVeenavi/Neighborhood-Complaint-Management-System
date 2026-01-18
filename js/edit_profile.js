document.addEventListener("DOMContentLoaded", () => {

    const toastContainer = document.getElementById("toastContainer");

    // Disable future dates for the Joining Date field
    const joiningDateField = document.getElementById("joining_date");
    if (joiningDateField) {
        const today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
        joiningDateField.setAttribute('max', today); // Disable future dates
    }

    function showToast(message, type = "success") {
        const toast = document.createElement("div");
        toast.className = `toast ${type}`;
        toast.textContent = message;
        toastContainer.appendChild(toast);

        setTimeout(() => toast.classList.add("show"), 50);

        setTimeout(() => {
            toast.classList.remove("show");
            setTimeout(() => toastContainer.removeChild(toast), 500);
        }, 3000);
    }

    // Check query parameters for success or error
    const urlParams = new URLSearchParams(window.location.search);

    if (urlParams.has('success')) {
        showToast("Profile Updated successfully.", "success");
        // Redirect to welcome page after 3 seconds
        setTimeout(() => {
            window.location.href = "../pages/profile.php";
        }, 3000); // 3 seconds delay before redirect
    }

    if (urlParams.has('error')) {
        const msg = urlParams.get('error');
        showToast(msg, "error");
    }

});
