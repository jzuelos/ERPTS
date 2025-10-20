<?php
$t_code = $_GET['t_code'] ?? '';
if (!$t_code)
    die('Missing transaction code.');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upload Documents</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: "Segoe UI", Roboto, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .upload-card {
            width: 95%;
            max-width: 480px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        #preview {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-top: 15px;
        }

        #preview img {
            width: 90px;
            height: 90px;
            border-radius: 10px;
            object-fit: cover;
            border: 2px solid #dee2e6;
            cursor: pointer;
            transition: transform 0.2s;
        }

        #preview img:hover {
            transform: scale(1.05);
        }

        #fullPreviewModal img {
            width: 100%;
            max-height: 85vh;
            object-fit: contain;
        }
    </style>
</head>

<body>

    <div class="card upload-card text-center">
        <div class="card-body">
            <h4 class="card-title mb-3">📄 Upload for Transaction #<?= htmlspecialchars($t_code) ?></h4>
            <form id="uploadForm" enctype="multipart/form-data">
                <input type="hidden" name="action" value="saveQrUpload">
                <input type="hidden" name="t_code" value="<?= htmlspecialchars($t_code) ?>">

                <div class="mb-3">
                    <label for="fileInput" class="form-label fw-semibold">Select Files (Images or PDFs)</label>
                    <input type="file" class="form-control" id="fileInput" name="t_file[]"
                        accept="image/*,application/pdf" capture="environment" multiple>
                    <div class="form-text">Images will be automatically converted into a PDF before upload.</div>
                </div>

                <div id="preview"></div>

                <button type="submit" class="btn btn-primary w-100 mt-3">
                    <i class="bi bi-cloud-arrow-up"></i> Upload
                </button>
            </form>

            <div class="mt-3">
                <p id="status" class="fw-semibold text-secondary small"></p>
            </div>
        </div>
    </div>

    <!-- Fullscreen Image Preview -->
    <div class="modal fade" id="fullPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
            <div class="modal-content bg-dark border-0">
                <div class="modal-body text-center p-0">
                    <img id="fullPreviewImage" src="" alt="Preview">
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const { jsPDF } = window.jspdf;
        const fileInput = document.getElementById('fileInput');
        const previewContainer = document.getElementById('preview');
        const statusEl = document.getElementById('status');
        const form = document.getElementById('uploadForm');
        const fullModal = new bootstrap.Modal(document.getElementById('fullPreviewModal'));
        const fullImage = document.getElementById('fullPreviewImage');
        let selectedFiles = [];

        //Preview and accumulate images
        fileInput.addEventListener('change', () => {
            const newFiles = Array.from(fileInput.files);

            // Add new files to existing ones (accumulate)
            newFiles.forEach(file => {
                // Check if file with same name and size already exists
                const isDuplicate = selectedFiles.some(existing =>
                    existing.name === file.name && existing.size === file.size
                );
                if (!isDuplicate) {
                    selectedFiles.push(file);
                }
            });

            // Clear input to allow selecting same file again
            fileInput.value = '';

            // Redraw all previews
            updatePreview();
        });

        function updatePreview() {
            previewContainer.innerHTML = '';

            selectedFiles.forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const wrapper = document.createElement('div');
                    wrapper.style.position = 'relative';
                    wrapper.style.display = 'inline-block';

                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.addEventListener('click', () => {
                        fullImage.src = img.src;
                        fullModal.show();
                    });

                    // Add remove button
                    const removeBtn = document.createElement('button');
                    removeBtn.innerHTML = '&times;';
                    removeBtn.className = 'btn btn-danger btn-sm';
                    removeBtn.style.position = 'absolute';
                    removeBtn.style.top = '-5px';
                    removeBtn.style.right = '-5px';
                    removeBtn.style.width = '25px';
                    removeBtn.style.height = '25px';
                    removeBtn.style.padding = '0';
                    removeBtn.style.borderRadius = '50%';
                    removeBtn.style.fontSize = '18px';
                    removeBtn.style.lineHeight = '1';
                    removeBtn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        selectedFiles.splice(index, 1);
                        updatePreview();
                    });

                    wrapper.appendChild(img);
                    wrapper.appendChild(removeBtn);
                    previewContainer.appendChild(wrapper);
                }
            });

            // Update status
            if (selectedFiles.length > 0) {
                statusEl.textContent = `${selectedFiles.length} photo(s) selected`;
                statusEl.className = 'fw-semibold text-success small';
            } else {
                statusEl.textContent = '';
            }
        }

        //Convert a single image to a full-page fitted PDF
        async function convertImageToPdf(file) {
            const pdf = new jsPDF({ orientation: 'portrait', unit: 'pt', format: 'a4' });

            const imgData = await readFileAsDataURL(file);
            const img = new Image();
            img.src = imgData;
            await new Promise(r => (img.onload = r));

            const pageWidth = pdf.internal.pageSize.getWidth();
            const pageHeight = pdf.internal.pageSize.getHeight();

            // Get image's aspect ratio
            const imgRatio = img.width / img.height;
            const pageRatio = pageWidth / pageHeight;

            let renderWidth, renderHeight, xOffset, yOffset;

            if (imgRatio > pageRatio) {
                // Image is wider → fit width, adjust height
                renderWidth = pageWidth;
                renderHeight = pageWidth / imgRatio;
                xOffset = 0;
                yOffset = (pageHeight - renderHeight) / 2; // center vertically
            } else {
                // Image is taller → fit height, adjust width
                renderHeight = pageHeight;
                renderWidth = pageHeight * imgRatio;
                xOffset = (pageWidth - renderWidth) / 2; // center horizontally
                yOffset = 0;
            }

            pdf.addImage(img, 'JPEG', xOffset, yOffset, renderWidth, renderHeight);
            return pdf.output('blob');
        }

        function readFileAsDataURL(file) {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = e => resolve(e.target.result);
                reader.onerror = reject;
                reader.readAsDataURL(file);
            });
        }

        // ✅ Handle upload
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (!selectedFiles.length) {
                statusEl.textContent = '⚠️Please select at least one file.';
                return;
            }

            const images = selectedFiles.filter(f => f.type.startsWith('image/'));
            const pdfs = selectedFiles.filter(f => f.type === 'application/pdf');
            const formData = new FormData();

            formData.append('action', 'saveQrUpload');
            formData.append('t_code', '<?= htmlspecialchars($t_code) ?>');

            try {
                // Convert and append each image as a separate PDF
                for (let i = 0; i < images.length; i++) {
                    statusEl.textContent = `⏳ Converting image ${i + 1} of ${images.length}...`;
                    const pdfBlob = await convertImageToPdf(images[i]);
                    formData.append('t_file[]', pdfBlob, images[i].name.replace(/\.[^.]+$/, '') + '.pdf');
                }

                // Append any existing PDFs directly
                pdfs.forEach(f => formData.append('t_file[]', f, f.name));

                statusEl.textContent = 'Uploading...';
                const res = await fetch('trackFunctions.php', { method: 'POST', body: formData });
                const text = await res.text();
                const data = JSON.parse(text);

                if (data.success) {
                    statusEl.innerHTML = 'Uploaded, closing...';
                    form.reset();
                    previewContainer.innerHTML = '';
                    selectedFiles = [];

                    setTimeout(() => {
                        window.close();
                        window.location.href = "about:blank";
                    }, 1500);
                } else {
                    statusEl.innerHTML = 'Error: ' + (data.message || 'Upload failed.');
                }
            } catch (err) {
                statusEl.innerHTML = 'Unexpected error: ' + err.message;
            }
        });
    </script>

</body>

</html>