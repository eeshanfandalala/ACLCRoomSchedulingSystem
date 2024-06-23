<?php

if (isset($_GET['del'])) {
    $id = $_GET['del'];
    $stmt = $con->prepare("DELETE FROM subject_tb WHERE subject_id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Item deleted successfully!');window.location.href = ../../../admin-manage-subject-list.php;</script>";
    } else {
        echo "<script>alert('Something went wrong!');</script>";
    }
    exit;
}

// Fetch distinct department options from the database
$departments = [];

$fetchDepartments = $con->query("SELECT department_id, department_name FROM department_tb");
while ($row = $fetchDepartments->fetch_assoc()) {
    $departments[] = $row;
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
                <select name="SubDept" required>
                    <option value="" disabled selected></option>
                    <?php
                    $fetchdept = mysqli_query($con, "SELECT * FROM department_tb");
                    while ($row = mysqli_fetch_array($fetchdept)) {
                    ?>
                        <option value="<?php echo $row['department_id'] ?>"><?php echo $row['department_name'] ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>

            <div>
                <label>Room Type</label><br>
                <select name="roomType" required>
                <option value="" disabled selected></option>

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
        <p class="guide">Please double-click on any cell to make edits.</p>

        <table id="roomTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Subject Name</th>
                    <th>Department</th>
                    <th>Type</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                // include('../../config.php');

                $getSubjects = $con->query("SELECT 
                s.subject_id, 
                s.subject_name, 
                s.subject_units, 
                s.subject_description, 
                s.subject_department, 
                d.department_name, 
                s.subject_type
            FROM 
                subject_tb s
            LEFT JOIN 
                department_tb d
            ON 
                s.subject_department = d.department_id;");
                $i = 1;
                while ($row = $getSubjects->fetch_assoc()) {
                ?>
                    <tr>
                        <td><?php echo $i; ?></td>
                        <td class="editable" data-userid="<?php echo $row['subject_id']; ?>" data-field="subject_name"><?php echo $row['subject_name']; ?></td>
                        <td class="editable-dropdown" data-userid="<?php echo $row['subject_id']; ?>" data-field="subject_department"><?php echo $row['department_name']; ?></td>
                        <td class="editable-dropdown" data-userid="<?php echo $row['subject_id']; ?>" data-field="subject_type"><?php echo $row['subject_type']; ?></td>
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
                if (!this.querySelector('input')) {
                    let originalValue = this.textContent.trim();
                    let input = document.createElement('input');
                    input.type = 'text';
                    input.value = originalValue;
                    this.textContent = '';
                    this.appendChild(input);
                    input.focus();

                    input.addEventListener('blur', function() {
                        let newValue = this.value.trim();
                        let userId = cell.getAttribute('data-userid');
                        let field = cell.getAttribute('data-field');

                        // Make an AJAX request to update the database
                        let xhr = new XMLHttpRequest();
                        xhr.open('POST', './PHP Backend/SD pages/manage/actionUpdateSubject.php', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                let response = xhr.responseText.trim();
                                if (response === 'success') {
                                    cell.textContent = newValue;
                                } else {
                                    cell.textContent = originalValue;
                                    // cell.textContent = newValue;

                                    alert(response);
                                }
                            }
                        };
                        xhr.send(`userid=${userId}&field=${field}&value=${newValue}`);
                    });
                }
            });
        });

        document.querySelectorAll('.editable-dropdown').forEach(cell => {
            cell.addEventListener('dblclick', function() {
                if (!this.querySelector('select')) {
                    let originalValue = this.textContent.trim();
                    let userId = this.getAttribute('data-userid');
                    let field = this.getAttribute('data-field');
                    let standing = this.getAttribute('data-standing');
                    let select = document.createElement('select');

                    let options;
                    if (field === 'subject_type') {
                        options = typeOptions;
                    } else if (field === 'subject_department') {
                        options = departmentOptions.map(department => {
                            return {
                                value: department.department_id,
                                text: department.department_name
                            };
                        });
                    }

                    options.forEach(optionValue => {
                        let option = document.createElement('option');
                        if (field === 'subject_department') {
                            option.value = optionValue.value;
                            option.text = optionValue.text;
                            option.selected = optionValue.text === originalValue;
                        } else {
                            option.value = optionValue;
                            option.text = optionValue;
                            option.selected = optionValue === originalValue;
                        }
                        select.appendChild(option);
                    });

                    this.textContent = '';
                    this.appendChild(select);
                    select.focus();

                    select.addEventListener('blur', function() {
                        cell.textContent = originalValue;
                    });

                    select.addEventListener('change', function() {
                        let newValue = this.value.trim();

                        // Make an AJAX request to update the database
                        let xhr = new XMLHttpRequest();
                        xhr.open('POST', './PHP Backend/SD pages/manage/actionUpdateSubject.php', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                let response = xhr.responseText.trim();
                                if (response === 'success') {
                                    cell.textContent = select.options[select.selectedIndex].text;
                                } else {
                                    cell.textContent = originalValue;
                                    // cell.textContent = select.options[select.selectedIndex].text;
                                    alert(response);
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
