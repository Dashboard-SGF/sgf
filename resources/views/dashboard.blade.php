@extends('layouts.app')

@section('title', "SGF — Visão Financeira Obra {$obra_ativa}")

@section('content')
    <section class="hero">
        <div>
            <div class="eyebrow">Módulo 01 · Suprimentos & Financeiro</div>
            <h1>Obra {{ $obra_ativa ?? 'Geral' }}</h1>
            <p>Acompanhe orçamento, custos, pedidos e materiais de maior impacto em um único painel.</p>
        </div>
    </section>

    <form method="GET" action="{{ route('dashboard', $obra_ativa) }}" class="filters" aria-label="Filtro de busca global" style="grid-template-columns: 1fr auto; align-items: end;">
    <form method="GET" action="{{ route('dashboard', $obra_ativa, false) }}" class="filters" aria-label="Filtro de busca global" style="grid-template-columns: 1fr auto; align-items: end;">
        <div class="field search-field">
            <label for="searchInput">Busca global no banco de dados</label>
            <input class="control" id="searchInput" name="search" type="search"
                placeholder="Buscar por Código do Item, Descrição ou Origem (SINAPI, PRÓPRIA...)" value="{{ request('search') }}" autocomplete="off">
        </div>

        <button class="btn primary filter-button" type="submit">Buscar</button>
    </form>

    <section class="kpis" aria-label="Indicadores financeiros">
        <article class="card kpi">
            <div class="kpi-top">
                <div>
                    <div class="kpi-label">Orçamento aprovado</div>
                    <div class="kpi-value">R$ {{ number_format($kpis['orcamento_total'], 2, ',', '.') }}</div>
                    <div class="kpi-sub">
                        {{ $kpis['orcamento_custom'] ? 'Meta definida pelo gestor' : 'Soma total dos serviços calculados' }}
                    </div>
                </div>
                <button class="btn icon ghost" type="button" data-action="open-budget-modal" title="Editar Orçamento Aprovado" style="border: 1px solid var(--line);">
                    ✏️
                </button>
            </div>
        </article>

        <article class="card kpi">
            <div class="kpi-top">
                <div>
                    <div class="kpi-label">Total gasto</div>
                    <div class="kpi-value">R$ {{ number_format($kpis['total_gasto'], 2, ',', '.') }}</div>
                    <div class="kpi-sub">{{ $kpis['percentual_executado'] }}% do orçamento aprovado</div>
                </div>
            </div>
            <div class="progress" aria-label="Percentual executado do orçamento">
                <i style="width: {{ min($kpis['percentual_executado'], 100) }}%"></i>
            </div>
        </article>

        <article class="card kpi {{ $kpis['is_estourado'] ? 'danger-card' : '' }}">
            <div class="kpi-top">
                <div>
                    <div class="kpi-label">Saldo remanescente</div>
                    <div class="kpi-value">R$ {{ number_format($kpis['saldo'], 2, ',', '.') }}</div>
                    <div class="kpi-sub">
                        {{ $kpis['is_estourado'] ? 'Orçamento excedido' : 'Disponível para novas despesas' }}
                    </div>
                </div>
            </div>
        </article>

        <article class="card kpi">
            <div class="kpi-label">Itens que exigem atenção</div>
            <div class="status-grid">
                <div class="status-chip">Pendentes<strong>{{ $kpis['pedidos_pendentes'] }}</strong></div>
                <div class="status-chip danger-status">
                    Divergências<strong>{{ $kpis['com_divergencia'] }}</strong></div>
            </div>
        </article>
    </section>

    <section class="section-grid">
        <article class="card section-card">
            <div class="section-head">
                <div>
                    <h2>Execução do orçamento</h2>
                    <p>Comparativo entre valor realizado e saldo disponível.</p>
                </div>
                <span class="pill">Orçado × realizado</span>
            </div>

            <div class="budget-summary">
                <div class="donut" style="--executed: {{ min($kpis['percentual_executado'], 100) }}%;">
                    <div class="donut-center">
                        <strong>{{ $kpis['percentual_executado'] }}%</strong>
                        <span>executado</span>
                    </div>
                </div>

                <div class="budget-values">
                    <div><span>Orçamento Aprovado</span><strong>R$ {{ number_format($kpis['orcamento_total'], 2, ',', '.') }}</strong></div>
                    <div><span>Gasto Realizado</span><strong>R$ {{ number_format($kpis['total_gasto'], 2, ',', '.') }}</strong></div>
                    <div><span>Saldo Remanescente</span><strong style="color: {{ $kpis['is_estourado'] ? 'var(--danger)' : 'var(--ink)' }};">R$ {{ number_format($kpis['saldo'], 2, ',', '.') }}</strong></div>
                </div>
            </div>
        </article>

        <article class="card section-card" id="abc">
            <div class="section-head">
                <div>
                    <h2>Curva ABC de custos</h2>
                    <p>Insumos ordenados pelo impacto financeiro acumulado.</p>
                </div>
                <a href="{{ route('dashboard.curva-abc', $obra_ativa) }}" class="pill" style="text-decoration: none;">Ver Detalhes →</a>
                <a href="{{ route('dashboard.curva-abc', $obra_ativa, false) }}" class="pill" style="text-decoration: none;">Ver Detalhes →</a>
            </div>

            <div class="abc-list">
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
                    <div class="empty-state">Nenhum insumo cadastrado para esta obra.</div>
                @endforelse
            </div>

            <div class="legend">
                <span><b>A</b> · alto impacto</span>
                <span><b>B</b> · médio impacto</span>
                <span><b>C</b> · baixo impacto</span>
            </div>
        </article>
    </section>

    <section class="card table-card" id="orders">
        <div class="table-head">
            <div>
                <h2>Serviços e Composições de Custos</h2>
                <p>Lista de itens cadastrados no orçamento da obra.</p>
            </div>
            <a href="{{ route('dashboard.servicos', $obra_ativa) }}" class="pill" style="text-decoration: none;" id="visibleOrdersCount">{{ count($pedidos) }} itens (Ver Todos →)</a>
            <a href="{{ route('dashboard.servicos', $obra_ativa, false) }}" class="pill" style="text-decoration: none;" id="visibleOrdersCount">{{ count($pedidos) }} itens (Ver Todos →)</a>
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
                            <td style="font-size: 11px; color: var(--muted); max-width: 200px;">
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

    <section class="card section-card occurrences-section" id="occurrences">
        <div class="section-head">
            <div>
                <h2>Central de Ocorrências e Divergências</h2>
                <p>Serviços pendentes, com divergência ou observações operacionais registradas.</p>
            </div>
            <a href="{{ route('dashboard.ocorrencias', $obra_ativa) }}" class="pill danger-pill" style="text-decoration: none;">{{ count($ocorrencias) }} ativas (Ver Painel →)</a>
            <a href="{{ route('dashboard.ocorrencias', $obra_ativa, false) }}" class="pill danger-pill" style="text-decoration: none;">{{ count($ocorrencias) }} ativas (Ver Painel →)</a>
        </div>

        <div class="occ-list">
            @forelse ($ocorrencias as $occ)
                <article class="occ">
                    <div class="occ-icon" aria-hidden="true">!</div>
                    <div>
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <strong>{{ $occ['codigo'] }}</strong>
                            <span class="status {{ $occ['status'] }}">{{ $occ['tag'] }}</span>
                        </div>
                        <p style="margin-top: 4px; font-weight: 600;">{{ $occ['desc'] }}</p>
                        @if(!empty($occ['observacao']))
                            <div style="font-size: 12px; color: var(--ink); background: #fff; padding: 6px 10px; border-radius: 8px; border: 1px solid var(--line); margin-top: 6px;">
                                <strong>Observação:</strong> {{ $occ['observacao'] }}
                            </div>
                        @endif
                    </div>
                </article>
            @empty
                <div class="empty-state">Nenhuma ocorrência registrada para esta obra. Todos os itens estão entregues/normais.</div>
            @endforelse
        </div>
    </section>
@endsection
