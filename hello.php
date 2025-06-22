<?php
// PHP variables for demonstration purposes. In a real application, these would come from your controller/database.
// Assume $section is set from a GET parameter, e.g., $_GET['section']
$section = isset($_GET['section']) ? $_GET['section'] : 'doctors'; // Default to 'doctors' if not set

// Assume $doctorEditAction is true if action is 'edit_doctor' and an ID is present
// This variable now correctly encapsulates the full condition for showing the edit section.
$doctorEditAction = ($section === 'doctors' && isset($_GET['action']) && $_GET['action'] == 'edit_doctor' && isset($_GET['id']));

// Dummy doctor data for demonstration. In a real app, this would be fetched from DB.
$doctors = [
    [
        'id' => 1,
        'name' => 'Dr. Jane Doe',
        'specialty' => 'Cardiology',
        'contact' => 'jane.doe@example.com',
        'phone' => '123-456-7890',
        'fees' => 150,
        'info' => 'Experienced heart specialist.',
        'fromDay' => 'Monday',
        'toDay' => 'Friday',
        'startTime' => '09:00',
        'endTime' => '17:00'
    ],
    [
        'id' => 2,
        'name' => 'Dr. John Smith',
        'specialty' => 'Pediatrics',
        'contact' => '+19876543210',
        'phone' => '987-654-3210',
        'fees' => 120,
        'info' => 'Dedicated to children\'s health.',
        'fromDay' => 'Tuesday',
        'toDay' => 'Saturday',
        'startTime' => '10:00',
        'endTime' => '18:00'
    ]
];

// Dummy data for doctor to edit (simulating fetching from DB based on ID)
$doctorToEdit = null;
if ($doctorEditAction) { // Only attempt to find doctor if edit action is valid
    $editId = $_GET['id'];
    foreach ($doctors as $doctor) {
        if ($doctor['id'] == $editId) {
            $doctorToEdit = $doctor;
            break;
        }
    }
}

$specialties = [
    'Cardiology', 'Pediatrics', 'Neurology', 'Dermatology', 'Orthopedics',
    'Ophthalmology', 'General Medicine', 'Oncology', 'Gastroenterology',
    'Psychiatry', 'Urology', 'ENT', 'Dentistry'
];

?>

<section id="content-doctors" class="dashboard-section" style="<?php echo $section === 'doctors' ? '' : 'display: none'; ?>">
    <h2>Doctor Management</h2>
    <p>Add new doctor records, view, or edit existing ones.</p>
    <div class="doctor-management-controls">
        <a href="?section=doctors&action=add" class="button <?php echo (isset($_GET['action']) && $_GET['action'] == 'add') ? 'active' : 'button-outline'; ?>">Add Doctor</a>
        <a href="?section=doctors&action=view" class="button <?php echo (!isset($_GET['action']) || $_GET['action'] == 'view') ? 'active' : 'button-outline'; ?>">View Doctors</a>
    </div>

    <div id="addDoctorSection" class="form-section" style="<?php echo ($section === 'doctors' && isset($_GET['action']) && $_GET['action'] == 'add') ? '' : 'display: none'; ?>">
        <h3>Add New Doctor</h3>
        <form id="doctorForm" method="POST" action="../../controller/doctor_con.php">
            <input type="hidden" name="action" value="add">
            <div class="form-group">
                <label for="doctorName">Full Name</label>
                <input type="text" id="doctorName" name="doctorName" placeholder="Doctor's Full Name" required>
            </div>
            <div class="form-group">
                <label for="doctorSpecialty">Specialty</label>
                <select id="doctorSpecialty" name="doctorSpecialty" required>
                    <option value="">Select Specialty</option>
                    <?php foreach ($specialties as $specialty): ?>
                        <option value="<?php echo htmlspecialchars($specialty); ?>"><?php echo htmlspecialchars($specialty); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="doctorContact">Email</label>
                <input type="email" id="doctorContact" name="doctorContact" placeholder="email@example.com" required>
            </div>
            <div class="form-group">
                <label for="doctorPhone">Phone Number</label>
                <input type="tel" id="doctorPhone" name="doctorPhone" placeholder="+1234567890" pattern="[0-9+\-()\s]+" title="Enter a valid phone number" required>
            </div>
            <div class="form-group">
                <label for="doctorPassword">Password</label>
                <input type="password" id="doctorPassword" name="doctorPassword" placeholder="Set password" required minlength="6">
                <small class="text-gray-500">
                    <br>This password will be securely hashed upon submission.
                </small>
            </div>
            <div class="form-group">
                <label for="doctorFees">Consultation Fees ($)</label>
                <input type="number" id="doctorFees" name="doctorFees" placeholder="e.g., 150" required min="0" step="any">
            </div>
            <div class="form-group">
                <label for="doctorInfo">Doctor Info (Short Bio)</label>
                <textarea id="doctorInfo" name="doctorInfo" rows="3" placeholder="e.g., Dedicated to children's health with 10 years experience..." required></textarea>
            </div>
            <div class="doctor-availability-group grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-group">
                    <label for="doctorFromDay">Available From Day</label>
                    <select id="doctorFromDay" name="doctorFromDay" required>
                        <option value="">Select Day</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="doctorToDay">Available To Day</label>
                    <select id="doctorToDay" name="doctorToDay" required>
                        <option value="">Select Day</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="doctorStartTime">Start Time</label>
                    <input type="time" id="doctorStartTime" name="doctorStartTime" required>
                </div>
                <div class="form-group">
                    <label for="doctorEndTime">End Time</label>
                    <input type="time" id="doctorEndTime" name="doctorEndTime" required>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="button">Add Doctor</button>
            </div>
        </form>
        <?php
        if (isset($_SESSION['doctor_message'])) {
            echo '<div id="doctorFormMessage" class="message-box ' . htmlspecialchars($_SESSION['doctor_message_type']) . '">' . htmlspecialchars($_SESSION['doctor_message']) . '</div>';
            unset($_SESSION['doctor_message']);
            unset($_SESSION['doctor_message_type']);
        }
        ?>
    </div>

    <div id="editDoctorSection" class="form-section" style="<?php echo ($doctorEditAction) ? '' : 'display: none'; ?>">
        <h3>Edit Doctor Information</h3>
        <?php if ($doctorToEdit): ?>
            <form id="editDoctorForm" method="POST" action="../../controller/doctor_con.php">
                <input type="hidden" name="action" value="update_doctor">
                <input type="hidden" name="doctor_id" value="<?php echo htmlspecialchars($doctorToEdit['id']); ?>">
                <div class="form-group">
                    <label for="editDoctorName">Full Name</label>
                    <input type="text" id="editDoctorName" name="doctorName" value="<?php echo htmlspecialchars($doctorToEdit['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="editDoctorSpecialty">Specialty</label>
                    <select id="editDoctorSpecialty" name="doctorSpecialty" required>
                        <option value="">Select Specialty</option>
                        <?php foreach ($specialties as $specialty): ?>
                            <option value="<?php echo htmlspecialchars($specialty); ?>" <?php if ($doctorToEdit['specialty'] === $specialty) echo "selected"; ?>>
                                <?php echo htmlspecialchars($specialty); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="editDoctorContact">Email</label>
                    <input type="email" id="editDoctorContact" name="doctorContact" value="<?php echo htmlspecialchars($doctorToEdit['contact']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="editDoctorPhone">Phone Number</label>
                    <input type="tel" id="editDoctorPhone" name="doctorPhone" value="<?php echo htmlspecialchars($doctorToEdit['phone']); ?>" pattern="[0-9+\-()\s]+" title="Enter a valid phone number" required>
                </div>
                <div class="form-group">
                    <label for="editDoctorFees">Consultation Fees ($)</label>
                    <input type="number" id="editDoctorFees" name="doctorFees" value="<?php echo htmlspecialchars($doctorToEdit['fees']); ?>" required min="0" step="any">
                </div>
                <div class="form-group">
                    <label for="editDoctorInfo">Doctor Info (Short Bio)</label>
                    <textarea id="editDoctorInfo" name="doctorInfo" rows="3" required><?php echo htmlspecialchars($doctorToEdit['info']); ?></textarea>
                </div>
                <div class="doctor-availability-group grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label for="editDoctorFromDay">Available From Day</label>
                        <select id="editDoctorFromDay" name="doctorFromDay" required>
                            <option value="">Select Day</option>
                            <option value="Monday" <?php if ($doctorToEdit['fromDay'] === "Monday") echo "selected"; ?>>Monday</option>
                            <option value="Tuesday" <?php if ($doctorToEdit['fromDay'] === "Tuesday") echo "selected"; ?>>Tuesday</option>
                            <option value="Wednesday" <?php if ($doctorToEdit['fromDay'] === "Wednesday") echo "selected"; ?>>Wednesday</option>
                            <option value="Thursday" <?php if ($doctorToEdit['fromDay'] === "Thursday") echo "selected"; ?>>Thursday</option>
                            <option value="Friday" <?php if ($doctorToEdit['fromDay'] === "Friday") echo "selected"; ?>>Friday</option>
                            <option value="Saturday" <?php if ($doctorToEdit['fromDay'] === "Saturday") echo "selected"; ?>>Saturday</option>
                            <option value="Sunday" <?php if ($doctorToEdit['toDay'] === "Sunday") echo "selected"; ?>>Sunday</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editDoctorToDay">Available To Day</label>
                        <select id="editDoctorToDay" name="doctorToDay" required>
                            <option value="">Select Day</option>
                            <option value="Monday" <?php if ($doctorToEdit['toDay'] === "Monday") echo "selected"; ?>>Monday</option>
                            <option value="Tuesday" <?php if ($doctorToEdit['toDay'] === "Tuesday") echo "selected"; ?>>Tuesday</option>
                            <option value="Wednesday" <?php if ($doctorToEdit['toDay'] === "Wednesday") echo "selected"; ?>>Wednesday</option>
                            <option value="Thursday" <?php if ($doctorToEdit['toDay'] === "Thursday") echo "selected"; ?>>Thursday</option>
                            <option value="Friday" <?php if ($doctorToEdit['toDay'] === "Friday") echo "selected"; ?>>Friday</option>
                            <option value="Saturday" <?php if ($doctorToEdit['toDay'] === "Saturday") echo "selected"; ?>>Saturday</option>
                            <option value="Sunday" <?php if ($doctorToEdit['toDay'] === "Sunday") echo "selected"; ?>>Sunday</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editDoctorStartTime">Start Time</label>
                        <input type="time" id="editDoctorStartTime" name="doctorStartTime" value="<?php echo htmlspecialchars($doctorToEdit['startTime']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="editDoctorEndTime">End Time</label>
                        <input type="time" id="editDoctorEndTime" name="doctorEndTime" value="<?php echo htmlspecialchars($doctorToEdit['endTime']); ?>" required>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="button button-secondary">Save Changes</button>
                    <a href="?section=doctors&action=view" class="button button-outline">Cancel</a>
                </div>
            </form>
        <?php else: ?>
            <p class="message-box message-error" style="display:block;">Doctor data not found. Please select a valid doctor to edit.</p>
            <div class="form-actions">
                <a href="?section=doctors&action=view" class="button button-outline">Back to Doctors List</a>
            </div>
        <?php endif; ?>
    </div>

    <div id="viewDoctorsSection" style="<?php echo ($section === 'doctors' && !isset($_GET['action'])) || ($section === 'doctors' && $_GET['action'] == 'view') ? '' : 'display: none'; ?>">
        <h3>Doctor List</h3>
        <div class="table-container">
            <table class="data-table" id="doctorTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Specialty</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Fees ($)</th>
                        <th>Info</th>
                        <th>Availability</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (empty($doctors)) {
                        echo '<tr><td colspan="9" style="text-align: center; padding: 2rem;">No doctor records found.</td></tr>';
                    } else {
                        foreach ($doctors as $doctor) {
                            $availabilityText = $doctor['fromDay'] . ' to ' . $doctor['toDay'] . ': ' . $doctor['startTime'] . ' - ' . $doctor['endTime'];
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($doctor['id']) . '</td>';
                            echo '<td>' . htmlspecialchars($doctor['name']) . '</td>';
                            echo '<td>' . htmlspecialchars($doctor['specialty']) . '</td>';
                            echo '<td>' . htmlspecialchars($doctor['contact']) . '</td>';
                            echo '<td>' . htmlspecialchars($doctor['phone']) . '</td>';
                            echo '<td>$' . htmlspecialchars(number_format($doctor['fees'], 2)) . '</td>';
                            echo '<td>' . htmlspecialchars($doctor['info']) . '</td>'; // Separated Info
                            echo '<td>' . htmlspecialchars($availabilityText) . '</td>'; // Separated Availability
                            echo '<td><div class="action-buttons">';
                            echo '<a href="?section=doctors&action=edit_doctor&id=' . htmlspecialchars($doctor['id']) . '" class="button button-outline">Edit</a>';
                            // Note: confirmAction JavaScript function would need to be defined elsewhere
                            echo '<button class="button button-danger" onclick="confirmAction(\'delete_doctor\', \'' . htmlspecialchars($doctor['id']) . '\')">Delete</button>';
                            echo '</div></td>';
                            echo '</tr>';
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</section>
