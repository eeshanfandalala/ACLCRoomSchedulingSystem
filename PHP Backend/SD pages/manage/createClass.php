<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['userid']) && isset($_POST['field']) && isset($_POST['value'])) {
    $userid = $_POST['userid'];
    $field = $_POST['field'];
    $value = $_POST['value'];

    $stmt = $con->prepare("UPDATE class_tb SET $field = ? WHERE class_id = ?");
    $stmt->bind_param('si', $value, $userid);

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

    $stmt->close();
    exit;
}

if (isset($_GET['del'])) {
    $id = $_GET['del'];
    $stmt = $con->prepare("DELETE FROM class_tb WHERE class_id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "<script>alert('Item deleted successfully!');</script>";
    } else {
        echo "<script>alert('Something went wrong!');</script>";
    }
}

// Fetch distinct department options from the database
$departments = [];

$fetchDepartments = $con->query("SELECT DISTINCT department_name FROM department_tb");
while ($row = $fetchDepartments->fetch_assoc()) {
    $departments[] = $row['department_name'];
}
?>
<div class="main">
    <div class="top" class="side-by-side">
        <form action="" method="post" class="side-by-side">
            <div>
                <div class="text">
                    <span>Create New Class</span>
                </div>
                <label>Standing</label><br>
                <input type="radio" name="standing" value="College" onchange="this.form.submit()" <?php if (isset($_POST['standing']) || isset($_POST['sub1']) || isset($_POST['sub2'])) {
                                                                                                        echo ($_POST['standing'] == 'College') ? "checked" : "";
                                                                                                    } ?> required>
                <label style="margin-right: 10px">College</label>

                <input type="radio" name="standing" value="SHS" onchange="this.form.submit()" <?php if (isset($_POST['standing']) || isset($_POST['sub1']) || isset($_POST['sub2'])) {
                                                                                                    echo ($_POST['standing'] == 'SHS') ? "checked" : "";
                                                                                                } ?> required>
                <label>SHS</label>
            </div>
        </form>

        <?php
        if (isset($_POST['standing']) || isset($_POST['sub1'])) {
            $standing = $_POST['standing'];
        ?>
            <form action="" method="post" class="side-by-side">
                <div>
                    <input type="hidden" name="standing" id="" value="<?php echo $_POST['standing'] ?>">
                    <label><?php echo isset($_POST['standing']) && $_POST['standing'] == 'College' ? 'Program' : 'Strand'; ?></label><br>

                    <input type="text" name="CorS" id="" list="classes-list" required value="<?php if (isset($_POST['CorS']))
                                                                                                    echo $_POST['CorS']; ?>"><br>
                    <datalist id="classes-list">
                        <?php
                        $fetchclasses = $con->query("SELECT DISTINCT class_courseStrand FROM class_tb WHERE class_standing = '$standing'");
                        while ($row = $fetchclasses->fetch_assoc()) {
                        ?>
                            <option value="<?php echo $row['class_courseStrand'] ?>"></option>
                        <?php
                        }
                        $fetchclasses->free_result();
                        ?>
                    </datalist>
                </div>

                <div>
                    <label>Year Level</label><br>

                    <select name="YrLvl" id="level" required>
                        <option value="" selected disabled></option>
                        <?php

                        if ($standing === 'College') {
                            $options = ['1', '2', '3', '4']; // College levels
                        } else {
                            $options = ['11', '12']; // SHS levels
                        }


                        foreach ($options as $option) {
                            // Check if the option should be selected based on POST data
                            $selected = isset($_POST['YrLvl']) && $_POST['YrLvl'] === $option ? 'selected' : '';

                            echo '<option value="' . $option . '" ' . $selected . '>' . $option . '</option>';
                        }
                        ?>
                    </select>
                    <!-- <datalist id="level-list">
                        <?php
                        // $fetchclasses = $con->query("SELECT DISTINCT class_year FROM class_tb WHERE class_standing = '$standing'");
                        // while ($row = $fetchclasses->fetch_assoc()) {
                        // 
                        ?>
                        //     <option value="<?php //echo $row['class_year'] 
                                                ?>"></option>
                        // <?php
                            // }
                            // $fetchclasses->free_result();
                            ?>
                    </datalist> -->
                    <!-- <input type="number" name="YrLvl" id="level" list="level-list" required value="<?php //if (isset($_POST['YrLvl'])) echo $_POST['YrLvl']; 
                                                                                                        ?>"> -->

                    <button type="submit" name="sub1">Next</button><br>
                </div>
            </form>
        <?php
        }
        ?>

        <?php
        if (isset($_POST['sub1'])) {
            $standing = $_POST['standing'];
        ?>
            <form action="?action=createClass" method="post" class="side-by-side">
                <input type="hidden" name="standing" id="" value="<?php echo $_POST['standing']; ?>">
                <input type="hidden" name="CorS" id="" value="<?php echo $_POST['CorS']; ?>">
                <input type="hidden" name="YrLvl" id="" value="<?php echo $_POST['YrLvl']; ?>">
                <div>
                    <label>Section</label><br>
                    <input type="text" name="section" id="" list="section-list" required><br>
                    <datalist id="section-list">
                        <?php
                        $fetchclasses = $con->query("SELECT DISTINCT class_section FROM class_tb WHERE class_standing = '$standing'");
                        while ($row = $fetchclasses->fetch_assoc()) {
                        ?>
                            <option value="<?php echo $row['class_section'] ?>"></option>
                        <?php
                        }
                        $fetchclasses->free_result();
                        ?>
                    </datalist>
                </div>

                <div>
                    <label>Department</label>
                    <datalist id="department-list">
                        <?php
                        // $fetchdept = $con->prepare("SELECT DISTINCT `class_department` FROM `class_tb` WHERE class_standing = '$standing'");
                        $fetchdept = $con->prepare("SELECT DISTINCT `department_name` FROM `department_tb`");
                        $fetchdept->execute();
                        $result = $fetchdept->get_result();
                        while ($row = $result->fetch_assoc()) {
                        ?>
                            <option value="<?php echo $row['department_name'] ?>">
                            </option>
                        <?php
                        }
                        $fetchdept->free_result();
                        ?>
                    </datalist><br>
                    <input type="text" name="SubDept" id="department-list" list="department-list" required>

                    <button type="submit" name="sub2">Add</button>
                </div>

            <?php
        }
            ?>

            </form>
    </div>

    <div class="list">
        <div class="text">
            <span>Class List</span>
        </div>
        <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search for classes...">
        <p class="guide">Please double-click on any cell to make edits.</p>

        <table id="roomTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Program</th>
                    <th>Year level</th>
                    <th>Section</th>
                    <th>Department</th>
                    <th>Standing</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $getClasses = $con->query("SELECT * FROM `class_tb`;");
                $i = 1;
                while ($row = $getClasses->fetch_assoc()) {
                ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td class="editable" data-userid="<?php echo $row['class_id']; ?>" data-field="class_courseStrand"><?php echo $row['class_courseStrand'] ?></td>
                        <td class="editable-dropdown" data-userid="<?php echo $row['class_id']; ?>" data-field="class_year" data-standing="<?php echo $row['class_standing']; ?>"><?php echo $row['class_year'] ?></td>
                        <td class="editable-dropdown" data-userid="<?php echo $row['class_id']; ?>" data-field="class_section"><?php echo $row['class_section'] ?></td>
                        <td class="editable-dropdown" data-userid="<?php echo $row['class_id']; ?>" data-field="class_department"><?php echo $row['class_department'] ?></td>
                        <td class="editable-dropdown" data-userid="<?php echo $row['class_id']; ?>" data-field="class_standing"><?php echo $row['class_standing'] ?></td>
                        <td><a href="?del=<?php echo $row['class_id']; ?>" onclick="return confirm('Are you sure you want to delete this item?')"><button>Delete</button></a></td>
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
        const yearLvlOptions = {
            'College': ['1', '2', '3', '4'],
            'SHS': ['11', '12']
        };
        const sectionOptions = ['A', 'B', 'C', 'D', 'E', 'F', 'G'];
        const departmentOptions = <?php echo json_encode($departments); ?>;
        const standingOptions = ['College', 'SHS'];

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

        document.querySelectorAll('.editable-dropdown').forEach(cell => {
            cell.addEventListener('dblclick', function() {
                if (!this.querySelector('select')) {
                    let originalValue = this.textContent.trim();
                    let userId = this.getAttribute('data-userid');
                    let field = this.getAttribute('data-field');
                    let standing = this.getAttribute('data-standing');
                    let select = document.createElement('select');

                    let options;
                    if (field === 'class_year') {
                        options = yearLvlOptions[standing] || [];
                    } else if (field === 'class_section') {
                        options = sectionOptions;
                    } else if (field === 'class_department') {
                        options = departmentOptions;
                    } else if (field === 'class_standing') {
                        options = standingOptions;
                    }

                    options.forEach(optionValue => {
                        let option = document.createElement('option');
                        option.value = optionValue;
                        option.text = optionValue;
                        option.selected = optionValue === originalValue;
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
if (isset($_GET['action']) && $_GET['action'] == 'createClass') {
    $CorS = $_POST['CorS'];
    $year = $_POST['YrLvl'];
    $section = $_POST['section'];
    $SubDept = $_POST['SubDept'];
    $standing = $_POST['standing'];

    $validateInput = $con->prepare("SELECT * FROM class_tb WHERE `class_courseStrand` = ? AND `class_year`= ? AND `class_section`= ? AND `class_department`= ? AND `class_standing` = ?");
    $validateInput->bind_param("sssss", $CorS, $year, $section, $SubDept, $standing);
    $validateInput->execute();
    $result = $validateInput->get_result();
    if ($result->num_rows > 0) {
        echo "<script>alert('Class already exists!')</script>";
    } else {
        $insertClass = $con->prepare("INSERT INTO class_tb(class_courseStrand, class_year, class_section, class_department, class_standing) VALUES (?, ?, ?, ?, ?)");
        $insertClass->bind_param("sssss", $CorS, $year, $section, $SubDept, $standing);
        if ($insertClass->execute()) {
            echo "<script>alert('Class record inserted successfully');window.location.href = 'admin-manage-class-list.php';</script>";
        } else {
            echo "<script>alert('Error inserting class')</script>";
        }
    }
}
