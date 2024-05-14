<?php
include '../ACLC-College-of-Ormoc-Classroom-Scheduling-System/config.php';

// SQL statement for Class table
$fetchclass = $con->prepare("SELECT * FROM class_tb");
$fetchclass->execute();
$fetchclass = $fetchclass->get_result();
?>
<form action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
    <select name="AY" id="yearSelect" onload="generateYearOptions()"></select>
    <label for="firstSemester">Set Semester:</label>
    <input type="radio" name="SetSem" id="firstSemester" value="1st" required>
    <label for="firstSemester">1st</label>
    <input type="radio" name="SetSem" id="secondSemester" value="2nd" required>
    <label for="secondSemester">2nd</label>

    <select name="class" id="">
        <?php
        while ($row = mysqli_fetch_array($fetchclass)) {
            $classID = $row['class_id'];
            $courseStrand = $row['class_courseStrand'];
            $year = $row['class_year'];
            $section = $row['class_section'];
            // $departmentUnder = $row['class_department'];

            $className = $courseStrand . $year . '-' . $section;
            $classID_name = $classID . '|' . $className;
            $classID_name = htmlspecialchars($classID_name);

            echo "<option value='$classID_name'>$className</option>";
        }
        ?>
    </select>
    <input type="submit" name="subprocs">
</form>
<?php

function processSchedule($insertSched, $con)
{

    $jsondataTime = json_encode($insertSched['schedule_time']);
    $jsondataDay = json_encode($insertSched['schedule_day']);
    $schedule_semester = $insertSched['schedule_semester'];
    $schedule_SY = $insertSched['schedule_SY'];
    $teacher_id = $insertSched['schedule_teacher'];
    $class_id = $insertSched['schedule_class'];
    $subject_id = $insertSched['schedule_subject'];
    $room_id = $insertSched['schedule_room'];

    $insertSchedToDatabse = $con->prepare("INSERT INTO schedule_tb(schedule_time, schedule_day, schedule_semester, schedule_SY, teacher_id, class_id, subject_id, room_id) VALUES (?,?,?,?,?,?,?,?)");
    $insertSchedToDatabse->bind_param("ssssiiii", $jsondataTime, $jsondataDay, $schedule_semester, $schedule_SY, $teacher_id, $class_id, $subject_id, $room_id);
    $insertSchedToDatabse->execute();
}

if (isset($_POST['subprocs'])) {

    $classID_name = $_POST['class'];
    list($classID, $className) = explode('|', $classID_name);
    $classID = htmlspecialchars($classID);
    $className = htmlspecialchars($className);

    $year = $_POST['AY'];
    $setSem = $_POST['SetSem'];

    // Prepare the SQL statement for fetching subjects
    $stmtSubjects = $con->prepare("SELECT `subject_id`, `subject_name`, `subject_description` FROM `subject_tb` WHERE `subject_department` = (SELECT class_department FROM class_tb WHERE class_id = ?) OR `subject_department` = 'General'");
    $stmtSubjects->bind_param("i", $classID);
    $stmtSubjects->execute();
    $resultSubjects = $stmtSubjects->get_result();

    // Prepare the SQL statement for fetching teachers
    $stmtTeachers = $con->prepare("SELECT `teacher_id`, `teacher_name`, `teacher_department` FROM `teacher_tb` WHERE `teacher_department` = (SELECT class_department FROM class_tb WHERE class_id = ?) OR `teacher_department` = 'General'");
    $stmtTeachers->bind_param("i", $classID);
    $stmtTeachers->execute();
    $resultTeachers = $stmtTeachers->get_result();

    // Prepare the SQL statement for fetching rooms
    $stmtRooms = $con->prepare("SELECT room_id, room_name, room_type FROM room_tb");
    $stmtRooms->execute();
    $resultRooms = $stmtRooms->get_result();
?>

    <table>
        <caption><?php echo $year . " " . $setSem . " Semester " . $className; ?></caption>
        <thead>
            <tr>
                <th>Subject Code</th>
                <th>Time</th>
                <th>Room</th>
                <th>Teacher</th>
                <th>Days</th>
                <th>Action</th>
            </tr>
        </thead>
        <form action="" method="post">
            <input type="hidden" name="setSem" value="<?php echo $setSem ?>" id="">
            <input type="hidden" name="year" value="<?php echo $year ?>" id="">
            <input type="hidden" name="classID" value="<?php echo $classID ?>" id="">
            <tbody>
                <!--FIRST ROW-->
                <tr>
                    <td>
                        <select name="schedule_subject" id="">
                            <?php
                            $resultSubjects->data_seek(0);
                            while ($rowSubjects = $resultSubjects->fetch_assoc()) {
                                $subject_id = $rowSubjects['subject_id'];
                                $subject_name = $rowSubjects['subject_name'];
                                $subject_description = $rowSubjects['subject_description'];

                            ?>
                                <option value="<?php echo $subject_id ?>"><?php echo $subject_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <label for="">Start: </label><input type="time" name="schedule_time[start]" min="07:00" max="22:00"><br>
                        <label for="">End: </label><input type="time" name="schedule_time[end]" min="07:00" max="22:00">
                    </td>
                    <td>
                        <select name="schedule_room" id="">
                            <?php
                            while ($rowRooms = $resultRooms->fetch_assoc()) {
                                $room_id = $rowRooms['room_id'];
                                $room_name = $rowRooms['room_name'];
                                $room_type = $rowRooms['room_type'];
                            ?>
                                <option value="<?php echo $room_id ?>"><?php echo $room_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <select name="schedule_teacher" id="">
                            <?php
                            while ($rowTeacher = $resultTeachers->fetch_assoc()) {
                                $teacher_id = $rowTeacher['teacher_id'];
                                $teacher_name = $rowTeacher['teacher_name'];
                                $teacher_department = $rowTeacher['teacher_department'];
                            ?>
                                <option value="<?php echo $teacher_id ?>"><?php echo $teacher_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <input type="checkbox" name="days[]" value="Monday">Monday<br>
                        <input type="checkbox" name="days[]" value="Tuesday">Tuesday<br>
                        <input type="checkbox" name="days[]" value="Wednesday">Wednesday<br>
                        <input type="checkbox" name="days[]" value="Thursday">Thursday<br>
                        <input type="checkbox" name="days[]" value="Friday">Friday<br>
                        <input type="checkbox" name="days[]" value="Saturday">Saturday<br>
                    </td>
                    <td>
                        <input type="checkbox" name="row1" id=""> <label for="">Add</label>
                    </td>
                </tr>
                <!--SENCOND ROW-->
                <tr>
                    <td>
                        <select name="schedule_subject2" id="">
                            <?php
                            $resultSubjects->data_seek(0);

                            while ($rowSubjects = $resultSubjects->fetch_assoc()) {
                                $subject_id = $rowSubjects['subject_id'];
                                $subject_name = $rowSubjects['subject_name'];
                                $subject_description = $rowSubjects['subject_description'];

                            ?>
                                <option value="<?php echo $subject_id ?>"><?php echo $subject_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <label for="">Start: </label><input type="time" name="schedule_time2[start]" min="07:00" max="22:00"><br>
                        <label for="">End: </label><input type="time" name="schedule_time2[end]" min="07:00" max="22:00">
                    </td>
                    <td>
                        <select name="schedule_room2" id="">
                            <?php
                            $resultRooms->data_seek(0);
                            while ($rowRooms = $resultRooms->fetch_assoc()) {
                                $room_id = $rowRooms['room_id'];
                                $room_name = $rowRooms['room_name'];
                                $room_type = $rowRooms['room_type'];
                            ?>
                                <option value="<?php echo $room_id ?>"><?php echo $room_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <select name="schedule_teacher2" id="">
                            <?php
                            $resultTeachers->data_seek(0);
                            while ($rowTeacher = $resultTeachers->fetch_assoc()) {
                                $teacher_id = $rowTeacher['teacher_id'];
                                $teacher_name = $rowTeacher['teacher_name'];
                                $teacher_department = $rowTeacher['teacher_department'];
                            ?>
                                <option value="<?php echo $teacher_id ?>"><?php echo $teacher_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <input type="checkbox" name="days2[]" value="Monday">Monday<br>
                        <input type="checkbox" name="days2[]" value="Tuesday">Tuesday<br>
                        <input type="checkbox" name="days2[]" value="Wednesday">Wednesday<br>
                        <input type="checkbox" name="days2[]" value="Thursday">Thursday<br>
                        <input type="checkbox" name="days2[]" value="Friday">Friday<br>
                        <input type="checkbox" name="days2[]" value="Saturday">Saturday<br>
                    </td>
                    <td>
                        <input type="checkbox" name="row2" id=""> <label for="">Add</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <select name="schedule_subject3" id="">
                            <?php
                            $resultSubjects->data_seek(0);

                            while ($rowSubjects = $resultSubjects->fetch_assoc()) {
                                $subject_id = $rowSubjects['subject_id'];
                                $subject_name = $rowSubjects['subject_name'];
                                $subject_description = $rowSubjects['subject_description'];

                            ?>
                                <option value="<?php echo $subject_id ?>"><?php echo $subject_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <label for="">Start: </label><input type="time" name="schedule_time3[start]" min="07:00" max="22:00"><br>
                        <label for="">End: </label><input type="time" name="schedule_time3[end]" min="07:00" max="22:00">
                    </td>
                    <td>
                        <select name="schedule_room3" id="">
                            <?php
                            $resultRooms->data_seek(0);
                            while ($rowRooms = $resultRooms->fetch_assoc()) {
                                $room_id = $rowRooms['room_id'];
                                $room_name = $rowRooms['room_name'];
                                $room_type = $rowRooms['room_type'];
                            ?>
                                <option value="<?php echo $room_id ?>"><?php echo $room_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <select name="schedule_teacher3" id="">
                            <?php
                            $resultTeachers->data_seek(0);
                            while ($rowTeacher = $resultTeachers->fetch_assoc()) {
                                $teacher_id = $rowTeacher['teacher_id'];
                                $teacher_name = $rowTeacher['teacher_name'];
                                $teacher_department = $rowTeacher['teacher_department'];
                            ?>
                                <option value="<?php echo $teacher_id ?>"><?php echo $teacher_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <input type="checkbox" name="days3[]" value="Monday">Monday<br>
                        <input type="checkbox" name="days3[]" value="Tuesday">Tuesday<br>
                        <input type="checkbox" name="days3[]" value="Wednesday">Wednesday<br>
                        <input type="checkbox" name="days3[]" value="Thursday">Thursday<br>
                        <input type="checkbox" name="days3[]" value="Friday">Friday<br>
                        <input type="checkbox" name="days3[]" value="Saturday">Saturday<br>
                    </td>
                    <td>
                        <input type="checkbox" name="row3" id=""> <label for="">Add</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <select name="schedule_subject4" id="">
                            <?php
                            $resultSubjects->data_seek(0);

                            while ($rowSubjects = $resultSubjects->fetch_assoc()) {
                                $subject_id = $rowSubjects['subject_id'];
                                $subject_name = $rowSubjects['subject_name'];
                                $subject_description = $rowSubjects['subject_description'];

                            ?>
                                <option value="<?php echo $subject_id ?>"><?php echo $subject_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <label for="">Start: </label><input type="time" name="schedule_time4[start]" min="07:00" max="22:00"><br>
                        <label for="">End: </label><input type="time" name="schedule_time4[end]" min="07:00" max="22:00">
                    </td>
                    <td>
                        <select name="schedule_room4" id="">
                            <?php
                            $resultRooms->data_seek(0);
                            while ($rowRooms = $resultRooms->fetch_assoc()) {
                                $room_id = $rowRooms['room_id'];
                                $room_name = $rowRooms['room_name'];
                                $room_type = $rowRooms['room_type'];
                            ?>
                                <option value="<?php echo $room_id ?>"><?php echo $room_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <select name="schedule_teacher4" id="">
                            <?php
                            $resultTeachers->data_seek(0);
                            while ($rowTeacher = $resultTeachers->fetch_assoc()) {
                                $teacher_id = $rowTeacher['teacher_id'];
                                $teacher_name = $rowTeacher['teacher_name'];
                                $teacher_department = $rowTeacher['teacher_department'];
                            ?>
                                <option value="<?php echo $teacher_id ?>"><?php echo $teacher_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <input type="checkbox" name="days4[]" value="Monday">Monday<br>
                        <input type="checkbox" name="days4[]" value="Tuesday">Tuesday<br>
                        <input type="checkbox" name="days4[]" value="Wednesday">Wednesday<br>
                        <input type="checkbox" name="days4[]" value="Thursday">Thursday<br>
                        <input type="checkbox" name="days4[]" value="Friday">Friday<br>
                        <input type="checkbox" name="days4[]" value="Saturday">Saturday<br>
                    </td>
                    <td>
                        <input type="checkbox" name="row4" id=""> <label for="">Add</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <select name="schedule_subject5" id="">
                            <?php
                            $resultSubjects->data_seek(0);

                            while ($rowSubjects = $resultSubjects->fetch_assoc()) {
                                $subject_id = $rowSubjects['subject_id'];
                                $subject_name = $rowSubjects['subject_name'];
                                $subject_description = $rowSubjects['subject_description'];

                            ?>
                                <option value="<?php echo $subject_id ?>"><?php echo $subject_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <label for="">Start: </label><input type="time" name="schedule_time5[start]" min="07:00" max="22:00"><br>
                        <label for="">End: </label><input type="time" name="schedule_time5[end]" min="07:00" max="22:00">
                    </td>
                    <td>
                        <select name="schedule_room5" id="">
                            <?php
                            $resultRooms->data_seek(0);
                            while ($rowRooms = $resultRooms->fetch_assoc()) {
                                $room_id = $rowRooms['room_id'];
                                $room_name = $rowRooms['room_name'];
                                $room_type = $rowRooms['room_type'];
                            ?>
                                <option value="<?php echo $room_id ?>"><?php echo $room_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <select name="schedule_teacher5" id="">
                            <?php
                            $resultTeachers->data_seek(0);
                            while ($rowTeacher = $resultTeachers->fetch_assoc()) {
                                $teacher_id = $rowTeacher['teacher_id'];
                                $teacher_name = $rowTeacher['teacher_name'];
                                $teacher_department = $rowTeacher['teacher_department'];
                            ?>
                                <option value="<?php echo $teacher_id ?>"><?php echo $teacher_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <input type="checkbox" name="days5[]" value="Monday">Monday<br>
                        <input type="checkbox" name="days5[]" value="Tuesday">Tuesday<br>
                        <input type="checkbox" name="days5[]" value="Wednesday">Wednesday<br>
                        <input type="checkbox" name="days5[]" value="Thursday">Thursday<br>
                        <input type="checkbox" name="days5[]" value="Friday">Friday<br>
                        <input type="checkbox" name="days5[]" value="Saturday">Saturday<br>
                    </td>
                    <td>
                        <input type="checkbox" name="row5" id=""> <label for="">Add</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <select name="schedule_subject6" id="">
                            <?php
                            $resultSubjects->data_seek(0);

                            while ($rowSubjects = $resultSubjects->fetch_assoc()) {
                                $subject_id = $rowSubjects['subject_id'];
                                $subject_name = $rowSubjects['subject_name'];
                                $subject_description = $rowSubjects['subject_description'];

                            ?>
                                <option value="<?php echo $subject_id ?>"><?php echo $subject_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <label for="">Start: </label><input type="time" name="schedule_time6[start]" min="07:00" max="22:00"><br>
                        <label for="">End: </label><input type="time" name="schedule_time6[end]" min="07:00" max="22:00">
                    </td>
                    <td>
                        <select name="schedule_room6" id="">
                            <?php
                            $resultRooms->data_seek(0);
                            while ($rowRooms = $resultRooms->fetch_assoc()) {
                                $room_id = $rowRooms['room_id'];
                                $room_name = $rowRooms['room_name'];
                                $room_type = $rowRooms['room_type'];
                            ?>
                                <option value="<?php echo $room_id ?>"><?php echo $room_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <select name="schedule_teacher6" id="">
                            <?php
                            $resultTeachers->data_seek(0);
                            while ($rowTeacher = $resultTeachers->fetch_assoc()) {
                                $teacher_id = $rowTeacher['teacher_id'];
                                $teacher_name = $rowTeacher['teacher_name'];
                                $teacher_department = $rowTeacher['teacher_department'];
                            ?>
                                <option value="<?php echo $teacher_id ?>"><?php echo $teacher_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <input type="checkbox" name="days6[]" value="Monday">Monday<br>
                        <input type="checkbox" name="days6[]" value="Tuesday">Tuesday<br>
                        <input type="checkbox" name="days6[]" value="Wednesday">Wednesday<br>
                        <input type="checkbox" name="days6[]" value="Thursday">Thursday<br>
                        <input type="checkbox" name="days6[]" value="Friday">Friday<br>
                        <input type="checkbox" name="days6[]" value="Saturday">Saturday<br>
                    </td>
                    <td>
                        <input type="checkbox" name="row6" id=""> <label for="">Add</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <select name="schedule_subject7" id="">
                            <?php
                            $resultSubjects->data_seek(0);

                            while ($rowSubjects = $resultSubjects->fetch_assoc()) {
                                $subject_id = $rowSubjects['subject_id'];
                                $subject_name = $rowSubjects['subject_name'];
                                $subject_description = $rowSubjects['subject_description'];

                            ?>
                                <option value="<?php echo $subject_id ?>"><?php echo $subject_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <label for="">Start: </label><input type="time" name="schedule_time7[start]" min="07:00" max="22:00"><br>
                        <label for="">End: </label><input type="time" name="schedule_time7[end]" min="07:00" max="22:00">
                    </td>
                    <td>
                        <select name="schedule_room7" id="">
                            <?php
                            $resultRooms->data_seek(0);
                            while ($rowRooms = $resultRooms->fetch_assoc()) {
                                $room_id = $rowRooms['room_id'];
                                $room_name = $rowRooms['room_name'];
                                $room_type = $rowRooms['room_type'];
                            ?>
                                <option value="<?php echo $room_id ?>"><?php echo $room_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <select name="schedule_teacher7" id="">
                            <?php
                            $resultTeachers->data_seek(0);
                            while ($rowTeacher = $resultTeachers->fetch_assoc()) {
                                $teacher_id = $rowTeacher['teacher_id'];
                                $teacher_name = $rowTeacher['teacher_name'];
                                $teacher_department = $rowTeacher['teacher_department'];
                            ?>
                                <option value="<?php echo $teacher_id ?>"><?php echo $teacher_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <input type="checkbox" name="days7[]" value="Monday">Monday<br>
                        <input type="checkbox" name="days7[]" value="Tuesday">Tuesday<br>
                        <input type="checkbox" name="days7[]" value="Wednesday">Wednesday<br>
                        <input type="checkbox" name="days7[]" value="Thursday">Thursday<br>
                        <input type="checkbox" name="days7[]" value="Friday">Friday<br>
                        <input type="checkbox" name="days7[]" value="Saturday">Saturday<br>
                    </td>
                    <td>
                        <input type="checkbox" name="row7" id=""> <label for="">Add</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <select name="schedule_subject8" id="">
                            <?php
                            $resultSubjects->data_seek(0);

                            while ($rowSubjects = $resultSubjects->fetch_assoc()) {
                                $subject_id = $rowSubjects['subject_id'];
                                $subject_name = $rowSubjects['subject_name'];
                                $subject_description = $rowSubjects['subject_description'];

                            ?>
                                <option value="<?php echo $subject_id ?>"><?php echo $subject_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <label for="">Start: </label><input type="time" name="schedule_time8[start]" min="07:00" max="22:00"><br>
                        <label for="">End: </label><input type="time" name="schedule_time8[end]" min="07:00" max="22:00">
                    </td>
                    <td>
                        <select name="schedule_room8" id="">
                            <?php
                            $resultRooms->data_seek(0);
                            while ($rowRooms = $resultRooms->fetch_assoc()) {
                                $room_id = $rowRooms['room_id'];
                                $room_name = $rowRooms['room_name'];
                                $room_type = $rowRooms['room_type'];
                            ?>
                                <option value="<?php echo $room_id ?>"><?php echo $room_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <select name="schedule_teacher8" id="">
                            <?php
                            $resultTeachers->data_seek(0);
                            while ($rowTeacher = $resultTeachers->fetch_assoc()) {
                                $teacher_id = $rowTeacher['teacher_id'];
                                $teacher_name = $rowTeacher['teacher_name'];
                                $teacher_department = $rowTeacher['teacher_department'];
                            ?>
                                <option value="<?php echo $teacher_id ?>"><?php echo $teacher_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <input type="checkbox" name="days8[]" value="Monday">Monday<br>
                        <input type="checkbox" name="days8[]" value="Tuesday">Tuesday<br>
                        <input type="checkbox" name="days8[]" value="Wednesday">Wednesday<br>
                        <input type="checkbox" name="days8[]" value="Thursday">Thursday<br>
                        <input type="checkbox" name="days8[]" value="Friday">Friday<br>
                        <input type="checkbox" name="days8[]" value="Saturday">Saturday<br>
                    </td>
                    <td>
                        <input type="checkbox" name="row8" id=""> <label for="">Add</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <select name="schedule_subject9" id="">
                            <?php
                            $resultSubjects->data_seek(0);

                            while ($rowSubjects = $resultSubjects->fetch_assoc()) {
                                $subject_id = $rowSubjects['subject_id'];
                                $subject_name = $rowSubjects['subject_name'];
                                $subject_description = $rowSubjects['subject_description'];

                            ?>
                                <option value="<?php echo $subject_id ?>"><?php echo $subject_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <label for="">Start: </label><input type="time" name="schedule_time9[start]" min="07:00" max="22:00"><br>
                        <label for="">End: </label><input type="time" name="schedule_time9[end]" min="07:00" max="22:00">
                    </td>
                    <td>
                        <select name="schedule_room9" id="">
                            <?php
                            $resultRooms->data_seek(0);
                            while ($rowRooms = $resultRooms->fetch_assoc()) {
                                $room_id = $rowRooms['room_id'];
                                $room_name = $rowRooms['room_name'];
                                $room_type = $rowRooms['room_type'];
                            ?>
                                <option value="<?php echo $room_id ?>"><?php echo $room_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <select name="schedule_teacher9" id="">
                            <?php
                            $resultTeachers->data_seek(0);
                            while ($rowTeacher = $resultTeachers->fetch_assoc()) {
                                $teacher_id = $rowTeacher['teacher_id'];
                                $teacher_name = $rowTeacher['teacher_name'];
                                $teacher_department = $rowTeacher['teacher_department'];
                            ?>
                                <option value="<?php echo $teacher_id ?>"><?php echo $teacher_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <input type="checkbox" name="days9[]" value="Monday">Monday<br>
                        <input type="checkbox" name="days9[]" value="Tuesday">Tuesday<br>
                        <input type="checkbox" name="days9[]" value="Wednesday">Wednesday<br>
                        <input type="checkbox" name="days9[]" value="Thursday">Thursday<br>
                        <input type="checkbox" name="days9[]" value="Friday">Friday<br>
                        <input type="checkbox" name="days9[]" value="Saturday">Saturday<br>
                    </td>
                    <td>
                        <input type="checkbox" name="row9" id=""> <label for="">Add</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <select name="schedule_subject10" id="">
                            <?php
                            $resultSubjects->data_seek(0);

                            while ($rowSubjects = $resultSubjects->fetch_assoc()) {
                                $subject_id = $rowSubjects['subject_id'];
                                $subject_name = $rowSubjects['subject_name'];
                                $subject_description = $rowSubjects['subject_description'];

                            ?>
                                <option value="<?php echo $subject_id ?>"><?php echo $subject_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <label for="">Start: </label><input type="time" name="schedule_time10[start]" min="07:00" max="22:00"><br>
                        <label for="">End: </label><input type="time" name="schedule_time10[end]" min="07:00" max="22:00">
                    </td>
                    <td>
                        <select name="schedule_room10" id="">
                            <?php
                            $resultRooms->data_seek(0);
                            while ($rowRooms = $resultRooms->fetch_assoc()) {
                                $room_id = $rowRooms['room_id'];
                                $room_name = $rowRooms['room_name'];
                                $room_type = $rowRooms['room_type'];
                            ?>
                                <option value="<?php echo $room_id ?>"><?php echo $room_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <select name="schedule_teacher10" id="">
                            <?php
                            $resultTeachers->data_seek(0);
                            while ($rowTeacher = $resultTeachers->fetch_assoc()) {
                                $teacher_id = $rowTeacher['teacher_id'];
                                $teacher_name = $rowTeacher['teacher_name'];
                                $teacher_department = $rowTeacher['teacher_department'];
                            ?>
                                <option value="<?php echo $teacher_id ?>"><?php echo $teacher_name ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <input type="checkbox" name="days10[]" value="Monday">Monday<br>
                        <input type="checkbox" name="days10[]" value="Tuesday">Tuesday<br>
                        <input type="checkbox" name="days10[]" value="Wednesday">Wednesday<br>
                        <input type="checkbox" name="days10[]" value="Thursday">Thursday<br>
                        <input type="checkbox" name="days10[]" value="Friday">Friday<br>
                        <input type="checkbox" name="days10[]" value="Saturday">Saturday<br>
                    </td>
                    <td>
                        <input type="checkbox" name="row10" id=""> <label for="">Add</label>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><input type="submit" name="suball" id=""></td>
                </tr>
            </tfoot>
        </form>
    </table>


<?php
}

if (isset($_POST['row1'])) {
    $insertSched = array(
        "schedule_time" => array(
            "start" => "",
            "end" => ""
        ),
        "schedule_day" => [],
        "schedule_semester" => "",
        "schedule_SY" => "",
        "schedule_room" => 0,
        "schedule_subject" => 0,
        "schedule_teacher" => 0,
        "schedule_class" => 0,
    );

    $insertSched['schedule_semester'] = $_POST['setSem'];
    $insertSched['schedule_SY'] = $_POST['year'];
    $insertSched['schedule_class'] = $_POST['classID'];

    // Check if days[] checkboxes are submitted
    if (isset($_POST['days']) && is_array($_POST['days'])) {
        // Loop through the submitted days and add them to the schedule_day array
        foreach ($_POST['days'] as $day) {
            // Add the selected day to the schedule_day array
            $insertSched['schedule_day'][] = $day;
        }
    }

    $insertSched['schedule_time']['start'] = $_POST['schedule_time']['start'];
    $insertSched['schedule_time']['end'] = $_POST['schedule_time']['end'];

    $insertSched['schedule_subject'] = $_POST['schedule_subject'];
    $insertSched['schedule_teacher'] = $_POST['schedule_teacher'];
    $insertSched['schedule_room'] = $_POST['schedule_room'];


    processSchedule($insertSched, $con);
}
// Continue the logic for rows 2 to 10
for ($i = 2; $i <= 10; $i++) {
    if (isset($_POST['row' . $i])) {
        $insertSched = array(
            "schedule_time" => array(
                "start" => "",
                "end" => ""
            ),
            "schedule_day" => [],
            "schedule_semester" => "",
            "schedule_SY" => "",
            "schedule_room" => 0,
            "schedule_subject" => 0,
            "schedule_teacher" => 0,
            "schedule_class" => 0,
        );

        $insertSched['schedule_semester'] = $_POST['setSem'];
        $insertSched['schedule_SY'] = $_POST['year'];
        $insertSched['schedule_class'] = $_POST['classID'];

        // Check if days[] checkboxes are submitted
        if (isset($_POST['days' . $i]) && is_array($_POST['days' . $i])) {
            // Loop through the submitted days and add them to the schedule_day array
            foreach ($_POST['days' . $i] as $day) {
                // Add the selected day to the schedule_day array
                $insertSched['schedule_day'][] = $day;
            }
        }

        $insertSched['schedule_time']['start'] = $_POST['schedule_time' . $i]['start'];
        $insertSched['schedule_time']['end'] = $_POST['schedule_time' . $i]['end'];

        $insertSched['schedule_subject'] = $_POST['schedule_subject' . $i];
        $insertSched['schedule_teacher'] = $_POST['schedule_teacher' . $i];
        $insertSched['schedule_room'] = $_POST['schedule_room' . $i];

        processSchedule($insertSched, $con);
    }
}
if (isset($_POST['suball'])) {
    echo "<script>alert('Schedule added Successfuly'); window.location.href = '../../admin-create-class-schedule.php';";
}
?>