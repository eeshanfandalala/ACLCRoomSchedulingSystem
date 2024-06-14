
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['userid']) && isset($_POST['field']) && isset($_POST['value'])) {
    $userid = $_POST['userid'];
    $field = $_POST['field'];
    $value = $_POST['value'];

    $stmt = $con->prepare("UPDATE department_tb SET $field = ? WHERE department_id = ?");
    $stmt->bind_param("si", $value, $userid);
    if ($stmt->execute()) {
        echo 'Updated success';
    } else {
        echo 'error';
    }

    $stmt->close();
    exit;
}

if (isset($_GET['del'])) {
    // echo 'hi';
    $id = $_GET['del'];
    $stmt = $con->prepare("DELETE FROM department_tb WHERE department_id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Item deleted successfully!');</script>";
    } else {
        echo "<script>alert('Something went wrong!');</script>";
    }
    exit;
}
?>
<div class="main-flex">
    <div class="create-new-form">
        <div class="text">
            <span>Create New Department</span>
        </div>
        <form action="?action=createDepartment" method="post">
            <label>Department Name</label><br>
            <input type="text" name="deptName" required>
            <input type="submit" value="Add">
        </form>
    </div>

    <div class="list">
        <div class="text">
            <span>Department List</span>
        </div>
        <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search for department...">
        <table id="roomTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Department Name</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                // include('../../config.php');

                $getDepartment = $con->query("SELECT * FROM department_tb");
                $i = 1;
                while ($row = $getDepartment->fetch_assoc()) {
                ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td class="editable" data-userid="<?php echo $row['department_id']; ?>" data-field="department_name"><?php echo $row['department_name'] ?></td>
                        <td><a href="?del=<?php echo $row['department_id']; ?>" class="delete-link" onclick="return confirm('Are you sure you want to delete this item?')"><button>Delete</button></a></td>
                    </tr>
                <?php
                    $i++;
                }
                ?>
            </tbody>
        </table>
    </div>
</div>


<script src="searchtable.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".editable").forEach((cell) => {
            cell.addEventListener("dblclick", function() {
                if (!this.querySelector("input")) {
                    let originalValue = this.textContent;
                    let input = document.createElement("input");
                    input.type = "text";
                    input.value = originalValue;
                    this.textContent = "";
                    this.appendChild(input);
                    input.focus();

                    input.addEventListener("blur", function() {
                        cell.textContent = originalValue;
                    });

                    input.addEventListener("keypress", function(e) {
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
                            xhr.onreadystatechange = function() {
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
</script>

<?php

if (isset($_GET['action']) && $_GET['action'] == 'createDepartment') {
    $deptName = $_POST['deptName'];

    // Prepare a statement to check if the department already exists
    $checkIfExist = $con->prepare("SELECT * FROM department_tb WHERE department_name = ?");
    $checkIfExist->bind_param("s", $deptName);
    $checkIfExist->execute();
    $resultDept = $checkIfExist->get_result();

    if ($resultDept->num_rows > 0) {
        echo "<script>alert('This department already exists');</script>";
    } else {
        // Prepare a statement to insert the new department
        $insertDept = $con->prepare("INSERT INTO department_tb(department_name) VALUES (?)");
        $insertDept->bind_param("s", $deptName);
        if ($insertDept->execute()) {
            echo "<script>alert('Department created successfully');window.location.href = 'admin-manage-department-list.php';</script>";
        } else {
            echo "<script>alert('Error creating department');</script>";
        }
        $insertDept->close();
    }

    $checkIfExist->close();
    $con->close();
}
?>