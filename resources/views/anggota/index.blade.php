<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Anggota Perpustakaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="card-title mb-4 text-center">Daftar Anggota Perpustakaan</h2>
            
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover align-middle">
                    <thead class="table-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Kode Anggota</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($anggota_list as $index => $anggota)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $anggota['kode'] }}</td>
                            <td>{{ $anggota['nama'] }}</td>
                            <td>{{ $anggota['email'] }}</td>
                            <td class="text-center">
                                @if ($anggota['status'] == 'Aktif')
                                    <span class="badge bg-success">{{ $anggota['status'] }}</span>
                                @else
                                    <span class="badge bg-danger">{{ $anggota['status'] }}</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ url('/anggota/' . $anggota['id']) }}" class="btn btn-sm btn-primary">Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>