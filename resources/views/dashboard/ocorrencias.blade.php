@extends('layouts.app')

@section('title', "SGF — Ocorrências Obra {$obra_ativa}")

@section('content')
    <section class="hero">
        <div>
            <div class="eyebrow">Módulo 01 · Gestão de Ocorrências</div>
            <h1>Central de Ocorrências & Inconsistências — Obra {{ $obra_ativa ?? 'Geral' }}</h1>
            <p>Acompanhamento de entregas pendentes, avarias e apontamentos de divergência no orçamento.</p>
        </div>
    </section>

    <form method="GET" action="{{ route('dashboard.ocorrencias', $obra_ativa) }}" class="filters" aria-label="Filtro de busca global" style="grid-template-columns: 1fr auto; align-items: end;">
        <div class="field search-field">
            <label for="searchInput">Buscar em ocorrências registradas</label>
            <input class="control" id="searchInput" name="search" type="search"
                placeholder="Buscar por código ou descrição da ocorrência..." value="{{ request('search') }}" autocomplete="off">
        </div>

        <button class="btn primary filter-button" type="submit">Buscar</button>
    </form>

    <section class="card section-card occurrences-section">
        <div class="section-head">
            <div>
                <h2>Ocorrências Ativas e Observações</h2>
                <p>Lista de serviços marcados com pendência, divergência ou observação operacional.</p>
            </div>
            <span class="pill danger-pill">{{ count($ocorrencias) }} ocorrências registradas</span>
        </div>

        <div class="occ-list">
            @forelse ($ocorrencias as $occ)
                <article class="occ" style="padding: 16px;">
                    <div class="occ-icon" aria-hidden="true">!</div>
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center; gap: 12px;">
                            <strong>{{ $occ['codigo'] }}</strong>
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <span class="status {{ $occ['status'] }}">{{ $occ['tag'] }}</span>
                                <button class="btn icon ghost" type="button"
                                    data-action="open-status-modal"
                                    data-id="{{ $occ['id'] }}"
                                    data-code="{{ $occ['codigo'] }}"
                                    data-item="{{ $occ['desc'] }}"
                                    data-status="{{ $occ['status'] }}"
                                    data-observacao="{{ $occ['observacao'] ?? '' }}"
                                    title="Editar Ocorrência"
                                    style="border: 1px solid var(--line); padding: 2px 6px;">
                                    ⚙️
                                </button>
                            </div>
                        </div>
                        <p style="margin-top: 6px; font-size: 13px; font-weight: 600; color: var(--ink);">{{ $occ['desc'] }}</p>
                        @if(!empty($occ['observacao']))
                            <div style="font-size: 12px; color: var(--ink); background: #fff; padding: 10px 14px; border-radius: 10px; border: 1px solid var(--line); margin-top: 8px; line-height: 1.45;">
                                <strong style="color: var(--danger);">Observação Registrada:</strong> {{ $occ['observacao'] }}
                            </div>
                        @else
                            <div style="font-size: 11px; color: var(--muted); margin-top: 4px;">Nenhuma observação textual informada.</div>
                        @endif
                    </div>
                </article>
            @empty
                <div class="empty-state" style="padding: 40px 20px;">
                    <strong>Nenhuma ocorrência pendente nesta obra.</strong>
                    <p style="margin-top: 6px; color: var(--muted);">Todos os serviços e composições de custos encontram-se em situação normal.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection

