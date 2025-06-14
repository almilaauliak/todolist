<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ToDoList App Almila</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background: linear-gradient(135deg, #f8fafc 0%, #e0e7ef 100%); min-height: 100vh; }
        .main-card { border-radius: 1.5rem; box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.12); background: #fff; }
        .table th, .table td { vertical-align: middle !important; }
        .btn-primary { border-radius: 1rem; padding-left: 1.2rem; padding-right: 1.2rem; font-weight: 600; }
        .btn-primary i { margin-right: 0.4rem; }
        .badge.bg-warning, .badge.bg-success { font-size: 1em; padding: 0.5em 1em; border-radius: 1.5rem; }
        h2 { font-weight: 800; letter-spacing: 2px; color: #2563eb; }
        .modal-content { border-radius: 1rem; }
        .modal-header { background: #f1f5fa; border-bottom: 0; }
        .modal-title { font-weight: 700; color: #2563eb; }
        .form-label { font-weight: 500; }
        .strike { text-decoration: line-through; color: #a0aec0 !important;}
        @media (max-width: 576px) {
            .main-card { padding: 0 !important; }
            .table-responsive { font-size: 0.9em; }
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <h2 class="mb-5 text-center">ToDoList App Almila</h2>
        <div class="d-flex justify-content-end mb-4">
            <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#tambahTugasModal">
                <i class="bi bi-plus-circle"></i> Tambah Tugas
            </button>
        </div>
            <div class="main-card mx-auto p-3" style="max-width: 800px;">
                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead class="table-light rounded">
        <tr>
            <th class="text-center" style="width: 40px;">&nbsp;</th>
            <th class="text-center" style="width: 50px;">No</th>
            <th>Title</th>
            <th class="text-center" style="width: 110px;">Status</th>
            <th class="text-center" style="width: 120px;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @foreach ($tasks as $task)
            <tr>
                <!-- Checkbox hanya untuk data database -->
                <td class="text-center">
                    @if(!empty($task['from_db']) && $task['from_db'])
                        <input type="checkbox"
                            class="form-check-input task-checkbox"
                            data-id="{{ $task['id'] }}"
                            {{ $task['status'] == 'selesai' ? 'checked' : '' }}>
                    @endif
                </td>
                <td class="text-center fw-bold">{{ $no++ }}</td>
                <td>
                    <span id="title-{{ $task['id'] }}"
                        class="{{ ($task['status']=='selesai') ? 'strike' : '' }}">
                        {{ $task['title'] }}
                    </span>
                </td>
                <td class="text-center">
                    <span id="status-badge-{{ $task['id'] }}">
                    @if($task['status'] == 'belum')
                        <span class="badge bg-warning text-dark shadow-sm">Belum</span>
                    @else
                        <span class="badge bg-success shadow-sm">Selesai</span>
                    @endif
                    </span>
                </td>
                <td class="text-center">
                    @if(!empty($task['from_db']) && $task['from_db'])
                        <button class="btn btn-sm btn-warning me-1" onclick="showEditModal({{ $task['id'] }}, '{{ addslashes($task['title']) }}', '{{ $task['status'] }}')">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <form action="{{ route('todolist.destroy', $task['id']) }}" method="POST" style="display:inline;" onsubmit="return confirm('Yakin hapus tugas ini?');">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    @else
                        <button class="btn btn-sm btn-outline-secondary" title="Tidak bisa diedit" disabled><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-outline-secondary" title="Tidak bisa dihapus" disabled><i class="bi bi-trash"></i></button>
                    @endif
                </td>
            </tr>
        @endforeach
        @if(count($tasks) == 0)
            <tr><td colspan="5" class="text-center text-muted">Belum ada tugas.</td></tr>
        @endif
    </tbody>

                </table>
            </div>
        </div>

        <!-- Modal Tambah Tugas -->
        <div class="modal fade" id="tambahTugasModal" tabindex="-1" aria-labelledby="tambahTugasLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content animate__animated animate__fadeInDown">
                    <form action="{{ route('todolist.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="tambahTugasLabel"><i class="bi bi-pencil-square"></i> Tambah Tugas Baru</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body pb-1">
                            <div class="mb-3">
                                <label for="title" class="form-label">Judul Tugas</label>
                                <input type="text" class="form-control shadow-sm" id="title" name="title" placeholder="Misal: Kerjakan laporan kuliah" required>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select shadow-sm" id="status" name="status" required>
                                    <option value="belum" selected>Belum</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit Tugas -->
        <div class="modal fade" id="editTugasModal" tabindex="-1" aria-labelledby="editTugasLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content animate__animated animate__fadeInDown">
                    <form id="editTaskForm" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="editTugasLabel"><i class="bi bi-pencil"></i> Edit Tugas</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body pb-1">
                            <input type="hidden" name="id" id="editTaskId">
                            <div class="mb-3">
                                <label for="edit_title" class="form-label">Judul Tugas</label>
                                <input type="text" class="form-control shadow-sm" id="edit_title" name="title" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_status" class="form-label">Status</label>
                                <select class="form-select shadow-sm" id="edit_status" name="status" required>
                                    <option value="belum">Belum</option>
                                    <option value="selesai">Selesai</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer border-0">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/animate.css@4.1.1/animate.min.css"/>
    <script>
        // AJAX update status (tanpa reload) + update badge & strike
        document.querySelectorAll('.task-checkbox').forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                var id = this.dataset.id;
                var checked = this.checked;
                var status = checked ? 'selesai' : 'belum';

                fetch('/update-status/' + id, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({status: status})
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update judul coret/tidak
                        var title = document.getElementById('title-' + id);
                        if (title) {
                            if (checked) {
                                title.classList.add('strike');
                            } else {
                                title.classList.remove('strike');
                            }
                        }
                        // Update badge status
                        var badge = document.getElementById('status-badge-' + id);
                        if (badge) {
                            if (checked) {
                                badge.innerHTML = '<span class="badge bg-success shadow-sm">Selesai</span>';
                            } else {
                                badge.innerHTML = '<span class="badge bg-warning text-dark shadow-sm">Belum</span>';
                            }
                        }
                    }
                });
            });
        });

        // Modal edit tugas
        function showEditModal(id, title, status) {
            document.getElementById('editTaskId').value = id;
            document.getElementById('edit_title').value = title;
            document.getElementById('edit_status').value = status;
            var form = document.getElementById('editTaskForm');
            form.action = '/edit/' + id;
            var editModal = new bootstrap.Modal(document.getElementById('editTugasModal'));
            editModal.show();
        }
    </script>
</body>
</html>
