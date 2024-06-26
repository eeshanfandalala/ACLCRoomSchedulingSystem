<div class="main">
    <div class="top">
        <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search for teachers...">
    </div>

    <div class="bottom">
        <div class="text">
            <span>Activate Teachers</span>
        </div>

        <table id="roomTable">
            <thead>
                <tr>
                    <th>Teacher Name</th>
                    <th>Email</th>
                    <th>Number</th>
                    <th>Department</th>
                    <th>Proficiency</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // include('../../../config.php');
                // Update status if POST request is detected
                if (isset($_POST['status'])) {
                    $status = $_POST['status'];
                    $teacher_id = $_POST['teacher_id'];
                    $updateStatus = $con->prepare("UPDATE teacher_tb SET `status`=? WHERE teacher_id=?");
                    $updateStatus->bind_param("ii", $status, $teacher_id);
                    $updateStatus->execute();
                }

                $viewTeach = mysqli_query($con, "SELECT 
                t.teacher_id, 
                t.teacher_name, 
                t.teacher_email, 
                t.teacher_password, 
                t.teacher_number, 
                t.teacher_department, 
                d.department_name,  -- From department_tb
                t.teacher_proficency, 
                t.status, 
                t.teacher_pic, 
                t.SD_id
            FROM 
                teacher_tb t
            LEFT JOIN 
                department_tb d ON t.teacher_department = d.department_id;
            ");
                while ($row = mysqli_fetch_array($viewTeach)) {
                    $statusText = ($row['status'] == 0) ? "Not Active" : "Active";
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['teacher_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['teacher_email']); ?></td>
                        <td><?php echo htmlspecialchars($row['teacher_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['department_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['teacher_proficency']); ?></td>
                        <td>
                            <form action="" method="post">
                                <input type="hidden" name="teacher_id" value="<?php echo htmlspecialchars($row['teacher_id']); ?>">
                                <select name="status" onchange="this.form.submit()">
                                    <option value="1" <?php if ($row['status'] == 1)
                                                            echo 'selected'; ?>>Active</option>
                                    <option value="0" <?php if ($row['status'] == 0)
                                                            echo 'selected'; ?>>Not Active</option>
                                </select>
                            </form>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
</div>