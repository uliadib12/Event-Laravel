<html lang="en">

<head>
    @foreach ($lombas->take(1) as $lomba)
        <title>{{ $lomba->event->nama_lomba }} - Lomba List</title>
    @endforeach
</head>

@include('widgets.head')


<body>
    @include('widgets.navbar')

    <div class="lomba-content">
        @foreach ($lombas->take(1) as $lomba)
            <h5 class="evetnName"><b>{{ $lomba->event->nama_lomba }}</b> </h5>
        @endforeach
        <div class="wrap-card centered">
            @foreach ($lombas as $lomba)
                <div class='tile'>
                    <img class='tile-img'
                        style="background-image: url('{{ asset('storage/lomba/poster/' . $lomba->id . '/' . $lomba->poster) }}');" />
                    <div class='tile-info'>
                        <div class="title-text-top">
                            <h1>{{ $lomba->nama_lomba }}</h1>
                            <p>{{ $lomba->keterangan }}</p>
                        </div>
                        <div class="title-text">
                            <p class="card-text">Ruangan: {{ $lomba->ruangan_lomba }}</p>
                            <p class="card-text">
                                <span class="status">
                                    Kuota Maximum - <i class="fa-solid fa-user-group"></i>{{ $lomba->kuota_lomba }}
                                </span>
                            </p>
                            <p class="card-text">Pelaksanaan:
                                {{ \Carbon\Carbon::parse($lomba->pelaksanaan_lomba)->format('l, j F Y') }}</p>
                        </div>
                        <button class="jointbtn"> Joint Event</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</body>

</html>
