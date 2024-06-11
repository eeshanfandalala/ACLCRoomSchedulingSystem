<div>
    <dm>
        <main>
            <form action="?selectedRoom" method="post" class="filter-container">
                <div>
                    <label>School Year</label><br>
                    <select name="AY" id="yearSelect">
                        <?php
                        $currentYear = date("Y");
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
                </div>

                <div>
                    <label>Semester</label><br>
                    <input type="radio" name="SetSem" id="firstSemester" value="1st" <?php if (isset($_GET['selectedRoom'])) {
                        echo ($_POST['SetSem'] == '1st') ? "checked" : "";
                    } ?> required>

                    <label>1st</label>
                    <input type="radio" name="SetSem" id="secondSemester" value="2nd" <?php if (isset($_GET['selectedRoom'])) {
                        echo ($_POST['SetSem'] == '2nd') ? "checked" : "";
                    } ?> required>
                    <label>2nd</label>
                </div>

                <div>
                    <label>Room</label><br>
                    <select name="room">
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
                    <input type="submit" value="Filter">
                </div>
            </form>
        </main>

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
                        $scheduleData = array();
                        while ($rowSchedule = $resultfetchSchedule->fetch_object()) {
                            $schedule_time = json_decode($rowSchedule->schedule_time, true);
                            $schedule_day = json_decode($rowSchedule->schedule_day, true);

                            foreach ($schedule_day as $day) {
                                for ($i = strtotime($schedule_time['start']); $i < strtotime($schedule_time['end']); $i += 1800) {
                                    $time_start = date('h:i A', $i);
                                    $time_end = date('h:i A', $i + 1800);

                                    $scheduleData[$day][$time_start] = $rowSchedule;
                                }
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
                                        $fetchclass = $con->prepare("SELECT class_courseStrand, class_year, class_section FROM class_tb WHERE class_id = ?");
                                        $fetchclass->bind_param("i", $rowSchedule->class_id);
                                        $fetchclass->execute();
                                        $fetchclass->store_result();
                                        $fetchclass->bind_result($class_courseStrand, $class_year, $class_section);
                                        $fetchclass->fetch();

                                        $nameFetchClassResult = $class_courseStrand . ' ' . $class_year . ' - ' . $class_section;

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

                                        echo '<td>' . findCellValues($nameFetchClassResult, $teacher_name, $subject_name) . '</td>';
                                    } else {
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