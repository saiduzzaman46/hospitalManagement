<div class="appointment-management-controls">
    <a href="?section=appointments&action=create" class="button <?php echo (isset($_GET['action']) && $_GET['action'] == 'create') ? 'active' : 'button-outline'; ?>">Create Appointment</a>
    <a href="?section=appointments&action=view" class="button <?php echo (!isset($_GET['action']) || $_GET['action'] == 'view') ? 'active' : 'button-outline'; ?>">View Appointments</a>
</div>