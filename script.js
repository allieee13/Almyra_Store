function editItem(itemId, field) {
    var newValue = prompt("Enter new value for " + field);
    if (newValue !== null) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "edit_item.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    alert(xhr.responseText);
                    window.location.reload(); // Reload the page to reflect changes
                } else {
                    alert("Error updating item: " + xhr.statusText);
                }
            }
        };
        xhr.send("itemId=" + itemId + "&field=" + field + "&value=" + encodeURIComponent(newValue));
    }
}

function confirmDelete(itemId) {
    var confirmDelete = confirm("Are you sure you want to delete this item?");
    if (confirmDelete) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_item.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    alert(xhr.responseText);
                    window.location.reload(); // Reload the page to reflect changes
                } else {
                    alert("Error deleting item: " + xhr.statusText);
                }
            }
        };
        xhr.send("itemId=" + itemId);
    }
}

function searchItems(query) {
    var tableBody = document.getElementById("itemTableBody");
    var rows = tableBody.getElementsByTagName("tr");
    var noResults = document.getElementById("noResults");

    var found = false;

    for (var i = 0; i < rows.length; i++) {
        var cells = rows[i].getElementsByTagName("td");
        var itemName = cells[1].innerText.toLowerCase();
        if (itemName.includes(query.toLowerCase())) {
            rows[i].style.display = "";
            found = true;
        } else {
            rows[i].style.display = "none";
        }
    }

    noResults.style.display = found ? "none" : "";
}
