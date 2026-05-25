<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Anggota</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Detail Profil Anggota</h4>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 35%;">Kode Anggota</th>
                            <td style="width: 5%;">:</td>
                            <td><strong>{{ $anggota['kode'] }}</strong></td>
                        </tr>
                        <tr>
                            <th>Nama Lengkap</th>
                            <td>:</td>
                            <td>{{ $anggota['nama'] }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>:</td>
                            <td>{{ $anggota['email'] }}</td>
                        </tr>
                        <tr>
                            <th>Telepon</th>
                            <td>:</td>
                            <td>{{ $anggota['telepon'] }}</td>
                        </tr>
                        <tr>
                            <th>Alamat</th>
                            <td>:</td>
                            <td>{{ $anggota['alamat'] }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td>:</td>
                            <td>
                                @if ($anggota['status'] == 'Aktif')
                                    <span class="badge bg-success fs-6">{{ $anggota['status'] }}</span>
                                @else
                                    <span class="badge bg-danger fs-6">{{ $anggota['status'] }}</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer text-end bg-white">
                    <a href="{{ url('/anggota') }}" class="btn btn-secondary">
                        &larr; Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>