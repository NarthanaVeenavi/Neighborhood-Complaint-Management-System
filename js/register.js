document.addEventListener("DOMContentLoaded", () => {

    const toastContainer = document.getElementById("toastContainer");

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

    // Disable future dates for the Joining Date field
    const joiningDateField = document.getElementById("joining_date");
    if (joiningDateField) {
        const today = new Date().toISOString().split('T')[0]; // Get today's date in YYYY-MM-DD format
        joiningDateField.setAttribute('max', today); // Disable future dates
    }

    // Form validation
    const form = document.querySelector("form");
    if (form) {
        form.addEventListener("submit", (e) => {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirm_password").value;

            // Check required fields
            const inputs = document.querySelectorAll("input[required]");
            for (let input of inputs) {
                if (input.value.trim() === "") {
                    e.preventDefault();
                    showToast("Please fill all required fields.", "warning");
                    input.focus();
                    return;
                }
            }

            // Check password match
            if (password !== confirmPassword) {
                e.preventDefault();
                showToast("Passwords do not match!", "error");
                document.getElementById("confirm_password").focus();
                return;
            }

            // Password length
            if (password.length < 6) {
                e.preventDefault();
                showToast("Password must be at least 6 characters long.", "warning");
                document.getElementById("password").focus();
                return;
            }
        });
    }

    // Check query parameters for success or error
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('success')) {
        showToast("Registration successful! You can now login.", "success");
        // Redirect to welcome page after 3 seconds
        setTimeout(() => {
            window.location.href = "../pages/welcome_page.php";
        }, 3000); // 3 seconds delay before redirect
    }

    if (urlParams.has('error')) {
        const msg = urlParams.get('error');
        showToast(msg, "error");
    }

    

});
