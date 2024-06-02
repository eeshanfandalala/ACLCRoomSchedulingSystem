
    <div style="display: flex;">
        <div>
            <h2>Subject List</h2>
            <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search for rooms..">
            <br><br>
            <table id="roomTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Subject Name</th>
                        <th>Department</th>
                        <th>Type</th>
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
                            <td><?php echo $i ?></td>
                            <td><?php echo $row['subject_name'] ?></td>
                            <td><?php echo $row['subject_department'] ?></td>
                            <td><?php echo $row['subject_type'] ?></td>
                        </tr>
                    <?php
                        $i++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <fieldset>
            <legend>Create Subjects</legend>
            <form action="?action=createSubject" method="post">

                <label for="">Subject Name:</label>
                <input type="text" name="SubjectName" id="">



                <label for="">Subject Department</label>
                <select name="SubDept" id="">
                    <?php
                    $fetchdept = mysqli_query($con, "SELECT department_name FROM department_tb");
                    while ($row = mysqli_fetch_array($fetchdept)) {
                    ?>
                        <option value="<?php echo $row['department_name'] ?>"><?php echo $row['department_name'] ?></option>
                    <?php
                    }
                    ?>

                </select>
                <select name="roomType" id="">
                    <option value="Lecture">Lecture</option>
                    <option value="Laboratory">Laboratory</option>
                </select>

                <input type="submit">

            </form>
        </fieldset>
    </div>
    <script src="searchtable.js"></script>

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
            echo "<script>alert('Subject added');</script>";
        } else {
            echo "<script>alert('Error creating Department');</script>";
        }
    }
}
