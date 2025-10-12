<?php
$t_code = $_GET['t_code'] ?? '';
if (!$t_code) die('Missing transaction code.');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Upload Document</title>
<style>
  body { font-family: Arial, sans-serif; text-align: center; padding: 30px; }
  form { border: 1px solid #ccc; padding: 20px; border-radius: 10px; display: inline-block; }
  input, button { margin: 10px 0; font-size: 16px; }
  button { padding: 8px 20px; cursor: pointer; }
</style>
</head>
<body>
  <h2>📄 Upload for Transaction #<?= htmlspecialchars($t_code) ?></h2>
  <form id="uploadForm" enctype="multipart/form-data">
    <input type="hidden" name="action" value="saveQrUpload">
    <input type="hidden" name="t_code" value="<?= htmlspecialchars($t_code) ?>">
    <input type="file" name="t_file[]" accept="image/*,application/pdf" capture="environment" required><br>
    <button type="submit">Upload</button>
  </form>

  <p id="status"></p>

<script>
document.getElementById('uploadForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const formData = new FormData(e.target);
  document.getElementById('status').textContent = '⏳ Uploading...';

  const res = await fetch('trackFunctions.php', { method: 'POST', body: formData });
  const text = await res.text();
  try {
    const data = JSON.parse(text);
    if (data.success) {
      document.getElementById('status').innerHTML = '✅ Uploaded successfully!';
      e.target.reset();
    } else {
      document.getElementById('status').innerHTML = '❌ Error: ' + (data.message || 'Upload failed.');
    }
  } catch {
    document.getElementById('status').innerHTML = '❌ Unexpected server response:<br>' + text;
  }
});
</script>
</body>
</html>
