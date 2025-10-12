<?php
$t_code = $_GET['t_code'] ?? '';
if (!$t_code) die('Missing transaction code.');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Upload Documents</title>
<style>
  body { font-family: Arial, sans-serif; text-align: center; padding: 30px; background: #fafafa; }
  form { 
    border: 1px solid #ccc; 
    padding: 20px; 
    border-radius: 10px; 
    display: inline-block; 
    background: #fff; 
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
  }
  input, button { margin: 10px 0; font-size: 16px; }
  button { padding: 8px 20px; cursor: pointer; }
  #preview {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 10px;
  }
  #preview img {
    width: 100px;
    height: auto;
    margin: 5px;
    border-radius: 8px;
    border: 1px solid #ccc;
    object-fit: cover;
  }
</style>
</head>
<body>
  <h2>📄 Upload for Transaction #<?= htmlspecialchars($t_code) ?></h2>
  <form id="uploadForm" enctype="multipart/form-data">
    <input type="hidden" name="action" value="saveQrUpload">
    <input type="hidden" name="t_code" value="<?= htmlspecialchars($t_code) ?>">
    
    <!-- ✅ Allow multiple files -->
    <input type="file" name="t_file[]" accept="image/*,application/pdf" capture="environment" multiple required><br>
    
    <button type="submit">Upload</button>
  </form>

  <!-- ✅ Preview selected images -->
  <div id="preview"></div>

  <p id="status"></p>

<script>
const fileInput = document.querySelector('input[type="file"]');
const previewContainer = document.getElementById('preview');
const statusEl = document.getElementById('status');
const form = document.getElementById('uploadForm');

// ✅ Preview selected images
fileInput.addEventListener('change', () => {
  previewContainer.innerHTML = '';
  const files = fileInput.files;
  if (!files.length) return;
  
  [...files].forEach(file => {
    if (!file.type.startsWith('image/')) return;
    const img = document.createElement('img');
    img.src = URL.createObjectURL(file);
    previewContainer.appendChild(img);
  });
});

// ✅ Handle upload
form.addEventListener('submit', async (e) => {
  e.preventDefault();
  const formData = new FormData(e.target);
  statusEl.textContent = '⏳ Uploading...';

  try {
    const res = await fetch('trackFunctions.php', { method: 'POST', body: formData });
    const text = await res.text();
    const data = JSON.parse(text);

    if (data.success) {
      statusEl.innerHTML = '✅ Uploaded successfully!';
      form.reset();
      previewContainer.innerHTML = '';
    } else {
      statusEl.innerHTML = '❌ Error: ' + (data.message || 'Upload failed.');
    }
  } catch (err) {
    statusEl.innerHTML = '❌ Unexpected server response.<br>' + err.message;
  }
});
</script>
</body>
</html>
