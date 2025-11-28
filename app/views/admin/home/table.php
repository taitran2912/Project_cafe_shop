<div class="content">
        <div class="content-header">
            <h2>Danh sách các bàn</h2>
            <button class="btn-add" id="btnAddTable">+ Thêm bàn</button>
        </div>

        <div class="table-grid" id="tableGrid">
            <!-- Tables will be inserted here -->
        </div>
    </div>

    <!-- Add/Edit Table Modal -->
    <div class="modal" id="tableModal">
        <div class="modal-content">
            <div class="modal-header" id="modalTitle">Thêm bàn mới</div>
            <form id="tableForm">
                <div class="form-group">
                    <label for="tableNumber">Số bàn:</label>
                    <input type="number" id="tableNumber" required min="1">
                </div>
                <div class="form-group">
                    <label for="branchSelect">Chi nhánh:</label>
                    <select id="branchSelect" required>
                        <option value="">-- Chọn chi nhánh --</option>
                        <option value="Chi nhánh 1">Chi nhánh 1</option>
                        <option value="Chi nhánh 2">Chi nhánh 2</option>
                        <option value="Chi nhánh 3">Chi nhánh 3</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="capacity">Sức chứa (người):</label>
                    <input type="number" id="capacity" required min="1" max="20">
                </div>
                <div class="form-group">
                    <label for="location">Vị trí:</label>
                    <input type="text" id="location" placeholder="VD: Tầng 1, cạnh cửa sổ">
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" id="btnCloseModal">Hủy</button>
                    <button type="submit" class="btn-submit">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <!-- QR Code Modal -->
    <div class="modal" id="qrModal">
        <div class="modal-content">
            <div class="modal-header">QR Code Bàn <span id="qrTableNumber"></span></div>
            <div class="qr-code-container">
                <p class="qr-code-info" id="qrInfo">Chi nhánh: <span id="qrBranch"></span></p>
                <canvas id="qrCanvas"></canvas>
                <div>
                    <button type="button" class="btn-print" id="btnPrintQR">🖨️ In QR Code</button>
                </div>
                <button type="button" class="btn-cancel" style="width: 100%; margin-top: 10px;" id="btnCloseQRModal">Đóng</button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        // Data Management
        let tables = [
<?php foreach ($data['tables'] as $table): ?>
            { 
                id: <?= $tables['ID'] ?>, 
                number: <?= $tables['No'] ?>, 
                branch: '<?= htmlspecialchars($tables['Branch_Name'], ENT_QUOTES) ?>', 
                status: '<?= $tables['Status'] ?>' 
                location: '<?= htmlspecialchars($tables['Address'], ENT_QUOTES) ?>'
            },
<?php endforeach; ?>
        ];

        let editingTableId = null;

        // Render Tables
        function renderTables(tablesToRender = tables) {
            const grid = document.getElementById('tableGrid');
            grid.innerHTML = '';

            tablesToRender.forEach(table => {
                const tableCard = document.createElement('div');
                tableCard.className = 'table-card';


                tableCard.innerHTML = `
                    <div class="table-number">Bàn ${table.number}</div>
                    <span class="table-status ${statusClass}">${statusText}</span>
                    <div class="table-info">
                        <div class="info-row">
                            <span class="info-label">Chi nhánh:</span>
                            <span class="info-value">${table.branch}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Vị trí:</span>
                            <span class="info-value">${table.location}</span>
                        </div>
                    </div>
                    <div class="table-actions">
                        <button class="btn-action btn-edit" onclick="editTable(${table.id})">Sửa</button>
                        <button class="btn-action btn-delete" onclick="deleteTable(${table.id})">Xoá</button>
                        <button class="btn-action btn-qrcode" onclick="showQRCode(${table.id})">QR</button>
                    </div>
                `;

                grid.appendChild(tableCard);
            });
        }
        renderTables();
    </script>