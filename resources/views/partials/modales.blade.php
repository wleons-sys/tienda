<!-- ══════════ MODAL ══════════ -->
<div id="modal" class="modal" onclick="handleBG(event)">
    <div class="modal-box">
        <div class="modal-hd">
            <div class="modal-hd-top">
                <span id="m-badge" class="modal-top-badge"></span>
                <button class="close-btn" onclick="closeModal()">✕</button>
            </div>
            <h3 id="m-title"></h3>
        </div>
        <div class="modal-body" id="m-body"></div>
    </div>
</div>

<style>
/* ─── MODAL ─── */
.modal{
    display:none;
    position:fixed;
    inset:0;
    z-index:999;
    background:rgba(15,23,42,0.75);
    backdrop-filter:blur(8px);
    align-items:center;
    justify-content:center;
    padding:1rem;
}
.modal.open{
    display:flex;
    animation:mfade 0.2s ease;
}
@keyframes mfade{
    from{opacity:0}
    to{opacity:1}
}
.modal-box{
    background:#fff;
    border-radius:24px;
    max-width:540px;
    width:100%;
    max-height:90vh;
    overflow-y:auto;
    animation:mup 0.3s ease;
}
@keyframes mup{
    from{transform:translateY(24px);opacity:0}
    to{transform:translateY(0);opacity:1}
}
.modal-hd{
    padding:1.8rem 2rem 1.4rem;
    border-bottom:2px solid var(--border);
    position:sticky;
    top:0;
    background:#fff;
    z-index:1;
}
.modal-hd-top{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
}
.modal-hd h3{
    font-size:1.4rem;
    font-weight:800;
    margin-top:0.6rem;
}
.close-btn{
    background:var(--bg);
    border:none;
    width:36px;
    height:36px;
    border-radius:50%;
    cursor:pointer;
    font-size:1.2rem;
    display:flex;
    align-items:center;
    justify-content:center;
    color:var(--text2);
    transition:background 0.2s;
    flex-shrink:0;
}
.close-btn:hover{background:var(--border);}
.modal-body{
    padding:1.6rem 2rem;
}
.mrow{
    display:flex;
    gap:1rem;
    padding:0.75rem 0;
    border-bottom:1px solid var(--border);
    font-size:0.87rem;
}
.mrow:last-child{border-bottom:none;}
.mkey{
    min-width:100px;
    flex-shrink:0;
    font-size:0.68rem;
    font-weight:800;
    letter-spacing:1.5px;
    text-transform:uppercase;
    padding-top:2px;
}
.mval{
    color:var(--text2);
    line-height:1.6;
}
.mnote{
    margin-top:1.2rem;
    padding:1rem 1.1rem;
    border-radius:12px;
    font-size:0.82rem;
    line-height:1.6;
    font-weight:600;
}
.agua-note{background:var(--agua-pale);color:var(--agua-dark);}
.caminos-note{background:var(--caminos-pale);color:var(--caminos-dark);}
.elec-note{background:var(--elec-pale);color:var(--elec-dark);}
.mkey.agua-k{color:var(--agua);}
.mkey.caminos-k{color:var(--caminos-dark);}
.mkey.elec-k{color:var(--elec-dark);}
.modal-top-badge{
    font-size:0.65rem;
    font-weight:800;
    letter-spacing:2px;
    text-transform:uppercase;
    padding:0.2rem 0.7rem;
    border-radius:40px;
    display:inline-block;
}

/* Responsive Modal */
@media(max-width:600px){
    .modal-box{max-width:100%;margin:0.5rem;border-radius:16px;}
    .modal-hd{padding:1.2rem 1.2rem 1rem;}
    .modal-body{padding:1rem 1.2rem;}
    .modal-hd h3{font-size:1.2rem;}
    .mrow{flex-direction:column;gap:0.3rem;padding:0.6rem 0;}
    .mkey{min-width:auto;font-size:0.6rem;}
}
</style>

<script>
// Datos de los servicios para los modales
var DATA = {
    agua: {
        badge: '💧 Agua Potable', badgeCls: 'agua-note', keyCls: 'agua-k',
        title: 'Comité de Agua — Detalles del Servicio',
        rows: [
            ['Misión', 'Garantizar agua limpia y continua para todos los hogares del cantón con conexión activa.'],
            ['Participantes','Únicamente usuarios con chorro de agua. Los jóvenes NO participan en faenas de agua.'],
            ['Cuotas', 'Solo en efectivo. Monto definido en asamblea. El sistema impide cobros duplicados o mayores al saldo pendiente.'],
            ['Faenas', 'Limpieza de tanques, reparación de tuberías, mantenimiento de fuentes y pozos.'],
            ['Sanciones', 'Falta a faena = sanción «doble» (2 días de trabajo adicionales). Multa Q25.00 por inasistencia a asamblea.'],
            ['Comité', '8 integrantes divididos en 3 mesas (sectores) para recaudación y control territorial.'],
            ['En el sistema','El operador de cada mesa solo puede ver y modificar los usuarios de su propia mesa.']
        ],
        note: '💡 El sistema guarda automáticamente la posición en la lista rotativa tras cada convocatoria, sin pérdida de información.'
    },
    caminos: {
        badge: '🛣️ Caminos Rurales', badgeCls: 'caminos-note', keyCls: 'caminos-k',
        title: 'Comité Promejoramiento — Caminos Rurales',
        rows: [
            ['Misión', 'Mantener accesos, cunetas, baches y puentes para conectar el cantón.'],
            ['Participantes','Usuarios Y jóvenes. Distribución equitativa (ej: 5 usuarios + 5 jóvenes = 10 convocados).'],
            ['Cuotas', 'Aportación mensual en efectivo. Definida por el Comité de Promejoramiento.'],
            ['Faenas', 'Limpieza de cunetas, reparación de baches, roza de orillas, mantenimiento de puentes.'],
            ['Sanciones', 'Falta = doble (2 jornadas futuras distintas, no en un mismo día). El sistema lo programa automáticamente.'],
            ['Comité', '8 integrantes, 3 mesas para organización eficiente del cobro y las faenas.'],
            ['Lista rotativa','El sistema recuerda la última posición y genera la siguiente lista desde ahí sin errores ni olvidos.']
        ],
        note: '💡 Cuando alguien falta, el sistema identifica automáticamente en cuáles 2 faenas futuras debe cumplir el doble con reemplazo equitativo.'
    },
    electricidad: {
        badge: '⚡ Electricidad', badgeCls: 'elec-note', keyCls: 'elec-k',
        title: 'Comité Promejoramiento — Electricidad',
        rows: [
            ['Misión', 'Asegurar alumbrado público y gestionar mejoras eléctricas ante la distribuidora.'],
            ['Participantes','Usuarios y jóvenes por igual, según orden de lista rotativa compartida con caminos.'],
            ['Cuotas', 'Aportación para mantenimiento del alumbrado público y gestiones administrativas.'],
            ['Faenas', 'Poda de árboles, limpieza de postes, reporte de fallas a la empresa distribuidora.'],
            ['Sanciones', 'Control automático del doble. Registro digital de multas por inasistencia a asambleas.'],
            ['Asambleas', 'Primer lunes del mes. El sistema genera tickets numerados para control de asistencia en papel.'],
            ['Transparencia','Cualquier poblador puede solicitar que le muestren su estado de cuenta en el sistema.']
        ],
        note: '💡 El sistema integra faenas, multas de asamblea y cuotas en un solo reporte completo de morosidad.'
    }
};

function openModal(key) {
    var d = DATA[key];
    if (!d) return;
    document.getElementById('m-badge').textContent = d.badge;
    document.getElementById('m-badge').className = 'modal-top-badge ' + d.badgeCls;
    document.getElementById('m-title').textContent = d.title;
    var html = d.rows.map(function(r) {
        return '<div class="mrow"><span class="mkey ' + d.keyCls + '">' + r[0] + '</span><span class="mval">' + r[1] + '</span></div>';
    }).join('');
    html += '<div class="mnote ' + d.badgeCls + '">' + d.note + '</div>';
    document.getElementById('m-body').innerHTML = html;
    document.getElementById('modal').classList.add('open');
}

function closeModal() {
    document.getElementById('modal').classList.remove('open');
}
</script>