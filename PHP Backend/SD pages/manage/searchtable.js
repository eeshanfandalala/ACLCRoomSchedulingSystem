function searchTable() {
    var input, filter, table, tr, td, i, j, txtValue, found;
    input = document.getElementById("searchInput");
    filter = input.value.toLowerCase();
    table = document.getElementById("roomTable");
    tr = table.getElementsByTagName("tr");
    found = false;

    var noResultsRow = document.getElementById("noResultsRow");
    if (noResultsRow) {
        noResultsRow.remove();
    }

    for (i = 1; i < tr.length; i++) {
        tr[i].style.display = "none";
        td = tr[i].getElementsByTagName("td");
        for (j = 0; j < td.length; j++) {
            if (td[j]) {
                txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toLowerCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                    found = true; 
                    break;
                }
            }
        }
    }

    // 'Not Found'
    if (!found) {
        var tbody = table.getElementsByTagName("tbody")[0];
        var row = tbody.insertRow();
        row.id = "noResultsRow";
        var cell = row.insertCell(0);
        cell.colSpan = table.rows[0].cells.length;
        cell.style.textAlign = "center";
        cell.style.color = "white";
        cell.textContent = "Not Found";
    }
}
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.editable').forEach(cell => {
        cell.addEventListener('dblclick', function() {
            if (!this.querySelector('input')) {
                let originalValue = this.textContent;
                let input = document.createElement('input');
                input.type = 'text';
                input.value = originalValue;
                this.textContent = '';
                this.appendChild(input);
                input.focus();

                input.addEventListener('blur', function() {
                    cell.textContent = originalValue;
                });

                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        let newValue = this.value;
                        let userId = cell.getAttribute('data-userid');
                        let field = cell.getAttribute('data-field');

                        // Make an AJAX request to update the database
                        let xhr = new XMLHttpRequest();
                        xhr.open('POST', '', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                if (xhr.responseText.trim() == 'Updated success') {
                                    cell.textContent = newValue;
                                } else {
                                    cell.textContent = originalValue;
                                    cell.textContent = newValue;

                                    // alert('Update failed');
                                }
                            }
                        };
                        xhr.send(`userid=${userId}&field=${field}&value=${newValue}`);
                    }
                });
            }
        });
    });
});