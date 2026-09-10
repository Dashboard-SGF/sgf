@extends('layouts.app')

@section('title', "SGF — Curva ABC Obra {$obra_ativa}")

@section('content')
    <section class="hero">
        <div>
            <div class="eyebrow">Módulo 01 · Curva ABC de Custos</div>
            <h1>Curva ABC de Insumos — Obra {{ $obra_ativa ?? 'Geral' }}</h1>
            <p>Análise de impacto financeiro acumulado por insumo e serviço cadastrado.</p>
        </div>
    </section>

    <form method="GET" action="{{ route('dashboard.curva-abc', $obra_ativa) }}" class="filters" aria-label="Filtro de busca global" style="grid-template-columns: 1fr auto; align-items: end;">
    <form method="GET" action="{{ route('dashboard.curva-abc', $obra_ativa, false) }}" class="filters" aria-label="Filtro de busca global" style="grid-template-columns: 1fr auto; align-items: end;">
        <div class="field search-field">
            <label for="searchInput">Buscar insumo ou serviço</label>
            <input class="control" id="searchInput" name="search" type="search"
                placeholder="Filtrar por nome do insumo ou código..." value="{{ request('search') }}" autocomplete="off">
        </div>

        <button class="btn primary filter-button" type="submit">Buscar</button>
    </form>

    <article class="card section-card">
        <div class="section-head">
            <div>
                <h2>Classificação Orçamentária Accumulada</h2>
                <p>Itens ordenados em ordem decrescente de valor orçado.</p>
            </div>
            <span class="pill">Classe A (80%) · Classe B (15%) · Classe C (5%)</span>
        </div>

        <div class="abc-list" style="max-height: 650px;">
            @forelse ($insumos_abc as $item)
                <div class="abc-item" data-search="{{ strtolower($item['nome']) }}">
                    <div class="abc-main">
                        <div class="abc-label">
                            <strong>{{ $item['nome'] }}</strong>
                            <span>R$ {{ number_format($item['valor'], 2, ',', '.') }}</span>
                        </div>
                        <div class="abc-bar">
                            <i class="abc-fill class-{{ $item['classe'] }}"
                                style="width: {{ $item['altura_pct'] }}%"></i>
                        </div>
                    </div>

                    <div class="class-badge class-{{ strtoupper($item['classe']) }}">
                        {{ strtoupper($item['classe']) }}
                    </div>
                </div>
            @empty
                <div class="empty-state">Nenhum insumo encontrado para a busca.</div>
            @endforelse
        </div>

        <div class="legend" style="margin-top: 20px;">
            <span><b>Classe A</b> · 80% do custo acumulado total</span>
            <span><b>Classe B</b> · 15% do custo acumulado total</span>
            <span><b>Classe C</b> · 5% do custo acumulado total</span>
        </div>
    </article>
@endsection

