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
