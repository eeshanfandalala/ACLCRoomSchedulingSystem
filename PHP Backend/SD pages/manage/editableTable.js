document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".editable").forEach((cell) => {
    cell.addEventListener("dblclick", function () {
      if (!this.querySelector("input")) {
        let originalValue = this.textContent;
        let input = document.createElement("input");
        input.type = "text";
        input.value = originalValue;
        this.textContent = "";
        this.appendChild(input);
        input.focus();

        input.addEventListener("blur", function () {
          cell.textContent = originalValue;
        });

        input.addEventListener("keypress", function (e) {
          if (e.key === "Enter") {
            let newValue = this.value;
            let userId = cell.getAttribute("data-userid");
            let field = cell.getAttribute("data-field");

            // Make an AJAX request to update the database
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "", true);
            xhr.setRequestHeader(
              "Content-Type",
              "application/x-www-form-urlencoded"
            );
            xhr.onreadystatechange = function () {
              if (xhr.readyState === 4 && xhr.status === 200) {
                if (xhr.responseText.trim() == "Updated success") {
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

document.addEventListener("DOMContentLoaded", function () {
    const buildingOption = ['A', 'B'];
    const floorOption = ['1', '2', '3', '4', '5', '6'];
    const typeOption = ['Lecture', 'Laboratory'];

});
