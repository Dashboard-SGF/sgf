@extends('layouts.app')

@section('title', "SGF — Serviços Obra {$obra_ativa}")

@section('content')
    <section class="hero">
        <div>
            <div class="eyebrow">Módulo 01 · Gestão de Serviços</div>
            <h1>Serviços & Composição de Custos — Obra {{ $obra_ativa ?? 'Geral' }}</h1>
            <p>Listagem completa e gerenciamento individual de status e observações dos itens.</p>
        </div>
    </section>

    <form method="GET" action="{{ route('dashboard.servicos', $obra_ativa) }}" class="filters" aria-label="Filtro de busca global" style="grid-template-columns: 1fr auto; align-items: end;">
    <form method="GET" action="{{ route('dashboard.servicos', $obra_ativa, false) }}" class="filters" aria-label="Filtro de busca global" style="grid-template-columns: 1fr auto; align-items: end;">
        <div class="field search-field">
            <label for="searchInput">Busca global na tabela de serviços</label>
            <input class="control" id="searchInput" name="search" type="search"
                placeholder="Buscar por código, descrição ou tipo (SINAPI, PRÓPRIA...)" value="{{ request('search') }}" autocomplete="off">
        </div>

        <button class="btn primary filter-button" type="submit">Buscar</button>
    </form>

    <section class="card table-card">
        <div class="table-head">
            <div>
                <h2>Tabela de Serviços & Composições</h2>
                <p>Altere status ou observações de qualquer item da composição.</p>
            </div>
            <span class="pill" id="visibleOrdersCount">{{ count($pedidos) }} itens cadastrados</span>
        </div>

        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Origem / Tipo</th>
                        <th>Data</th>
                        <th>Serviço / Insumo</th>
                        <th>Quantidade</th>
                        <th>Valor total</th>
                        <th>Status</th>
                        <th>Observação</th>
                        <th style="text-align: center;">Ações</th>
                    </tr>
                </thead>
                <tbody id="ordersTable">
                    @forelse ($pedidos as $pedido)
                        <tr data-order-row
                            data-search="{{ strtolower($pedido['codigo'] . ' ' . $pedido['codigo_item'] . ' ' . $pedido['fornecedor'] . ' ' . $pedido['item']) }}">
                            <td><strong>{{ $pedido['codigo'] }}</strong></td>
                            <td>{{ $pedido['fornecedor'] }}</td>
                            <td>{{ $pedido['data'] }}</td>
                            <td>{{ $pedido['item'] }}</td>
                            <td>{{ $pedido['quantidade'] }}</td>
                            <td><strong>R$ {{ number_format($pedido['valor'], 2, ',', '.') }}</strong></td>
                            <td>
                                <span class="status {{ $pedido['status'] }}">{{ $pedido['status_label'] }}</span>
                            </td>
                            <td style="font-size: 11px; color: var(--muted); max-width: 240px;">
                                {{ $pedido['observacao'] ?? '—' }}
                            </td>
                            <td style="text-align: center;">
                                <button class="btn icon ghost" type="button"
                                    data-action="open-status-modal"
                                    data-id="{{ $pedido['id'] }}"
                                    data-code="{{ $pedido['codigo'] }}"
                                    data-item="{{ $pedido['item'] }}"
                                    data-status="{{ $pedido['status'] }}"
                                    data-observacao="{{ $pedido['observacao'] ?? '' }}"
                                    title="Alterar Status / Ocorrência"
                                    style="border: 1px solid var(--line); padding: 2px 6px;">
                                    ⚙️
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="empty-state">Nenhum serviço encontrado com os filtros atuais.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="order-mobile" id="ordersMobile">
            @foreach ($pedidos as $pedido)
                <article class="order-card" data-order-card
                    data-search="{{ strtolower($pedido['codigo'] . ' ' . $pedido['codigo_item'] . ' ' . $pedido['fornecedor'] . ' ' . $pedido['item']) }}">
                    <div class="order-top">
                        <strong>{{ $pedido['codigo'] }}</strong>
                        <span class="status {{ $pedido['status'] }}">{{ $pedido['status_label'] }}</span>
                    </div>
                    <div class="order-muted">{{ $pedido['fornecedor'] }} · {{ $pedido['item'] }}</div>
                    @if(!empty($pedido['observacao']))
                        <div style="font-size: 11px; color: var(--warning); margin-top: 6px;">Obs: {{ $pedido['observacao'] }}</div>
                    @endif
                    <div class="order-bottom">
                        <span>{{ $pedido['data'] }}</span>
                        <div>
                            <strong>R$ {{ number_format($pedido['valor'], 2, ',', '.') }}</strong>
                            <button class="btn ghost" type="button"
                                data-action="open-status-modal"
                                data-id="{{ $pedido['id'] }}"
                                data-code="{{ $pedido['codigo'] }}"
                                data-item="{{ $pedido['item'] }}"
                                data-status="{{ $pedido['status'] }}"
                                data-observacao="{{ $pedido['observacao'] ?? '' }}"
                                style="margin-left: 8px; padding: 2px 8px; font-size: 11px;">
                                Status
                            </button>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection

