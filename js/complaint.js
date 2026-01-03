function validateForm() {
    let isValid = true;

    const userName = document.querySelector('input[name="user_name"]');
    const email = document.querySelector('input[name="email"]');
    const houseNo = document.querySelector('input[name="house_no"]');
    const contactNo = document.querySelector('input[name="contact_no"]');
    const complaintTitle = document.querySelector('input[name="complaint_title"]');
    const category = document.querySelector('select[name="category"]');
    const complaintDesc = document.querySelector('textarea[name="complaint"]');
    const incidentDate = document.querySelector('input[name="incident_date"]');

    // Clear all previous error messages
    document.querySelectorAll('.error').forEach(span => span.innerText = '');

    // Full Name
    if (userName.value.trim() === "") {
        document.getElementById('user_nameError').innerText = "Full name is required.";
        isValid = false;
    }

    // Email
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (email.value.trim() === "") {
        document.getElementById('emailError').innerText = "Email is required.";
        isValid = false;
    } else if (!emailPattern.test(email.value.trim())) {
        document.getElementById('emailError').innerText = "Enter a valid email address.";
        isValid = false;
    }

    // House No
    if (houseNo.value.trim() === "") {
        document.getElementById('house_noError').innerText = "House/Apartment number is required.";
        isValid = false;
    }

    // Contact No
    const phonePattern = /^[0-9]{10}$/;
    if (contactNo.value.trim() === "") {
        document.getElementById('contact_noError').innerText = "Contact number is required.";
        isValid = false;
    } else if (!phonePattern.test(contactNo.value.trim())) {
        document.getElementById('contact_noError').innerText = "Enter a valid 10-digit number.";
        isValid = false;
    }

    // Complaint Title
    if (complaintTitle.value.trim() === "") {
        document.getElementById('complaint_titleError').innerText = "Complaint title is required.";
        isValid = false;
    }

    // Complaint Category
    if (category.value === "") {
        document.getElementById('categoryError').innerText = "Please select a complaint category.";
        isValid = false;
    }

    // Complaint Description
    if (complaintDesc.value.trim() === "") {
        document.getElementById('complaintError').innerText = "Please enter complaint description.";
        isValid = false;
    }

    // Incident Date
    if (incidentDate.value === "") {
        document.getElementById('incident_dateError').innerText = "Incident date is required.";
        isValid = false;
    }

    return isValid;
}
