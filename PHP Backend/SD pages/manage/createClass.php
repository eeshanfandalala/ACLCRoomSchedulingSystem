<?php
// include '../../config.php'; 
?>
<div class="main-flex">

    <div class="create-new-form">
        <div class="text">
            <span>Create New Class</span>
        </div>
        <div id="form1">
            <form action="?action=createClass" method="post">
                <input type="radio" name="standing" value="College" <?php if (isset($_POST['standing']) || isset($_POST['sub1']) || isset($_POST['sub2'])) {
                    echo ($_POST['standing'] == 'College') ? "checked" : "";
                } ?> onchange="this.form.submit()" required><label for="">College</label>
                <input type="radio" name="standing" value="SHS" <?php if (isset($_POST['standing']) || isset($_POST['sub1']) || isset($_POST['sub2'])) {
                    echo ($_POST['standing'] == 'SHS') ? "checked" : "";
                } ?> onchange="this.form.submit()" required><label for="">SHS</label><br>

                <label>Course / Strand</label><br>
                <input type="text" name="CorS" id="" required value="<?php if (isset($_POST['CorS']))
                    echo $_POST['CorS']; ?>"><br>

                <label>Year Level</label><br>
                <input type="number" name="YrLvl" id="level" required value="<?php if (isset($_POST['YrLvl']))
                    echo $_POST['YrLvl']; ?>"><br>

                <button type="submit" name="sub1">Next</button><br><br>


                <label>Section Name</label><br>
                <input type="text" name="section" id="" required value="<?php if (isset($_POST['section']))
                    echo $_POST['section']; ?>"><br>

                <label>Department</label><br>
                <datalist id="department-list">
                    <?php
                    $fetchdept = $con->prepare("SELECT `department_name` FROM `department_tb`");
                    $fetchdept->execute();
                    $result = $fetchdept->get_result();
                    while ($row = $result->fetch_assoc()) {
                        ?>
                        <option value="<?php echo $row['department_name'] ?>"><?php echo $row['department_name'] ?>
                        </option>
                        <?php
                    }
                    $fetchdept->free_result();
                    ?>
                </datalist>
                <input type="text" name="SubDept" id="department-list" list="department-list" required><br>
                <button type="submit" name="sub2">Add</button>
            </form>
        </div>
    </div>
    
    <div class="list">
        <div class="text">
            <span>Class List</span>
        </div>
        <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search for classes...">
        <table id="roomTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Program</th>
                    <th>Year level</th>
                    <th>Section</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $getClasses = $con->query("SELECT `class_courseStrand`, `class_year`, `class_section` FROM `class_tb`;");
                $i = 1;
                while ($row = $getClasses->fetch_assoc()) {
                    ?>
                    <tr>
                        <td><?php echo $i ?></td>
                        <td><?php echo $row['class_courseStrand'] ?></td>
                        <td><?php echo $row['class_year'] ?></td>
                        <td><?php echo $row['class_section'] ?></td>
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
            echo "<script>alert('Class record inserted successfully')</script>";
        } else {
            echo "<script>alert('Error inserting class')</script>";
        }
    }
}
