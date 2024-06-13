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