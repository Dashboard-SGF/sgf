<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGF — Suprimentos & Gestão Financeira</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>

<body>
    <div class="app">
        <aside class="sidebar">
            <div class="logo">SGF <span>01</span></div>
            <nav class="nav">
                <a class="active" href="#">▦ Dashboard</a>
                <a href="#">▤ Pedidos</a>
                <a href="#">◈ Materiais</a>
                <a href="#">⚠ Ocorrências</a>
                <small>Gestão</small>
                <a href="#">◉ Obras</a>
                <a href="#">⇩ Importações</a>
                <a href="#">⚙ Configurações</a>
            </nav>
        </aside>

        <main class="main">
            <header class="top">
                <div class="title">
                    <h1>Suprimentos & Gestão Financeira</h1>
                    <p>Visão consolidada • Atualizado dinamicamente via Service</p>
                </div>
                <div class="actions">
                    <button class="btn" onclick="window.print()">🖨 Imprimir</button>
                    <button class="btn primary" onclick="openModal()">⇧ Importar Excel</button>
                </div>
            </header>

            <section class="filters">
                <div class="field">
                    <label>OBRA</label>
                    <select>
                        <option>Visão consolidada</option>
                        <option>Obra Residencial Aurora</option>
                        <option>Complexo Administrativo</option>
                    </select>
                </div>
                <div class="field"><label>PERÍODO INICIAL</label><input type="date" value="2026-08-01"></div>
                <div class="field"><label>PERÍODO FINAL</label><input type="date" value="2026-09-02"></div>
                <div class="field"><label>BUSCAR PEDIDO OU INSUMO</label><input
                        placeholder="Código, fornecedor, item..."></div>
                <button class="btn primary">Filtrar</button>
            </section>

            <section class="kpis">
                <div class="card kpi">
                    <div class="label">TOTAL GASTO</div>
                    <div class="value">R$ {{ number_format($kpis['total_gasto'], 2, ',', '.') }}</div>
                    <div class="sub">{{ $kpis['percentual_executado'] }}% do orçamento aprovado</div>
                </div>
                <div class="card kpi {{ $kpis['is_estourado'] ? 'danger' : '' }}">
                    <div class="label">SALDO REMANESCENTE</div>
                    <div class="value">R$ {{ number_format($kpis['saldo'], 2, ',', '.') }}</div>
                    <div class="sub">Orçamento: R$ {{ number_format($kpis['orcamento_total'], 2, ',', '.') }}</div>
                </div>
                <div class="card kpi">
                    <div class="label">PEDIDOS PENDENTES</div>
                    <div class="value">{{ $kpis['pedidos_pendentes'] }}</div>
                    <div class="sub">Aguardando aprovação</div>
                </div>
                <div class="card kpi danger">
                    <div class="label">COM DIVERGÊNCIA</div>
                    <div class="value">{{ $kpis['com_divergencia'] }}</div>
                    <div class="sub">Necessitam de atenção</div>
                </div>
            </section>

            <section class="grid">
                <div class="card">
                    <div class="card-head">
                        <div>
                            <h2>Curva ABC — Impacto no orçamento</h2>
                            <span class="muted">Materiais ordenados por custo acumulado</span>
                        </div>
                        <span class="badge approved">A: 80% • B: 15% • C: 5%</span>
                    </div>
                    <div class="chart">
                        @foreach ($insumos_abc as $item)
                            <div class="bar {{ $item['classe'] }}" style="height:{{ $item['altura_pct'] }}%">
                                <i>{{ $item['nome'] }}</i>
                            </div>
                        @endforeach
                    </div>
                    <div class="abc-legend">
                        <span><i class="dot blue"></i>Classe A — alto impacto</span>
                        <span><i class="dot light"></i>Classe B — médio</span>
                        <span><i class="dot pale"></i>Classe C — baixo</span>
                    </div>
                </div>

                <div class="card">
                    <div class="card-head">
                        <div>
                            <h2>Orçamento global</h2>
                            <span class="muted">Executado x disponível</span>
                        </div>
                    </div>
                    <div class="budget">
                        <div class="donut"
                            style="background: conic-gradient(#2563eb 0 {{ $kpis['percentual_executado'] }}%, #e5e7eb {{ $kpis['percentual_executado'] }}% 100%);">
                        </div>
                        <div class="donut-label">
                            <strong>{{ $kpis['percentual_executado'] }}%</strong>
                            <span>executado</span>
                        </div>
                    </div>
                    <div style="display:flex;justify-content:space-between;font-size:12px">
                        <span>Gasto <strong>R$ {{ number_format($kpis['total_gasto'], 0, ',', '.') }}</strong></span>
                        <span>Saldo <strong>R$ {{ number_format($kpis['saldo'], 0, ',', '.') }}</strong></span>
                    </div>
                </div>
            </section>

            <section class="card table-card">
                <div class="table-head">
                    <div>
                        <h2>Últimos pedidos de insumos</h2>
                        <span class="muted">Pedidos mais recentes do período</span>
                    </div>
                    <button class="btn">Exportar PDF</button>
                </div>
                <div class="table-wrap">
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
                        <tbody>
                            @foreach ($pedidos as $pedido)
                                <tr>
                                    <td>{{ $pedido['codigo'] }}</td>
                                    <td>{{ $pedido['fornecedor'] }}</td>
                                    <td>{{ $pedido['data'] }}</td>
                                    <td>{{ $pedido['item'] }}</td>
                                    <td>{{ $pedido['quantidade'] }}</td>
                                    <td>R$ {{ number_format($pedido['valor'], 2, ',', '.') }}</td>
                                    <td><span
                                            class="badge {{ $pedido['status'] }}">{{ $pedido['status_label'] }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="card" style="margin-top:18px">
                <div class="card-head">
                    <div>
                        <h2>Central de ocorrências</h2>
                        <span class="muted">Divergências pendentes</span>
                    </div>
                    <span class="badge divergence">{{ count($ocorrencias) }} pendências</span>
                </div>
                <div class="occurrences">
                    @foreach ($ocorrencias as $occ)
                        <div class="occ">
                            <strong>{{ $occ['codigo'] }}</strong>
                            <p>{{ $occ['desc'] }}</p>
                            <span class="tag">{{ $occ['tag'] }}</span>
                        </div>
                    @endforeach
                </div>
            </section>
        </main>
    </div>

    <div class="modal" id="modal">
        <div class="modal-box">
            <h2>Importar planilha</h2>
            <p class="muted">Envie uma planilha .xlsx ou .xls para atualizar os indicadores.</p>
            <div class="drop">
                <strong>Arraste o arquivo aqui</strong>ou clique para selecionar<br>
                <small>Máximo recomendado: 5.000 linhas</small>
                <input id="file" type="file" accept=".xlsx,.xls" style="margin-top:15px">
            </div>
            <div class="modal-actions">
                <button class="btn" onclick="closeModal()">Cancelar</button>
                <button class="btn primary" onclick="simulate()">Processar planilha</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/dashboard.js') }}"></script>
</body>

</html>
