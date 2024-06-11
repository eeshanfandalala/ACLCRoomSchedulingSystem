<div>
    <div>
        <button>Edit</button>
        <button>Delete</button>
        <button>Print</button>
    </div>
    <div>
        <div>
            <form action="?selectedRoom" method="post">
                <select name="AY" id="yearSelect">
                    <?php
                    // Get current year
                    $currentYear = date("Y");

                    // Generate options for the current year and the next 5 years
                    for ($i = -1; $i < 4; $i++) {
                        $year = $currentYear + $i;
                        $nextYear = $year + 1;
                        $optionValue = $year . "-" . $nextYear;
                        ?>
                        <option value=<?php echo $optionValue ?>     <?php if (isset($_GET['selectedRoom'])) {
                                    echo ($_POST['AY'] == "$optionValue") ? "selected" : "";
                                } ?> required><?php echo $optionValue ?>
                        </option>
                        <?php
                    }
                    ?>
                </select>
                <label for="firstSemester">Set Semester:</label>
                <input type="radio" name="SetSem" id="firstSemester" value="1st" <?php if (isset($_GET['selectedRoom'])) {
                    echo ($_POST['SetSem'] == '1st') ? "checked" : "";
                } ?> required>
                <label for="firstSemester">1st</label>
                <input type="radio" name="SetSem" id="secondSemester" value="2nd" <?php if (isset($_GET['selectedRoom'])) {
                    echo ($_POST['SetSem'] == '2nd') ? "checked" : "";
                } ?> required>
                <label for="secondSemester">2nd</label>
                <select name="room" id="">
                    <?php
                    $getRoom = $con->prepare("SELECT * FROM room_tb");
                    $getRoom->execute();
                    $resultGetRoom = $getRoom->get_result();
                    while ($rowGetRoom = $resultGetRoom->fetch_assoc()) {
                        ?>
                        <option value="<?php echo $rowGetRoom['room_id'] ?>" <?php if (isset($_GET['selectedRoom'])) {
                               echo ($_POST['room'] == $rowGetRoom['room_id']) ? "selected" : "";
                           } ?> required><?php echo $rowGetRoom['room_name'] ?></option>
                        <?php
                    }
                    ?>
                </select>
                <input type="submit">
            </form>
        </div>
        <div>
            <?php
            if (isset($_GET['selectedRoom'])) {
                $AY = $_POST['AY'];
                $SetSem = $_POST['SetSem'];
                $room = $_POST['room'];

                $fetchSchedule = $con->prepare("SELECT * FROM schedule_tb WHERE room_id = ? AND schedule_SY = ? AND schedule_semester = ?");
                $fetchSchedule->bind_param("iss", $room, $AY, $SetSem);
                $fetchSchedule->execute();
                $resultfetchSchedule = $fetchSchedule->get_result();

                // Define findCellValues function outside the loop
                function findCellValues($nameFetchClassResult, $teacher_name, $subject_name)
                {
                    $cellValue = $nameFetchClassResult . "<br>" . $teacher_name . "<br>" . $subject_name . "<br>";
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
                        // Initialize an array to store the schedule data
                        $scheduleData = array();

                        // Loop through each row of the schedule and organize the data by time and day
                        while ($rowSchedule = $resultfetchSchedule->fetch_object()) {
                            // Decode schedule_time and schedule_day
                            $schedule_time = json_decode($rowSchedule->schedule_time, true);
                            $schedule_day = json_decode($rowSchedule->schedule_day, true);

                            // Loop through each day in the schedule
                            foreach ($schedule_day as $day) {
                                // Loop through each half-hour interval for the current day
                                for ($i = strtotime($schedule_time['start']); $i < strtotime($schedule_time['end']); $i += 1800) {
                                    // Format the time in hh:mm format
                                    $time_start = date('h:i A', $i);
                                    $time_end = date('h:i A', $i + 1800);

                                    // Store the schedule data in the $scheduleData array
                                    $scheduleData[$day][$time_start] = $rowSchedule;
                                }
                            }
                        }

                        // Loop through each half-hour interval and display the schedule data
                        for ($i = strtotime('7:00 AM'); $i < strtotime('10:00 PM'); $i += 1800) {
                            // Format the time in hh:mm format
                            $time_start = date('h:i A', $i);
                            $time_end = date('h:i A', $i + 1800);

                            ?>
                            <tr>
                                <td><?php echo $time_start . ' - ' . $time_end; ?></td>
                                <?php
                                // Loop through each day of the week
                                foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day) {
                                    // Check if there is schedule data for the current day and time
                                    if (isset($scheduleData[$day][$time_start])) {
                                        // Fetch the schedule data for the current day and time
                                        $rowSchedule = $scheduleData[$day][$time_start];

                                        // Fetch the data for Class
                                        $fetchclass = $con->prepare("SELECT class_courseStrand, class_year, class_section FROM class_tb WHERE class_id = ?");
                                        $fetchclass->bind_param("i", $rowSchedule->class_id);
                                        $fetchclass->execute();
                                        $fetchclass->store_result();
                                        $fetchclass->bind_result($class_courseStrand, $class_year, $class_section);
                                        $fetchclass->fetch();

                                        $nameFetchClassResult = $class_courseStrand . ' ' . $class_year . ' - ' . $class_section;

                                        // Fetch the data for Teacher
                                        $fetchTeacher = $con->prepare("SELECT teacher_name FROM teacher_tb WHERE teacher_id = ?");
                                        $fetchTeacher->bind_param("i", $rowSchedule->teacher_id);
                                        $fetchTeacher->execute();
                                        $fetchTeacher->store_result();
                                        $fetchTeacher->bind_result($teacher_name);
                                        $fetchTeacher->fetch();

                                        // Fetch the data for Subject
                                        $fetchSubjects = $con->prepare("SELECT subject_name, subject_description FROM subject_tb WHERE subject_id = ?");
                                        $fetchSubjects->bind_param("i", $rowSchedule->subject_id);
                                        $fetchSubjects->execute();
                                        $fetchSubjects->store_result();
                                        $fetchSubjects->bind_result($subject_name, $subject_description);
                                        $fetchSubjects->fetch();

                                        // Call the findCellValues function to display the schedule data
                                        echo '<td>' . findCellValues($nameFetchClassResult, $teacher_name, $subject_name) . '</td>';
                                    } else {
                                        // Display an empty cell if there is no schedule data for the current day and time
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
</div>