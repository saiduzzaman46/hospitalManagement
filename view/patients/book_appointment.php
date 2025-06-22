    <?php
    session_start(); // Start the session


    // Check if doctor_id is provided in the URL
    $doctor_id = isset($_GET['doctor_id']) ? htmlspecialchars($_GET['doctor_id']) : null;
    $doctor_info = null;

    // Dummy Data for doctors (should match what's in patient_dashboard.php and your DB)
    $doctors_dummy = [
        ['id' => 'D001', 'name' => 'Dr. Emily Watson', 'specialty' => 'Cardiologist', 'contact' => 'emily.w@medcare.com', 'info' => 'Expert in heart health.'],
        ['id' => 'D002', 'name' => 'Dr. John Smith', 'specialty' => 'Pediatrician', 'contact' => 'john.s@medcare.com', 'info' => 'Dedicated to children\'s health.'],
        ['id' => 'D003', 'name' => 'Dr. Sarah Lee', 'specialty' => 'Dermatologist', 'contact' => 'sarah.l@medcare.com', 'info' => 'Specializes in skin conditions.'],
    ];

    // Fetch doctor info based on doctor_id
    if ($doctor_id) {
        // In a real application, you'd call a model function like:
        // $doctor_info = get_doctor_by_id($conn, $doctor_id);
        foreach ($doctors_dummy as $doctor) {
            if ($doctor['id'] === $doctor_id) {
                $doctor_info = $doctor;
                break;
            }
        }
    }

    // If no doctor is found or ID is missing, redirect back to doctors list
    if (!$doctor_info) {
        $_SESSION['appointment_message'] = 'Doctor not found or invalid selection.';
        $_SESSION['appointment_message_type'] = 'error';
        header("Location: patient_dashboard.php?section=doctors");
        exit;
    }

    // If patient info is not available, handle error (should ideally not happen if auth works)
    if (!$patient_info) {
        $_SESSION['appointment_message'] = 'Patient information could not be retrieved. Please try logging in again.';
        $_SESSION['appointment_message_type'] = 'error';
        header("Location: ../../view/login/login.php");
        exit;
    }

    // Display messages from session if any
    $appointment_message = $_SESSION['appointment_message'] ?? '';
    $appointment_message_type = $_SESSION['appointment_message_type'] ?? '';
    unset($_SESSION['appointment_message']);
    unset($_SESSION['appointment_message_type']);

    ?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Book Appointment with <?php echo htmlspecialchars($doctor_info['name']); ?></title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
        <style>
            /* Reusing and adapting styles from the dashboard */
            * {
                padding: 0;
                margin: 0;
                box-sizing: border-box;
            }

            html {
                font-size: 62.5%;
                overflow-x: hidden;
                height: 100%;
            }

            :root {
                --header-height: 7.2rem;
                --footer-height: 6.4rem;
                --main-padding-y: 3.2rem;
                --border-radius-base: 0.4rem;
                --box-shadow-base: 0 0.1rem 0.3rem rgba(0, 0, 0, 0.08);
                --light-bg: #eef2f6;
                --border-color-light: #d1d5db;
                --primary-blue: #3b82f6;
                --dark-blue: #1e40af;
                --green-success: #10b981;
                --red-error: #ef4444;
            }

            body {
                font-family: 'Inter', sans-serif;
                background-color: #f1f5f9;
                color: #334155;
                line-height: 1.6;
                font-size: 1.6rem;
                display: flex;
                flex-direction: column;
                min-height: 100vh;
            }

            .container {
                max-width: 90rem;
                /* Adjusted for a single form page */
                margin: 0 auto;
                padding: 0 2rem;
            }

            h1,
            h2,
            h3 {
                color: #1e293b;
                margin-bottom: 1.6rem;
                line-height: 1.2;
            }

            h1 {
                font-size: 3.6rem;
                font-weight: 700;
                text-align: center;
            }

            h2 {
                font-size: 2.8rem;
                font-weight: 600;
                margin-bottom: 2.4rem;
            }

            h3 {
                font-size: 2.2rem;
                font-weight: 600;
                margin-bottom: 1.2rem;
            }

            p {
                margin-bottom: 1.6rem;
            }

            .button {
                display: inline-block;
                padding: 1rem 2rem;
                background-color: var(--primary-blue);
                color: white;
                text-decoration: none;
                border-radius: var(--border-radius-base);
                font-weight: 600;
                transition: background-color 0.2s ease, transform 0.1s ease, box-shadow 0.1s ease;
                font-size: 1.5rem;
                border: none;
                cursor: pointer;
                box-shadow: 0 0.2rem 0.4rem rgba(0, 0, 0, 0.1);
            }

            .button:hover {
                background-color: #2563eb;
                transform: translateY(-0.1rem);
                box-shadow: 0 0.3rem 0.6rem rgba(0, 0, 0, 0.15);
            }

            .button-secondary {
                background-color: var(--green-success);
            }

            .button-secondary:hover {
                background-color: #059669;
            }

            .button-danger {
                background-color: var(--red-error);
            }

            .button-danger:hover {
                background-color: #dc2626;
            }

            .button-outline {
                background-color: transparent;
                border: 0.1rem solid var(--primary-blue);
                color: var(--primary-blue);
                box-shadow: none;
            }

            .button-outline:hover {
                background-color: #eff6ff;
                color: #2563eb;
                transform: none;
                box-shadow: none;
            }

            header {
                background-color: #ffffff;
                padding: 1.8rem 0;
                box-shadow: 0 0.1rem 0.2rem rgba(0, 0, 0, 0.08);
                position: sticky;
                top: 0;
                z-index: 1000;
                min-height: var(--header-height);
                display: flex;
                align-items: center;
                border-bottom: 0.1rem solid var(--border-color-light);
            }

            header .container {
                display: flex;
                justify-content: space-between;
                align-items: center;
                width: 100%;
            }

            .logo {
                font-size: 2.6rem;
                font-weight: 700;
                color: var(--primary-blue);
                text-decoration: none;
            }

            .patient-info {
                display: flex;
                align-items: center;
                gap: 0.8rem;
                font-size: 1.6rem;
                font-weight: 600;
                color: #4a5568;
            }

            .patient-info i {
                font-size: 2rem;
                color: var(--primary-blue);
            }

            .main-content {
                flex: 1;
                padding: var(--main-padding-y) 0;
                display: flex;
                justify-content: center;
                align-items: flex-start;
            }

            .booking-form-section {
                background-color: #ffffff;
                border-radius: var(--border-radius-base);
                box-shadow: var(--box-shadow-base);
                padding: 2.8rem;
                width: 100%;
                border: 0.1rem solid var(--border-color-light);
            }

            .info-card {
                background-color: var(--light-bg);
                padding: 2rem;
                border-radius: var(--border-radius-base);
                border: 0.1rem solid var(--border-color-light);
                margin-bottom: 2rem;
            }

            .info-card h3 {
                margin-bottom: 1rem;
                color: var(--dark-blue);
                border-bottom: 0.1rem solid #cbd5e0;
                padding-bottom: 0.8rem;
            }

            .info-card p {
                margin-bottom: 0.5rem;
                font-size: 1.5rem;
            }

            .form-group {
                margin-bottom: 1.4rem;
            }

            .form-group label {
                display: block;
                margin-bottom: 0.6rem;
                font-size: 1.4rem;
                color: #4b5563;
                font-weight: 500;
            }

            .form-group input,
            .form-group select,
            .form-group textarea {
                width: 100%;
                padding: 0.8rem;
                border: 0.1rem solid var(--border-color-light);
                border-radius: var(--border-radius-base);
                font-size: 1.5rem;
                color: #334155;
                outline: none;
                background-color: #ffffff;
            }

            .form-group input:focus,
            .form-group select:focus,
            .form-group textarea:focus {
                border-color: var(--primary-blue);
                box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.2);
            }

            .form-actions {
                display: flex;
                gap: 1rem;
                justify-content: flex-end;
                margin-top: 2rem;
            }

            .message-box {
                margin-top: 1.6rem;
                padding: 1.2rem;
                border-radius: var(--border-radius-base);
                text-align: center;
                font-weight: 500;
                display: none;
                font-size: 1.4rem;
            }

            .message-success {
                background-color: #d1fae5;
                color: #065f46;
                border: 0.1rem solid #34d399;
            }

            .message-error {
                background-color: #fee2e2;
                color: #991b1b;
                border: 0.1rem solid #ef4444;
            }


            footer {
                background-color: #2f3e4e;
                color: white;
                padding: 3rem 0;
                text-align: center;
                font-size: 1.3rem;
                margin-top: auto;
                min-height: var(--footer-height);
                display: flex;
                align-items: center;
                justify-content: center;
                border-top: 0.1rem solid #4b5563;
            }

            footer p {
                margin: 0;
                color: rgba(255, 255, 255, 0.7);
            }

            @media (max-width: 768px) {
                html {
                    font-size: 58%;
                }

                .container {
                    padding: 0 1.5rem;
                }

                h1 {
                    font-size: 3rem;
                }

                h2 {
                    font-size: 2.4rem;
                }
            }

            @media (max-width: 480px) {
                html {
                    font-size: 55%;
                }

                .form-actions {
                    flex-direction: column;
                }

                .form-actions .button {
                    width: 100%;
                }
            }
        </style>
    </head>

    <body>
        <header>
            <div class="container">
                <a href="patient_dashboard.php" class="logo">MedCare Hospital</a>
                <div class="patient-info">
                    <i class="fas fa-user-circle"></i>
                    <span><?php echo htmlspecialchars($patient_info['fname']); ?></span>
                </div>
            </div>
        </header>

        <main class="main-content">
            <div class="container booking-form-section">
                <h2>Book Your Appointment</h2>
                <p>Please confirm the details and select your preferred date and time for the appointment.</p>

                <?php if ($appointment_message): ?>
                    <div id="bookingMessage" class="message-box message-<?php echo htmlspecialchars($appointment_message_type); ?>" style="display: block;">
                        <?php echo htmlspecialchars($appointment_message); ?>
                    </div>
                <?php else: ?>
                    <div id="bookingMessage" class="message-box"></div>
                <?php endif; ?>

                <div class="info-card">
                    <h3>Doctor Information</h3>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($doctor_info['name']); ?></p>
                    <p><strong>Specialty:</strong> <?php echo htmlspecialchars($doctor_info['specialty']); ?></p>
                    <p><strong>Contact:</strong> <?php echo htmlspecialchars($doctor_info['contact']); ?></p>
                    <p><strong>About:</strong> <?php echo htmlspecialchars($doctor_info['info']); ?></p>
                </div>

                <div class="info-card">
                    <h3>Your Information</h3>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($patient_info['fname']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($patient_info['email']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($patient_info['phone']); ?></p>
                </div>

                <form id="bookAppointmentForm" method="POST" action="../../controller/book_appointment_con.php">
                    <input type="hidden" name="patient_id" value="<?php echo htmlspecialchars($patient_info['id']); ?>">
                    <input type="hidden" name="doctor_id" value="<?php echo htmlspecialchars($doctor_info['id']); ?>">

                    <div class="form-group">
                        <label for="appointmentDate">Preferred Date:</label>
                        <input type="date" id="appointmentDate" name="appointment_date" min="<?php echo date('Y-m-d'); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="appointmentTime">Preferred Time:</label>
                        <input type="time" id="appointmentTime" name="appointment_time" required>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="button button-secondary">Confirm Booking</button>
                        <a href="patient_dashboard.php?section=doctors" class="button button-outline">Cancel</a>
                    </div>
                </form>
            </div>
        </main>

        <footer>
            <div class="container">
                <p>&copy; 2025 MedCare Hospital. All rights reserved. Your health, our priority.</p>
            </div>
        </footer>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const bookingMessageDiv = document.getElementById('bookingMessage');
                if (bookingMessageDiv && bookingMessageDiv.textContent.trim() !== '') {
                    bookingMessageDiv.style.display = 'block';
                    setTimeout(() => {
                        bookingMessageDiv.style.display = 'none';
                    }, 3000);
                }

                const appointmentDateInput = document.getElementById('appointmentDate');
                if (appointmentDateInput) {
                    // Set min date to today to prevent booking in the past
                    const today = new Date();
                    const year = today.getFullYear();
                    const month = String(today.getMonth() + 1).padStart(2, '0');
                    const day = String(today.getDate()).padStart(2, '0');
                    appointmentDateInput.min = `${year}-${month}-${day}`;
                }

                // Basic client-side form validation (server-side validation is crucial)
                const bookAppointmentForm = document.getElementById('bookAppointmentForm');
                if (bookAppointmentForm) {
                    bookAppointmentForm.addEventListener('submit', function(event) {
                        const date = appointmentDateInput.value;
                        const time = document.getElementById('appointmentTime').value;

                        if (!date || !time) {
                            showMessage('bookingMessage', 'Please select both date and time for your appointment.', 'error');
                            event.preventDefault(); // Stop form submission
                            return;
                        }

                        // You might add more complex validation here, e.g., checking doctor's availability
                        // For now, this is just a basic client-side check.
                    });
                }
            });

            // Reusing the showMessage helper from the dashboard for consistency
            function showMessage(elementId, message, type) {
                const msgBox = document.getElementById(elementId);
                msgBox.textContent = message;
                msgBox.className = `message-box message-${type}`;
                msgBox.style.display = 'block';
                setTimeout(() => {
                    msgBox.style.display = 'none';
                }, 3000);
            }
        </script>
    </body>

    </html>