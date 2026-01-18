function openDeleteModal(deleteUrl) {
    document.getElementById("confirmDeleteBtn").href = deleteUrl;
    document.getElementById("deleteModal").style.display = "flex";
}

function closeModal() {
    document.getElementById("deleteModal").style.display = "none";
}

window.onclick = function(e) {
    if (e.target === document.getElementById("deleteModal")) {
        closeModal();
    }
}
