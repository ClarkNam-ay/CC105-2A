<?php
session_start();
include "../include/db.php";

// Security Check
if(!isset($_SESSION['user']) || $_SESSION['position'] !== 'manager'){
    header("Location: ../login.php"); 
    exit();
}

// Handle activate/deactivate actions
if(isset($_POST['action']) && isset($_POST['employee_id'])) {
    $employee_id = intval($_POST['employee_id']);
    $action = $_POST['action'];
    
    if($action === 'deactivate') {
        $result = $conn->query("UPDATE user SET status='inactive' WHERE id='$employee_id' AND position='employee'");
        if($result) {
            $_SESSION['success'] = "Employee deactivated successfully!";
        } else {
            $_SESSION['error'] = "Failed to deactivate employee!";
        }
    } elseif($action === 'activate') {
        $result = $conn->query("UPDATE user SET status='active' WHERE id='$employee_id' AND position='employee'");
        if($result) {
            $_SESSION['success'] = "Employee activated successfully!";
        } else {
            $_SESSION['error'] = "Failed to activate employee!";
        }
    }
    
    // Refresh the page
    header("Location: employee_list.php");
    exit();
}

// Fetch all employees with status - WITH ERROR HANDLING
$query = "SELECT id, name, email, employee_role, status FROM user WHERE position='employee' ORDER BY name ASC";
$employees = $conn->query($query);

if(!$employees) {
    die("Database Error: " . $conn->error . "<br>Query: " . $query);
}

// Get counts for stats
$active_count_result = $conn->query("SELECT COUNT(*) as count FROM user WHERE position='employee' AND status='active'");
$total_count_result = $conn->query("SELECT COUNT(*) as count FROM user WHERE position='employee'");

$active_count = $active_count_result ? $active_count_result->fetch_assoc()['count'] : 0;
$total_count = $total_count_result ? $total_count_result->fetch_assoc()['count'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee Management - TaskWaveSystem</title>
    <link rel="stylesheet" href="assets/style.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --success: #10b981;
            --danger: #ef4444;
            --warning: #f59e0b;
            --dark: #1e293b;
            --gray-100: #f8fafc;
            --gray-200: #e2e8f0;
            --gray-600: #64748b;
            --gray-800: #1e293b;
            --white: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .sidebar { 
            width: 270px; 
            height: 100vh; 
            background: linear-gradient(180deg, var(--dark), #0f172a);
            color: white; 
            position: fixed; 
            padding: 25px 20px;
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
        }

        .sidebar h3 {
            color: var(--primary);
            margin-bottom: 30px;
            font-size: 1.4em;
            text-align: center;
        }

        .sidebar a { 
            display: flex;
            align-items: center;
            color: #cbd5e1;
            text-decoration: none; 
            padding: 14px 16px; 
            margin-bottom: 8px; 
            border-radius: 12px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .sidebar a i { margin-right: 12px; width: 20px; }
        .sidebar a:hover { 
            background: var(--primary);
            transform: translateX(5px);
            box-shadow: 0 4px 15px rgba(99, 102, 241, 0.4);
        }

        .main { 
            margin-left: 290px; 
            padding: 30px;
            min-height: 100vh;
        }

        /* Alert Styles */
        .alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .header {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            color: var(--dark);
            padding: 25px 30px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h2 {
            font-size: 2.2em;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stats {
            display: flex;
            gap: 20px;
        }

        .stat-card {
            background: rgba(255,255,255,0.9);
            padding: 15px 20px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .stat-number {
            font-size: 1.8em;
            font-weight: bold;
            color: var(--primary);
        }

        .section {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(20px);
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .section h3 {
            color: var(--dark);
            margin-bottom: 25px;
            font-size: 1.5em;
        }

        .table-container {
            overflow-x: auto;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }

        table { 
            width: 100%; 
            border-collapse: collapse;
            background: white;
            border-radius: 15px;
            overflow: hidden;
        }

        th { 
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white; 
            padding: 18px 15px;
            text-align: left;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-size: 0.9em;
        }

        td { 
            padding: 18px 15px; 
            border-bottom: 1px solid var(--gray-200);
            vertical-align: middle;
        }

        tr:hover {
            background: var(--gray-100);
            transform: scale(1.01);
            transition: all 0.2s ease;
        }

        .status-badge {
            padding: 6px 14px;
            border-radius: 25px;
            font-size: 0.85em;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-active {
            background: rgba(16, 185, 129, 0.2);
            color: var(--success);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .status-inactive {
            background: rgba(239, 68, 68, 0.2);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .btn {
            padding: 10px 18px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9em;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.3s ease;
            margin: 2px;
        }

        .btn-assign {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
        }

        .btn-activate {
            background: linear-gradient(135deg, var(--success), #059669);
            color: white;
        }

        .btn-deactivate {
            background: linear-gradient(135deg, var(--danger), #dc2626);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .employee-name {
            font-weight: 600;
            color: var(--dark);
        }

        .employee-id {
            background: var(--gray-100);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.85em;
            color: var(--gray-600);
        }

        @media (max-width: 768px) {
            .sidebar { width: 100%; position: relative; }
            .main { margin-left: 0; }
            .stats { flex-direction: column; }
        }
    </style>
</head>
<body>

<?php include "manager_sidebar.php"; ?>

<div class="main">
    <?php 
    // Display success/error messages
    if(isset($_SESSION['success'])) {
        echo '<div class="alert alert-success"><i class="fas fa-check-circle"></i>' . $_SESSION['success'] . '</div>';
        unset($_SESSION['success']);
    }
    if(isset($_SESSION['error'])) {
        echo '<div class="alert alert-error"><i class="fas fa-exclamation-circle"></i>' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
    ?>

    <div class="header">
        <div>
            <h2><i class="fas fa-users"></i> Employee Management</h2>
            <p>Manage your TaskWaveSystem team members</p>
        </div>
        <div class="stats">
            <div class="stat-card">
                <div class="stat-number"><?php echo $total_count; ?></div>
                <div>Total Employees</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?php echo $active_count; ?></div>
                <div>Active</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th><i class="fas fa-hashtag"></i> ID</th>
                        <th><i class="fas fa-user"></i> Employee</th>
                        <th><i class="fas fa-envelope"></i> Email</th>
                        <th><i class="fas fa-briefcase"></i> Specialization</th>
                        <th><i class="fas fa-toggle-on"></i> Status</th>
                        <th><i class="fas fa-cogs"></i> Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($employees->num_rows > 0): ?>
                        <?php while($emp = $employees->fetch_assoc()): ?>
                        <tr>
                            <td><span class="employee-id">#<?php echo $emp['id']; ?></span></td>
                            <td>
                                <div>
                                    <div class="employee-name"><?php echo htmlspecialchars($emp['name']); ?></div>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($emp['email']); ?></td>
                            <td>
                                <span style="background: var(--gray-100); padding: 6px 12px; border-radius: 20px; color: var(--gray-600); font-size: 0.9em;">
                                    <?php echo htmlspecialchars($emp['employee_role'] ?? 'N/A'); ?>
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-<?php echo $emp['status'] ?? 'inactive'; ?>">
                                    <i class="fas <?php echo ($emp['status'] ?? 'inactive') === 'active' ? 'fa-check-circle' : 'fa-times-circle'; ?>"></i>
                                    <?php echo ucfirst($emp['status'] ?? 'inactive'); ?>
                                </span>
                            </td>
                            <td>
                                <a href="assign_task.php?employee_id=<?php echo $emp['id']; ?>" class="btn btn-assign">
                                    <i class="fas fa-plus-circle"></i> Assign Task
                                </a>
                                <?php if(($emp['status'] ?? 'inactive') === 'active'): ?>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Deactivate <?php echo htmlspecialchars($emp['name']); ?>?\nThis will prevent them from logging in.');">
                                        <input type="hidden" name="employee_id" value="<?php echo $emp['id']; ?>">
                                        <input type="hidden" name="action" value="deactivate">
                                        <button type="submit" class="btn btn-deactivate" title="Deactivate Account">
                                            <i class="fas fa-power-off"></i> Deactivate
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Activate <?php echo htmlspecialchars($emp['name']); ?>?\nThey will be able to log in again.');">
                                        <input type="hidden" name="employee_id" value="<?php echo $emp['id']; ?>">
                                        <input type="hidden" name="action" value="activate">
                                        <button type="submit" class="btn btn-activate" title="Activate Account">
                                            <i class="fas fa-power-on"></i> Activate
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 60px; color: var(--gray-600);">
                                <i class="fas fa-users-slash" style="font-size: 5em; margin-bottom: 20px; opacity: 0.5; color: var(--gray-400);"></i>
                                <h3>No employees found</h3>
                                <p style="margin-top: 10px;">Add employees from the <strong>Employee Registration</strong> page</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Add loading state for buttons
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const btn = this.querySelector('button[type="submit"]');
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            btn.disabled = true;
        });
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>

</body>
</html>