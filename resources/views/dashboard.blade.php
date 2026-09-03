<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SGF — Dashboard de Suprimentos e Gestão Financeira de Obras">
    <title>SGF — Suprimentos & Gestão Financeira</title>
    <link rel="stylesheet" href="./css/dashboard.css">
</head>

<body>
    <div class="app">
        <aside class="sidebar">
            <div class="brand">
                <div class="brand-mark" aria-hidden="true"></div>
                <div>
                    <strong>SGF</strong>
                    <small>Gestão & Fiscalização</small>
                </div>
            </div>

            <nav class="nav" aria-label="Navegação principal">
                <a class="active" href="#overview"><span class="nav-dot"></span>Visão financeira</a>
                <a href="#abc"><span class="nav-dot"></span>Curva ABC</a>
                <a href="#orders"><span class="nav-dot"></span>Pedidos</a>
                <a href="#occurrences"><span class="nav-dot"></span>Ocorrências</a>
            </nav>

            <div class="sidebar-note">
                <strong>Unidade 1 · MVP</strong>
                <span>Dados simulados e fluxos prioritários. Sem persistência física.</span>
            </div>
        </aside>

        <main class="main">
            <header class="topbar">
                <div class="mobile-brand">
                    <div class="brand-mark" aria-hidden="true"></div>
                    <strong>SGF</strong>
                </div>

                <div class="top-actions">
                    <button class="btn" type="button" data-action="print">
                        <span aria-hidden="true">⇩</span>
                        <span class="button-label">Exportar</span>
                    </button>
                    <button class="btn primary" type="button" data-action="open-import">
                        <span aria-hidden="true">＋</span>
                        <span class="button-label">Importar Excel</span>
                    </button>
                </div>
            </header>

            <div class="container" id="overview">
                <section class="hero">
                    <div>
                        <div class="eyebrow">Módulo 01 · Suprimentos & Financeiro</div>
                        <h1>Visão financeira das obras</h1>
                        <p>Acompanhe orçamento, custos, pedidos e materiais de maior impacto em um único painel.</p>

                        <div class="meta-line">
                            <span>Atualizado pela aplicação Laravel</span>
                            <span class="dot-sep"></span>
                            <span>Visão consolidada</span>
                        </div>
                    </div>

                    <div class="mode-badge">Dados simulados · Unidade 1</div>
                </section>

                <section class="filters" aria-label="Filtros do dashboard">
                    <div class="field">
                        <label for="workFilter">Obra</label>
                        <select class="control" id="workFilter">
                            <option value="">Todas as obras</option>
                            <option value="Residencial Vista Alegre">Residencial Vista Alegre</option>
                            <option value="Torre Atlântico">Torre Atlântico</option>
                            <option value="Parque das Flores">Parque das Flores</option>
                        </select>
                    </div>

                    <div class="field">
                        <label for="startDate">Período inicial</label>
                        <input class="control" id="startDate" type="date" value="2026-08-01">
                    </div>

                    <div class="field">
                        <label for="endDate">Período final</label>
                        <input class="control" id="endDate" type="date" value="2026-09-03">
                    </div>

                    <div class="field search-field">
                        <label for="searchInput">Buscar pedido ou insumo</label>
                        <input class="control" id="searchInput" type="search" placeholder="Código, fornecedor, item..." autocomplete="off">
                    </div>

                    <button class="btn primary filter-button" id="applyFilters" type="button">Filtrar</button>
                </section>

                <section class="kpis" aria-label="Indicadores financeiros">
                    <article class="card kpi">
                        <div class="kpi-top">
                            <div>
                                <div class="kpi-label">Orçamento aprovado</div>
                                <div class="kpi-value">R$ {{ number_format($kpis['orcamento_total'], 2, ',', '.') }}</div>
                                <div class="kpi-sub">Base de comparação do MVP</div>
                            </div>
                            <div class="mini-icon" aria-hidden="true">◎</div>
                        </div>
                    </article>

                    <article class="card kpi">
                        <div class="kpi-top">
                            <div>
                                <div class="kpi-label">Total gasto</div>
                                <div class="kpi-value">R$ {{ number_format($kpis['total_gasto'], 2, ',', '.') }}</div>
                                <div class="kpi-sub">{{ $kpis['percentual_executado'] }}% do orçamento aprovado</div>
                            </div>
                            <div class="mini-icon" aria-hidden="true">↗</div>
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
                                <div class="kpi-sub">{{ $kpis['is_estourado'] ? 'Orçamento excedido' : 'Disponível para novas despesas' }}</div>
                            </div>
                            <div class="mini-icon" aria-hidden="true">◒</div>
                        </div>
                    </article>

                    <article class="card kpi">
                        <div class="kpi-label">Pedidos que exigem atenção</div>
                        <div class="status-grid">
                            <div class="status-chip">Pendentes<strong>{{ $kpis['pedidos_pendentes'] }}</strong></div>
                            <div class="status-chip danger-status">Divergências<strong>{{ $kpis['com_divergencia'] }}</strong></div>
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
                                <div><span>Gasto</span><strong>R$ {{ number_format($kpis['total_gasto'], 2, ',', '.') }}</strong></div>
                                <div><span>Saldo</span><strong>R$ {{ number_format($kpis['saldo'], 2, ',', '.') }}</strong></div>
                            </div>
                        </div>
                    </article>

                    <article class="card section-card" id="abc">
                        <div class="section-head">
                            <div>
                                <h2>Curva ABC de custos</h2>
                                <p>Insumos ordenados pelo impacto financeiro acumulado.</p>
                            </div>
                            <span class="pill">A 80% · B 15% · C 5%</span>
                        </div>

                        <div class="abc-list">
                            @foreach ($insumos_abc as $item)
                            <div class="abc-item">
                                <div class="abc-main">
                                    <div class="abc-label">
                                        <strong>{{ $item['nome'] }}</strong>
                                        <span>R$ {{ number_format($item['valor'], 2, ',', '.') }}</span>
                                    </div>
                                    <div class="abc-bar">
                                        <i class="abc-fill class-{{ $item['classe'] }}" style="width: {{ $item['altura_pct'] }}%"></i>
                                    </div>
                                </div>

                                <div class="class-badge class-{{ strtoupper($item['classe']) }}">
                                    {{ strtoupper($item['classe']) }}
                                </div>
                            </div>
                            @endforeach
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
                            <h2>Pedidos recentes</h2>
                            <p>Pedidos mais recentes dentro do período selecionado.</p>
                        </div>
                        <span class="pill" id="visibleOrdersCount">{{ count($pedidos) }} pedidos</span>
                    </div>

                    <div class="table-scroll">
                        <table>
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Fornecedor</th>
                                    <th>Data</th>
                                    <th>Item</th>
                                    <th>Quantidade</th>
                                    <th>Valor total</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="ordersTable">
                                @foreach ($pedidos as $pedido)
                                <tr
                                    data-order-row
                                    data-search="{{ strtolower($pedido['codigo'].' '.$pedido['fornecedor'].' '.$pedido['item']) }}"
                                    data-date="{{ $pedido['data'] }}"
                                    data-work="{{ $pedido['obra'] ?? '' }}">
                                    <td><strong>{{ $pedido['codigo'] }}</strong></td>
                                    <td>{{ $pedido['fornecedor'] }}</td>
                                    <td>{{ $pedido['data'] }}</td>
                                    <td>{{ $pedido['item'] }}</td>
                                    <td>{{ $pedido['quantidade'] }}</td>
                                    <td><strong>R$ {{ number_format($pedido['valor'], 2, ',', '.') }}</strong></td>
                                    <td><span class="status {{ $pedido['status'] }}">{{ $pedido['status_label'] }}</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="order-mobile" id="ordersMobile">
                        @foreach ($pedidos as $pedido)
                        <article
                            class="order-card"
                            data-order-card
                            data-search="{{ strtolower($pedido['codigo'].' '.$pedido['fornecedor'].' '.$pedido['item']) }}"
                            data-date="{{ $pedido['data'] }}"
                            data-work="{{ $pedido['obra'] ?? '' }}">
                            <div class="order-top">
                                <strong>{{ $pedido['codigo'] }}</strong>
                                <span class="status {{ $pedido['status'] }}">{{ $pedido['status_label'] }}</span>
                            </div>
                            <div class="order-muted">{{ $pedido['fornecedor'] }} · {{ $pedido['item'] }}</div>
                            <div class="order-bottom">
                                <span>{{ $pedido['data'] }}</span>
                                <strong>R$ {{ number_format($pedido['valor'], 2, ',', '.') }}</strong>
                            </div>
                        </article>
                        @endforeach
                    </div>

                    <div class="empty-state" id="ordersEmpty" hidden>Nenhum pedido encontrado com os filtros atuais.</div>
                </section>

                <section class="card section-card occurrences-section" id="occurrences">
                    <div class="section-head">
                        <div>
                            <h2>Ocorrências e divergências</h2>
                            <p>Entregas parciais, avarias e inconsistências que exigem atenção.</p>
                        </div>
                        <span class="pill danger-pill">{{ count($ocorrencias) }} abertas</span>
                    </div>

                    <div class="occ-list">
                        @forelse ($ocorrencias as $occ)
                        <article class="occ">
                            <div class="occ-icon" aria-hidden="true">!</div>
                            <div>
                                <strong>{{ $occ['codigo'] }}</strong>
                                <p>{{ $occ['desc'] }}</p>
                                <span class="occ-tag">{{ $occ['tag'] }}</span>
                            </div>
                        </article>
                        @empty
                        <div class="empty-state">Nenhuma ocorrência pendente.</div>
                        @endforelse
                    </div>
                </section>
            </div>
        </main>

        <nav class="mobile-nav" aria-label="Navegação mobile">
            <a href="#overview" class="active"><span>⌂</span>Resumo</a>
            <a href="#abc"><span>▥</span>ABC</a>
            <a href="#orders"><span>≡</span>Pedidos</a>
            <a href="#occurrences"><span>!</span>Ocorrências</a>
        </nav>
    </div>

    <div class="modal" id="importModal" role="dialog" aria-modal="true" aria-labelledby="importTitle">
        <div class="modal-card">
            <div class="modal-head">
                <div>
                    <h2 id="importTitle">Importar planilha de custos</h2>
                    <p>O upload funciona apenas na implantação dinâmica do Laravel.</p>
                </div>
                <button class="btn icon ghost" type="button" data-action="close-import" aria-label="Fechar">×</button>
            </div>

            <form id="importForm" class="modal-body" action="/importar" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="dropzone">
                    <strong>Selecione a planilha legada</strong>
                    <p>Arquivos .xlsx ou .xls. O backend deve validar o cabeçalho antes de processar os dados.</p>
                    <input id="excelFile" name="arquivo" type="file" accept=".xlsx,.xls" required>
                </div>

                <div class="static-warning" id="staticWarning" hidden>
                    No GitHub Pages o sistema usa somente a massa de dados pré-carregada.
                    A importação real exige a versão Laravel publicada em servidor PHP.
                </div>

                <div class="modal-actions">
                    <button class="btn" type="button" data-action="close-import">Cancelar</button>
                    <button class="btn primary" id="submitImport" type="submit">Validar e processar</button>
                </div>
            </form>
        </div>
    </div>

    <div class="toast" id="toast" role="status" aria-live="polite"></div>
    <script src="./js/dashboard.js"></script>
</body>

</html>