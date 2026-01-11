function showToast(message, type = "success") {
    const container = document.getElementById("toastContainer");
    const toast = document.createElement("div");
    toast.className = `toast ${type}`;
    toast.textContent = message;
    container.appendChild(toast);

    setTimeout(() => toast.classList.add("show"), 50);
    setTimeout(() => {
        toast.classList.remove("show");
        setTimeout(() => container.removeChild(toast), 500);
    }, 3000);
}

// Check query parameters for success or error messages
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.has('success')) {
    showToast(urlParams.get('success'), "success");
}
if (urlParams.has('error')) {
    showToast(urlParams.get('error'), "error");
}