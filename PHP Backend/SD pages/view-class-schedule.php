<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div class="main">
        <div class="filter-container">
            <form action="?selectedClass" method="post">
                <div>
                    <label>School Year</label><br>
                    <select name="AY" id="yearSelect" onchange="this.form.submit()">
                        <?php
                        include 'config.php';
                        $currentYear = date("Y");
                        for ($i = -1; $i < 4; $i++) {
                            $year = $currentYear + $i;
                            $nextYear = $year + 1;
                            $optionValue = $year . "-" . $nextYear;
                        ?>
                            <option value="<?php echo $optionValue ?>" <?php if (isset($_GET['selectedRoom'])) {
                                                                            echo ($_POST['AY'] == "$optionValue") ? "selected" : "";
                                                                        } ?> required><?php echo $optionValue ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>

                <div>
                    <label>Semester</label><br>
                    <input type="radio" name="SetSem" id="firstSemester" value="1st" style="margin-top: 10px" onchange="this.form.submit()" <?php if (isset($_POST['SetSem'])) {
                                                                                                                                                echo ($_POST['SetSem'] == '1st') ? "checked" : "";
                                                                                                                                            } else {
                                                                                                                                                echo "checked"; // checked by default
                                                                                                                                            } ?> required>

                    <label>1st</label>
                    <input type="radio" name="SetSem" id="secondSemester" value="2nd" onchange="this.form.submit()" <?php if (isset($_POST['SetSem'])) {
                                                                                                                        echo ($_POST['SetSem'] == '2nd') ? "checked" : "";
                                                                                                                    } ?> required>
                    <label>2nd</label>
                </div>

                <div>
                    <label>Class</label><br>
                    <select name="class" onchange="this.form.submit()">
                        <option>--Choose a class--</option>
                        <?php
                        $getClasses = $con->prepare("SELECT * FROM class_tb");
                        $getClasses->execute();
                        $resultGetClasses = $getClasses->get_result();
                        while ($rowGetClass = $resultGetClasses->fetch_assoc()) {
                            $completeClassName = $rowGetClass['class_courseStrand'] . ' ' . $rowGetClass['class_year'] . ' - ' . $rowGetClass['class_section'];

                        ?>
                            <option value="<?php echo $rowGetClass['class_id'] ?>" <?php if (isset($_GET['selectedClass'])) {
                                                                                        echo ($_POST['class'] == $rowGetClass['class_id']) ? "selected" : "";
                                                                                    } ?> required><?php echo $completeClassName ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>
            </form>

            <div style="margin-top: 20px">
                <button id="editButton">Enable Edit</button>
                <button id="deleteButton">Enable Delete</button>
                <button onclick="print()" class="print">Print</button>
            </div>
        </div>

        <!-- for printing -->
        <div class="print-info">
            <img src='media/ACLC-logo.png'><br>
            <p class='header'>ACLC College of Ormoc City, Inc.</p>
            <p class='header'>Lilia Avenue, Brgy. Cogon, Ormoc City</p><br><br>
            <div class="print-details">
                <strong>Semester: <span id="printSem"><?php echo isset($_POST['SetSem']) ? $_POST['SetSem'] : ''; ?></span></strong>
                <strong>School Year: <span id="printAY"><?php echo isset($_POST['AY']) ? $_POST['AY'] : ''; ?></span></strong>
                <strong>Class: <span id="printClass"><?php
                                                        if (isset($_POST['class'])) {
                                                            $classId = $_POST['class'];
                                                            $getClass = $con->prepare("SELECT class_courseStrand, class_year, class_section FROM class_tb WHERE class_id = ?");
                                                            $getClass->bind_param("i", $classId);
                                                            $getClass->execute();
                                                            $getClass->store_result();
                                                            $getClass->bind_result($class_courseStrand, $class_year, $class_section);
                                                            $getClass->fetch();
                                                            echo $class_courseStrand . ' ' . $class_year . ' - ' . $class_section;
                                                        }
                                                        ?></span></strong>
            </div>
        </div>

        <div class="list">
            <?php
            if (isset($_GET['selectedClass'])) {
                $AY = $_POST['AY'];
                $SetSem = $_POST['SetSem'];
                $class = $_POST['class'];

                // $getRoomType = $con->prepare("SELECT room_type FROM room_tb WHERE room_id = ?");
                // $getRoomType->bind_param("s", $room);
                // $getRoomType->execute();
                // $getRoomType->bind_result($roomType);
                // $getRoomType->fetch();
                // $getRoomType->close();


                $fetchSchedule = $con->prepare("SELECT * FROM schedule_tb WHERE class_id = ? AND schedule_SY = ? AND schedule_semester = ?");
                $fetchSchedule->bind_param("iss", $class, $AY, $SetSem);
                $fetchSchedule->execute();
                $resultfetchSchedule = $fetchSchedule->get_result();
                function findCellValues($room_name, $teacher_name, $subject_name)
                {
                    $cellValue = $room_name . "<br>" . $teacher_name . "<br>" . $subject_name . "<br>";
                    return $cellValue;
                }
            ?>
                <table>
                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Monday</th>
                            <th>Tuesday</th>
                            <th>Wednesday</th>
                            <th>Thursday</th>
                            <th>Friday</th>
                            <th>Saturday</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $scheduleData = array();

                        while ($rowSchedule = $resultfetchSchedule->fetch_object()) {
                            $schedule_time = [
                                'start' => $rowSchedule->schedule_time_start,
                                'end' => $rowSchedule->schedule_time_end
                            ];
                            $schedule_day = $rowSchedule->schedule_day;


                            for ($i = strtotime($schedule_time['start']); $i < strtotime($schedule_time['end']); $i += 1800) {
                                $time_start = date('h:i A', $i);
                                $time_end = date('h:i A', $i + 1800);

                                $scheduleData[$schedule_day][$time_start] = $rowSchedule;
                            }
                        }

                        for ($i = strtotime('7:00 AM'); $i < strtotime('10:00 PM'); $i += 1800) {
                            $time_start = date('h:i A', $i);
                            $time_end = date('h:i A', $i + 1800);

                        ?>
                            <tr>
                                <td><?php echo $time_start . ' - ' . $time_end; ?></td>
                                <?php
                                foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day) {
                                    if (isset($scheduleData[$day][$time_start])) {
                                        $rowSchedule = $scheduleData[$day][$time_start];

                                        // Fetch the data for class
                                        $fetchRoom = $con->prepare("SELECT room_name, room_type FROM room_tb WHERE room_id = ?");
                                        $fetchRoom->bind_param("i", $rowSchedule->room_id);
                                        $fetchRoom->execute();
                                        $fetchRoom->store_result();
                                        $fetchRoom->bind_result($room_name, $roomType);
                                        $fetchRoom->fetch();

                                        // $nameFetchClassResult = $class_courseStrand . ' ' . $class_year . ' - ' . $class_section;

                                        // Fetch the data for teacher
                                        $fetchTeacher = $con->prepare("SELECT teacher_name FROM teacher_tb WHERE teacher_id = ?");
                                        $fetchTeacher->bind_param("i", $rowSchedule->teacher_id);
                                        $fetchTeacher->execute();
                                        $fetchTeacher->store_result();
                                        $fetchTeacher->bind_result($teacher_name);
                                        $fetchTeacher->fetch();

                                        // Fetch the data for subject
                                        $fetchSubjects = $con->prepare("SELECT subject_name, subject_description FROM subject_tb WHERE subject_id = ?");
                                        $fetchSubjects->bind_param("i", $rowSchedule->subject_id);
                                        $fetchSubjects->execute();
                                        $fetchSubjects->store_result();
                                        $fetchSubjects->bind_result($subject_name, $subject_description);
                                        $fetchSubjects->fetch();

                                        $schedID = $rowSchedule->schedule_id;

                                        echo "<td><a href='./PHP Backend/SD pages/actionClass.php?schedID=" . urlencode($schedID) . "&roomType=" . urlencode($roomType) . "' class='disabled-link' data-schedid='$schedID' data-roomID='$roomType'>" . findCellValues($room_name, $teacher_name, $subject_name) . "</a></td>";
                                    } else {
                                        // If empty
                                        echo '<td></td>';
                                    }
                                }
                                ?>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            <?php
            }
            ?>
        </div>
    </div>

    <script>
        document.getElementById('editButton').addEventListener('click', function() {
            var links = document.querySelectorAll('td a');
            var enable = this.textContent === 'Enable Edit';

            links.forEach(function(link) {
                if (enable) {
                    link.classList.remove('disabled-link');
                    link.href = './PHP Backend/SD pages/actionClass.php?EditschedID=' + link.getAttribute('data-schedid') + '&roomType=' + link.getAttribute('data-roomID');
                } else {
                    link.classList.add('disabled-link');
                    link.removeAttribute('href');
                }
            });

            this.textContent = enable ? 'Disable Edit' : 'Enable Edit';
        });

        document.getElementById('deleteButton').addEventListener('click', function() {
            var links = document.querySelectorAll('td a');
            var enable = this.textContent === 'Enable Delete';

            links.forEach(function(link) {
                if (enable) {
                    link.classList.remove('disabled-link');
                    link.href = './PHP Backend/SD pages/actionClass.php?DelschedID=' + link.getAttribute('data-schedid');
                } else {
                    link.classList.add('disabled-link');
                    link.removeAttribute('href');
                }
            });

            this.textContent = enable ? 'Disable Delete' : 'Enable Delete';
        });
    </script>

</body>

</html>