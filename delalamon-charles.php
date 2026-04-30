<?php
$pageTitle  = 'Add Medicine';
$activePage = 'add';

require_once 'config/session.php';
require_once 'config/db.php';

$errors = [];
$old    = [];

// HANDLE FORM
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $old = $_POST;

    $name     = trim($_POST['medicine_name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $quantity = $_POST['quantity'] ?? '';
    $unit     = trim($_POST['unit'] ?? '');
    $exp      = $_POST['expiration_date'] ?? '';
    $added    = $_POST['date_added'] ?? date('Y-m-d');
    $status   = $_POST['status'] ?? 'In Stock';
    $remarks  = trim($_POST['remarks'] ?? '');

    // ✅ IMAGE UPLOAD ADDED
    $imageName = null;

    if (!empty($_FILES['medicine_image']['name'])) {
        $imageName = time() . '_' . $_FILES['medicine_image']['name'];
        move_uploaded_file(
            $_FILES['medicine_image']['tmp_name'],
            "uploads/" . $imageName
        );
    }

    if (empty($name)) {
        $errors[] = 'Medicine name is required.';
    }

    if ($quantity === '') {
        $errors[] = 'Quantity is required.';
    } elseif (!is_numeric($quantity) || (int)$quantity < 0) {
        $errors[] = 'Quantity must be a non-negative number.';
    }

    if ((int)$quantity === 0) {
        $status = 'Out of Stock';
    } elseif ((int)$quantity <= 10 && $status === 'In Stock') {
        $status = 'Low Stock';
    }

    if (empty($errors)) {

        $stmt = $conn->prepare("
            INSERT INTO medicines
            (medicine_name, category, quantity, unit, expiration_date, date_added, status, remarks, image)
            VALUES (?, ?, ?, ?, NULLIF(?, ''), NULLIF(?, ''), ?, ?, ?)
        ");

        if ($stmt) {

            $qty = (int)$quantity;

            $stmt->bind_param(
                "ssissssss",
                $name,
                $category,
                $qty,
                $unit,
                $exp,
                $added,
                $status,
                $remarks,
                $imageName
            );

            if ($stmt->execute()) {

                $_SESSION['flash'] = [
                    'type' => 'success',
                    'msg'  => 'Medicine added successfully!'
                ];

                header('Location: medicines.php');
                exit;
            } else {
                $errors[] = 'Database error: ' . $stmt->error;
            }

            $stmt->close();

        } else {
            $errors[] = 'Prepare failed: ' . $conn->error;
        }
    }
}

// LOAD HEADER AFTER LOGIC
require_once 'includes/header.php';
?>

<div class="page-header">
  <div>
    <h1>Add Medicine</h1>
    <p>Add a new medicine to the clinic inventory</p>
  </div>
  <a href="medicines.php" class="btn btn-secondary">← Back to List</a>
</div>

<?php if (!empty($errors)): ?>
  <div class="alert alert-error">
    ⚠ <?= implode(' | ', array_map('htmlspecialchars', $errors)) ?>
  </div>
<?php endif; ?>

<div class="form-card">
  <form method="POST" action="add_medicine.php" enctype="multipart/form-data">

    <div class="form-grid">

      <div class="field span2">
        <label>Medicine Name *</label>
        <input type="text" name="medicine_name"
               value="<?= htmlspecialchars($old['medicine_name'] ?? '') ?>"
               required>
      </div>

      <div class="field">
        <label>Category</label>
        <select name="category">
          <option value="">— Select Category —</option>
          <?php
          $cats = ['Analgesic','Antibiotic','Antihistamine','Antacid','Antiseptic','Vitamin/Supplement','First Aid','Decongestant','Other'];
          foreach ($cats as $c):
          ?>
            <option <?= (($old['category'] ?? '') === $c) ? 'selected' : '' ?>><?= $c ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="field">
        <label>Unit</label>
        <select name="unit">
          <option value="">— Select Unit —</option>
          <?php
          $units = ['tablets','capsules','bottles','sachets','ampules','pieces','boxes','packs'];
          foreach ($units as $u):
          ?>
            <option <?= (($old['unit'] ?? '') === $u) ? 'selected' : '' ?>><?= $u ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="field">
        <label>Quantity *</label>
        <input type="number" name="quantity" min="0"
               value="<?= htmlspecialchars($old['quantity'] ?? '') ?>" required>
      </div>

      <!-- ✅ IMAGE FIELD (ADDED, NO DESIGN CHANGE) -->
      <div class="field">
        <label>Medicine Image</label>
        <input type="file" name="medicine_image" accept="image/*">
      </div>

      <div class="field">
        <label>Status</label>
        <select name="status">
          <?php
          $statuses = ['In Stock','Low Stock','Out of Stock','Expired'];
          foreach ($statuses as $s):
          ?>
            <option <?= (($old['status'] ?? 'In Stock') === $s) ? 'selected' : '' ?>><?= $s ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="field">
        <label>Expiration Date</label>
        <input type="date" name="expiration_date"
               value="<?= htmlspecialchars($old['expiration_date'] ?? '') ?>">
      </div>

      <div class="field">
        <label>Date Added</label>
        <input type="date" name="date_added"
               value="<?= htmlspecialchars($old['date_added'] ?? date('Y-m-d')) ?>">
      </div>

      <div class="field span2">
        <label>Remarks</label>
        <textarea name="remarks"><?= htmlspecialchars($old['remarks'] ?? '') ?></textarea>
      </div>

    </div>

    <div class="form-actions">
      <a href="medicines.php" class="btn btn-secondary">Cancel</a>
      <button type="submit" class="btn btn-primary">
        Save Medicine
      </button>
    </div>

  </form>
</div>

<?php require_once 'includes/footer.php'; ?>