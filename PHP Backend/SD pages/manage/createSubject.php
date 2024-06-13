<style>
    .editable input {
        width: 100%;
        box-sizing: border-box;
    }
</style>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['userid']) && isset($_POST['field']) && isset($_POST['value'])) {
    $userid = $_POST['userid'];
    $field = $_POST['field'];
    $value = $_POST['value'];

    $stmt = $con->prepare("UPDATE subject_tb SET $field = ? WHERE subject_id = ?");
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
    $stmt = $con->prepare("DELETE FROM subject_tb WHERE subject_id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Item deleted successfully!');</script>";
    } else {
        echo "<script>alert('Something went wrong!');</script>";
    }
    exit;
}

// Fetch distinct department options from the database
$departments = [];

$fetchDepartments = $con->query("SELECT DISTINCT department_name FROM department_tb");
while ($row = $fetchDepartments->fetch_assoc()) {
    $departments[] = $row['department_name'];
}
?>
<div class="main">
    <div class="create-new-room">
        <div class="text">
            <span>Create New Subject</span>
        </div>
        <form action="?action=createSubject" method="post" class="side-by-side">
            <div>
                <label fo>Subject Name</label><br>
                <input type="text" name="SubjectName" required>
            </div>

            <div>
                <label>Subject Department</label><br>
                <select name="SubDept">
                    <?php
                    $fetchdept = mysqli_query($con, "SELECT department_name FROM department_tb");
                    while ($row = mysqli_fetch_array($fetchdept)) {
                    ?>
                        <option value="<?php echo $row['department_name'] ?>"><?php echo $row['department_name'] ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>

            <div>
                <label>Room Type</label><br>
                <select name="roomType">
                    <option value="Lecture">Lecture</option>
                    <option value="Laboratory">Laboratory</option>
                </select>
                <input type="submit" value="Add" class="submit-room">
            </div>
        </form>
    </div>

    <div class="list">
        <div class="text">
            <span>Subject List</span>
        </div>
        <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search for subject..">
        <table id="roomTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Subject Name</th>
                    <th>Department</th>
                    <th>Type</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // include('../../config.php');

                $getSubjects = $con->query("SELECT * FROM subject_tb");
                $i = 1;
                while ($row = $getSubjects->fetch_assoc()) {
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td class="editable" data-userid="<?php echo $row['subject_id']; ?>" data-field="subject_name"><?php echo $row['subject_name']; ?></td>
                        <td class="editable" data-userid="<?php echo $row['subject_id']; ?>" data-field="subject_department"><?php echo $row['subject_department']; ?></td>
                        <td class="editable" data-userid="<?php echo $row['subject_id']; ?>" data-field="subject_type"><?php echo $row['subject_type']; ?></td>
                        <td><a href="?del=<?php echo $row['subject_id']; ?>" onclick="return confirm('Are you sure you want to delete this item?')"><button>Delete</button></a></td>
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
    document.addEventListener('DOMContentLoaded', function() {
        const departmentOptions = <?php echo json_encode($departments); ?>;
        const typeOptions = ['Lecture', 'Laboratory'];

        document.querySelectorAll('.editable').forEach(cell => {
            cell.addEventListener('dblclick', function() {
                if (!this.querySelector('input') && !this.querySelector('select')) {
                    let originalValue = this.textContent;
                    let userId = this.getAttribute('data-userid');
                    let field = this.getAttribute('data-field');
                    let inputElement;

                    if (field === 'subject_name') {
                        inputElement = document.createElement('input');
                        inputElement.type = 'text';
                        inputElement.value = originalValue;
                    } else {
                        inputElement = document.createElement('select');
                        let options = field === 'subject_department' ? departmentOptions : typeOptions;
                        options.forEach(optionValue => {
                            let option = document.createElement('option');
                            option.value = optionValue;
                            option.text = optionValue;
                            option.selected = optionValue === originalValue;
                            inputElement.appendChild(option);
                        });
                    }

                    this.textContent = '';
                    this.appendChild(inputElement);
                    inputElement.focus();

                    inputElement.addEventListener('blur', function() {
                        cell.textContent = originalValue;
                    });

                    inputElement.addEventListener('change', function() {
                        let newValue = this.value;

                        // Make an AJAX request to update the database
                        let xhr = new XMLHttpRequest();
                        xhr.open('POST', '', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                if (xhr.responseText.trim() === 'success') {
                                    cell.textContent = newValue;
                                } else {
                                    cell.textContent = originalValue;
                                    cell.textContent = newValue;

                                    // alert('Update failed');
                                }
                            }
                        };
                        xhr.send(`userid=${userId}&field=${field}&value=${newValue}`);
                    });
                }
            });
        });
    });
</script>




<?php

if (isset($_GET['action']) && $_GET['action'] == 'createSubject') {

    $SubjectName = $_POST['SubjectName'];
    $SubDept = $_POST['SubDept'];
    $roomType = $_POST['roomType'];

    $validateSubjects = $con->prepare("SELECT * FROM subject_tb WHERE subject_name = ? AND subject_department = ? AND subject_type = ?");
    $validateSubjects->bind_param("sss", $SubjectName, $SubDept, $roomType);
    $validateSubjects->execute();
    $result = $validateSubjects->get_result();
    if ($result->num_rows > 0) {
        echo "<script>alert('Subject already exists')</script>";
    } else {


        $insertSubject = $con->prepare("INSERT INTO subject_tb(subject_name, subject_department, subject_type) VALUES (?, ?, ?)");
        $insertSubject->bind_param('sss', $SubjectName, $SubDept, $roomType);

        if ($insertSubject->execute()) {
            echo "<script>alert('Subject added');window.location.href = 'admin-manage-subject-list.php';</script>";
        } else {
            echo "<script>alert('Error creating Department');</script>";
        }
    }
}
