document.addEventListener('DOMContentLoaded', function () {

    // ── CALENDARIO ──────────────────────────────────────────────
    const calendarEl = document.getElementById('calendar');
    if (calendarEl) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'es',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            buttonText: { today: 'Hoy' },
            events: disponibilidadUrl,
            eventColor: '#8B4513',
            dayCellClassNames: function (info) {
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                if (info.date < today) return ['fc-day-past-custom'];
                return [];
            },
            height: 'auto',
        });
        calendar.render();
    }

    // ── CÁLCULO DE PRECIO ────────────────────────────────────────
    const fechaEntrada = document.getElementById('fecha_entrada');
    const fechaSalida = document.getElementById('fecha_salida');
    const selectServicio = document.getElementById('id_servicio');
    const precioDisplay = document.getElementById('precioDisplay');
    const precioTotal = document.getElementById('precioTotal');
    const precioNoches = document.getElementById('precioNoches');

    function calcularPrecio() {
        if (!fechaEntrada || !fechaSalida || !selectServicio) return;

        const entrada = new Date(fechaEntrada.value);
        const salida = new Date(fechaSalida.value);
        const option = selectServicio.options[selectServicio.selectedIndex];
        const precio = parseFloat(option.dataset.precio);

        if (fechaEntrada.value && fechaSalida.value && selectServicio.value && salida > entrada) {
            const noches = Math.round((salida - entrada) / (1000 * 60 * 60 * 24));
            const total = noches * precio;
            precioTotal.textContent = total.toFixed(0) + '€';
            precioNoches.textContent = noches + ' noche' + (noches > 1 ? 's' : '') + ' × ' + precio.toFixed(0) + '€/día';
            precioDisplay.style.display = 'block';
        } else {
            precioDisplay.style.display = 'none';
        }
    }

    if (fechaEntrada) fechaEntrada.addEventListener('change', calcularPrecio);
    if (fechaSalida) fechaSalida.addEventListener('change', calcularPrecio);
    if (selectServicio) selectServicio.addEventListener('change', calcularPrecio);

    // ── FECHA MÍNIMA DE SALIDA ───────────────────────────────────
    if (fechaEntrada) {
        fechaEntrada.addEventListener('change', function () {
            if (fechaSalida) {
                const minSalida = new Date(this.value);
                minSalida.setDate(minSalida.getDate() + 1);
                fechaSalida.min = minSalida.toISOString().split('T')[0];
                if (fechaSalida.value && new Date(fechaSalida.value) <= new Date(this.value)) {
                    fechaSalida.value = minSalida.toISOString().split('T')[0];
                }
                calcularPrecio();
            }
        });
    }
});