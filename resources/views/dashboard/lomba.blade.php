@extends('dashboard')

@section('content')
    <h1>Lomba</h1>
    <p>Welcome to the Lomba.</p>
    <div class="card detail">
        <div class="detail-header">
            <div id="alert-container">
                <h2>All</h2>
                @if (session('status'))
                    <h2 class="alert @if (session('status') == 'error') alert-error @else alert-success @endif">
                        {{ session('status') }}
                    </h2>
                @elseif (session('success'))
                    <h2 class="alert alert-success">
                        {{ session('success') }}
                    </h2>
                @elseif(session('error'))
                    <h2 class="alert alert-error">
                        {{ session('error') }}
                    </h2>
                @elseif($errors->any())
                    <div class="alert alert-error ">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            <div class="crud">
                <button class="show-modal" data-modal="addModal">
                    <i class="fa-solid fa-notes-medical"></i>
                    <h5>Add</h5>
                </button>
                <button title="Delete" class="del-modal" data-modal="deletecheckModal">
                    <i class="fa-solid fa-trash-arrow-up"></i>
                    <h5>Delete</h5>
                </button>
            </div>
        </div>

        @if (count($lombas) > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <span class="custom-checkbox">
                                <input type="checkbox" id="checkbox_selectAll">
                                <label for="checkbox_selectAll"></label>
                            </span>
                        </th>
                        <th>No</th>
                        <th>Nama Event</th>
                        <th>Nama Lomba</th>
                        <th>Keterangan</th>
                        <th>Ruangan Lomba</th>
                        <th>Kuota Lomba</th>
                        <th>Pelaksanaan Lomba</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lombas as $lomba)
                        <tr>
                            <td>
                                <span class="custom-checkbox">
                                    <input type="checkbox" data-id="{{ $lomba->id }}">
                                    <label for="checkbox_selectAll"></label>
                                </span>
                            </td>
                            <td class="openInfoModalBtn" data-lomba-id="{{ $lomba->id }}">{{ $loop->iteration }}</td>
                            <td>{{ $lomba->event->nama_lomba }}</td>
                            <td>{{ $lomba->nama_lomba }}</td>
                            <td class="descript">{{ $lomba->keterangan }}</td>
                            <td>{{ $lomba->ruangan_lomba }}</td>
                            <td>
                                <span class="status confirmed">
                                    <i class="fa-solid fa-user-group">{{ $lomba->kuota_lomba }}</i>
                                </span>
                            </td>
                            <td>{{ $lomba->pelaksanaan_lomba }}</td>
                            <td>
                                <button class="editbtn" type="button" data-lomba-id="{{ $lomba->id }}">
                                    <i class="fa-solid fa-pen-clip"></i>
                                </button>
                                {{-- <a href="{{ route('dashboard.lomba.edit', ['lomba_id' => $lomba->id]) }}"
                                    class="btn btn-warning">Edit</a> --}}
                                {{-- <form action="{{ route('dashboard.lomba.destroy', ['lomba_id' => $lomba->id]) }}"
                                    method="POST" style="display: inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </form> --}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Tidak ada Lomba.</p>
        @endif

        <div id="infoModal" class="tr-modal">
            <div class="tr-modal-content">
                <div class="tr-det">
                    <h2 id="info_nama_lomba"></h2>
                    <strong><i class="fa-solid fa-hashtag"></i>
                        <span id="id"></span>
                    </strong>
                </div>
                <div class="banner-container">
                    <img class="banner" id="info_banner" src="{{ asset('assets/images/carrousel1.JPG') }}" alt="banner">
                </div>
                <ul>
                    <li><strong>Deskripsi:</strong> <span id="info_deskripsi"></span></li>
                    <li class="tr-det inf">
                        <strong><i class="fa-solid fa-map-location-dot"></i>
                            <span id="info_tempat"></span>
                        </strong>
                        <strong><i class="fa-solid fa-users-line"></i>
                            <span id="info_kuota"></span>
                        </strong>
                        <strong><i class="fa-solid fa-people-group"></i>
                            <span id="info_penyelenggara_id"></span>
                        </strong>
                    </li>
                    <li><strong>Pendaftaran:</strong> <span id="info_tanggal_pendaftaran"></span></li>
                    <li><strong>Penutupan:</strong> <span id="info_tanggal_penutupan_pendaftaran"></span></li>
                    <li><strong>Pelaksanaan:</strong> <span id="info_tanggal_pelaksanaan"></span></li>
                </ul>
            </div>
        </div>

        <div class="modal-overlay"></div>

        <!-- Modal Add Lomba -->
        <div id="addModal" class="modal">
            <div class="modal-content">
                <h2>Tambah Lomba</h2>
                <form method="POST" action="{{ route('dashboard.lomba.store') }}">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event_id }}">

                    <label for="nama_lomba">Nama Lomba:</label>
                    <input type="text" name="nama_lomba" required>

                    <label for="keterangan">Keterangan:</label>
                    <textarea name="keterangan" required></textarea>

                    <label for="ruangan_lomba">Ruangan Lomba:</label>
                    <input type="text" name="ruangan_lomba" required>

                    <label for="kuota_lomba">Kuota Lomba:</label>
                    <input type="number" name="kuota_lomba" required>

                    <label for="pelaksanaan_lomba">Pelaksanaan Lomba:</label>
                    <input type="datetime-local" name="pelaksanaan_lomba" required>

                    <div class="CC">
                        <button type="submit">Add</button>
                        <button type="button" id="closeButton">Close</button>
                    </div>
                </form>
            </div>
        </div>



        <!-- Modal Edit -->
        <div id="editModal" class="modal" style="display: none;">
            <div class="modal-content">
                <h2>Edit Lomba</h2>
                <form method="POST" action="/dashboard/lomba/update">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="id" id="id">

                    <label for="nama_lomba">Nama Lomba:</label>
                    <input type="text" name="nama_lomba" id="nama_lomba" required>

                    <label for="keterangan">Keterangan:</label>
                    <textarea name="keterangan" id="keterangan" required></textarea>

                    <label for="ruangan_lomba">Ruangan Lomba:</label>
                    <input type="text" name="ruangan_lomba" id="ruangan_lomba" required>

                    <label for="kuota_lomba">Kuota Lomba:</label>
                    <input type="number" name="kuota_lomba" id="kuota_lomba" required>

                    <label for="pelaksanaan_lomba">Pelaksanaan Lomba:</label>
                    <input type="datetime-local" id="pelaksanaan_lomba" required>

                    <div class="CC">
                        <button type="submit">Confirm</button>
                        <button type="button" id="closeButton">Close</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Modal Delete -->
        <div id="deleteModal" class="modal">
            <div class="modal-content">
                <h2>Delete</h2>
                <div class="message">Confirm untuk hapus data!</div>
                <div class="tr-det">
                    <h2 id="del_nama_lomba"></h2>
                    <strong><i class="fa-solid fa-hashtag"></i>
                        <span id="del_id"></span>
                    </strong>
                </div>
                <div class="CC">
                    <button type="submit" id="confirmButton">Confirm</button>
                    <button type="button" id="closeButton">Tutup</button>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="{{ asset('assets/js/modallomba.js') }}"></script>
    <script src="{{ asset('assets/js/modal/lombaModal.js') }}"></script>
@endsection
