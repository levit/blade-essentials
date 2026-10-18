@php
    $instituicao = auth()->user()?->instituicao;
    $logo = $instituicao?->logotipo_path ?? 'assets/img/logos/softtan.png';
@endphp
<div class="bg-white rounded-3 p-2 shadow-sm" style="width: 90px; height: 90px;">
    @if($instituicao?->site)
        <a href="{{ $instituicao->site }}">
            <img src="{{ $logo }}" style="object-fit: contain;" alt="Logo">
        </a>
    @else
        <img src="{{ $logo }}" style="object-fit: contain;" alt="Logo">
    @endif
</div>

<div>
    <p>Acesse o módulo desejado abaixo ou no topo da tela do sistema</p>
    @foreach($modulos as $mod)
        <p class="text-center fs-6 text-body">{{ $mod->nome }}</p>
    @endforeach
</div>

