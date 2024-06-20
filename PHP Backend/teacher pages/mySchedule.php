<?php
include 'config.php';
// $teacher_id = 1;
if (!isset($_SESSION['teacher_id'])) {
    header("Location: ../index.php");
    exit;
} else {
    $teacher_id = $_SESSION['teacher_id'];
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Schedule</title>
    </head>

    <body>
        <main>
            <form action="" method="post" class="filter-container">
                <div>
                    <label>School Year</label><br>
                    <select name="AY" id="yearSelect" onchange="this.form.submit()">
                        <?php
                        $currentYear = date("Y");
                        for ($i = -1; $i < 4; $i++) {
                            $year = $currentYear + $i;
                            $nextYear = $year + 1;
                            $optionValue = $year . "-" . $nextYear;
                        ?>
                            <option value="<?php echo $optionValue ?>" <?php if (isset($_POST['AY'])) {
                                                                            echo ($_POST['AY'] == "$optionValue") ? "selected" : "";
                                                                        } ?> required><?php echo $optionValue ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </div>

                <div>
                    <label>Semester</label><br>
                    <input type="radio" name="SetSem" id="firstSemester" value="1st" style="margin-top: 10px" onchange="this.form.submit()" <?php if (isset($_POST['SetSem'])) {
                                                                                                                                                echo ($_POST['SetSem'] == '1st') ? "checked" : "";
                                                                                                                                            } ?> required>
                    <label for="firstSemester">1st</label>
                    <input type="radio" name="SetSem" id="secondSemester" value="2nd" onchange="this.form.submit()" <?php if (isset($_POST['SetSem'])) {
                                                                                                                        echo ($_POST['SetSem'] == '2nd') ? "checked" : "";
                                                                                                                    } ?> required>
                    <label for="secondSemester">2nd</label>

                    <button type="button" onclick="window.print()" class="print">Print</button>
                </div>
            </form>

            <?php
            if (isset($_POST['AY']) && isset($_POST['SetSem'])) {
                $AY = $_POST['AY'];
                $SetSem = $_POST['SetSem'];

                // display during print
                echo "<div class='print-only'>
                        <img src='media/ACLC-logo.png'><br>
                        <p class='header'>ACLC College of Ormoc City, Inc.</p>
                        <p class='header'>Lilia Avenue, Brgy. Cogon, Ormoc City</p><br><br>
                        <div class='print-details'>
                            <strong>Instuctor: </strong><br>
                            <strong>Department: </strong><br>
                            <strong>School Year: $AY</strong><br>
                            <strong>Semester: $SetSem</strong>
                        </div>
                    </div>";

                $fetchSchedule = $con->prepare("SELECT * FROM schedule_tb WHERE teacher_id = ? AND schedule_SY = ? AND schedule_semester = ?");
                $fetchSchedule->bind_param("iss", $teacher_id, $AY, $SetSem);
                $fetchSchedule->execute();
                $resultfetchSchedule = $fetchSchedule->get_result();
                function findCellValues($nameFetchClassResult, $subject_name, $room_name)
                {
                    $cellValue = $nameFetchClassResult . "<br>" . $subject_name . "<br>" . $room_name . "<br>";
                    return $cellValue;
                }
                
                echo "<div class='print-only'>
                        <p class='footer'>Prepared by: Name of instructor</p><br>
                        <p class='footer'>Noted by: Name of school director</p>
                    </div>"
            ?>
                <div class="schedule-container">
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

                                $hasSchedule = false;
                                foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day) {
                                    if (isset($scheduleData[$day][$time_start])) {
                                        $hasSchedule = true;
                                        break;
                                    }
                                }
                            ?>
                                <tr class="<?php echo $hasSchedule ? '' : 'no-schedule'; ?>">
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

                                            // Fetch the data for subject
                                            $fetchSubjects = $con->prepare("SELECT subject_name, subject_description FROM subject_tb WHERE subject_id = ?");
                                            $fetchSubjects->bind_param("i", $rowSchedule->subject_id);
                                            $fetchSubjects->execute();
                                            $fetchSubjects->store_result();
                                            $fetchSubjects->bind_result($subject_name, $subject_description);
                                            $fetchSubjects->fetch();

                                            // Fetch the data for room
                                            $fetchRoom = $con->prepare("SELECT room_name FROM room_tb WHERE room_id = ?");
                                            $fetchRoom->bind_param("i", $rowSchedule->room_id);
                                            $fetchRoom->execute();
                                            $fetchRoom->store_result();
                                            $fetchRoom->bind_result($room_name);
                                            $fetchRoom->fetch();

                                            echo '<td>' . findCellValues($nameFetchClassResult, $subject_name, $room_name) . '</td>';
                                        } else {
                                            echo '<td></td>';
                                        }
                                    }
                                    ?>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
        </main>
<?php
            }
        } ?>
    </body>

    </html>