function validateForm() {
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirm_password").value;

    // Check empty fields
    const inputs = document.querySelectorAll("input[required]");
    for (let input of inputs) {
        if (input.value.trim() === "") {
            alert("Please fill all required fields.");
            input.focus();
            return false;
        }
    }

    // Check password match
    if (password !== confirmPassword) {
        alert("Passwords do not match!");
        document.getElementById("confirm_password").focus();
        return false;
    }

    // Password length check (optional but recommended)
    if (password.length < 6) {
        alert("Password must be at least 6 characters long.");
        document.getElementById("password").focus();
        return false;
    }

    return true;
}