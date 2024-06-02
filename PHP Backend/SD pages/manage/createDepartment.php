<div style="display:flex;">
    <div>
        <h2>Department List</h2>
        <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search for rooms..">
        <br><br>
        <table id="roomTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Department Name</th>
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
                        <td><?php echo $row['department_name'] ?></td>

                    </tr>
                <?php
                    $i++;
                }
                ?>
            </tbody>
        </table>
    </div>
    <fieldset>
        <legend>Create New Department</legend>
        <form action="?action=createDepartment" method="post">
            <label for="">Department name</label>
            <input type="text" name="deptName" id="" required>
            <input type="submit" name="" id="">
        </form>
    </fieldset>
</div>

<script src="searchtable.js"></script>

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
            echo "<script>alert('Department created successfully');</script>";
        } else {
            echo "<script>alert('Error creating department');</script>";
        }
        $insertDept->close();
    }

    $checkIfExist->close();
    $con->close();
}
?>