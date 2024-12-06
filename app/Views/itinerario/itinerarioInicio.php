<?= $this->extend('template'); ?>
<?= $this->section('content'); ?>

<div class="container">
    <div class="row">
        <div class="col-3">
            <div id='external-events'>
                <div class="card">
                    <div class="card-header">
                        <h5>Ponencias por asignar</h5>
                    </div>
                    <div class="card-body">
                        <div id='lista-ponencias'></div>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-9">
            <div class="card">
                <div class="card-header">
                    <h5>Calendario</h5>
                </div>
                <div class="card-body">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

<script>

    let ponencias = <?= json_encode($ponencias) ?>;
    let congreso = <?= json_encode($congreso) ?>;

    let eventos = ponencias.filter(ponencia => ponencia.po_hora_inicio != null).map(ponencia => ({
        title: ponencia.po_titulo,
        start: ponencia.po_hora_inicio,
        end: ponencia.po_hora_fin,
        overlap: false,
        id: ponencia.po_id_ponencia
    }));

    let listaPonencias = document.getElementById('lista-ponencias');

    ponencias.filter(ponencia => ponencia.po_hora_inicio == null).forEach(ponencia => {
        let tematica = ponencia.tematica;

        let ponenciaDiv = document.createElement('div');
        let titleDiv = document.createElement('div');
        titleDiv.className = 'fc-event-main';
        titleDiv.innerHTML = ponencia.po_titulo;
        ponenciaDiv.appendChild(titleDiv);
        ponenciaDiv.className = 'fc-event';
        ponenciaDiv.dataset.id = ponencia.po_id_ponencia;
        listaPonencias.appendChild(ponenciaDiv);

        let tematicaDiv = listaPonencias.querySelector(`[data-tematica="${tematica}"]`);
        if (!tematicaDiv) {
            tematicaDiv = document.createElement('div');
            tematicaDiv.className = 'tematica';
            tematicaDiv.dataset.tematica = tematica;
            tematicaDiv.innerHTML = `<strong>${tematica}</strong>`;
            listaPonencias.appendChild(tematicaDiv);
        }
        tematicaDiv.appendChild(ponenciaDiv);
    });

    if (listaPonencias.childElementCount == 0) {
        let ponenciaDiv = document.createElement('div');
        ponenciaDiv.innerHTML = 'No hay ponencias por asignar';
        listaPonencias.appendChild(ponenciaDiv);
    } else {
        new FullCalendar.Draggable(listaPonencias, {
            itemSelector: '.fc-event',
            eventData: eventEl => ({
                title: eventEl.innerText,
                id: eventEl.dataset.id,
                duration: '01:00:00'
            })
        });
    }

    FullCalendar.globalLocales.push({
        code: "es",
        week: {
            dow: 1,
            doy: 4
        },
        buttonText: {
            prev: "Ant",
            next: "Sig",
            today: "Hoy",
            month: "Mes",
            week: "Semana",
            day: "Día",
            list: "Agenda"
        },
        weekText: "Sm",
        allDayText: "Todo el día",
        moreLinkText: "más",
        noEventsText: "No hay eventos para mostrar"
    });

    let formatDate = date => {
        let year = date.getFullYear();
        let month = ('0' + (date.getMonth() + 1)).slice(-2);
        let day = ('0' + date.getDate()).slice(-2);
        let hours = ('0' + date.getHours()).slice(-2);
        let minutes = ('0' + date.getMinutes()).slice(-2);
        let seconds = ('0' + date.getSeconds()).slice(-2);
        return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
    };

    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                center: 'title',
                left: 'prev,next',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth',
            },
            initialView: 'dayGridMonth',
            locale: 'es',
            navLinks: true,
            dayMaxEvents: true,
            editable: true,
            eventDurationEditable: true,
            droppable: true,
            events: eventos,
            visibleRange: {
                start: congreso.fecha_inicio,
                end: new Date(new Date(congreso.fecha_fin).setDate(new Date(congreso.fecha_fin).getDate() + 1)).toISOString().split('T')[0]
            },
            validRange: {
                start: congreso.fecha_inicio,
                end: new Date(new Date(congreso.fecha_fin).setDate(new Date(congreso.fecha_fin).getDate() + 1)).toISOString().split('T')[0]
            },
            drop: function (arg) {
                if (['timeGridDay', 'timeGridWeek'].includes(calendar.view.type)) {
                    let fechaInicio = arg.date;
                    let fechaFin = new Date(fechaInicio.getTime() + 60 * 60 * 1000); //1 hora después
                    let ponenciaId = arg.draggedEl.dataset.id;
                    let fechaInicioMysql = formatDate(fechaInicio);
                    let fechaFinMysql = formatDate(fechaFin);

                    getObject('<?= base_url('itinerario/actualizarHora') ?>', {
                        ponenciaId,
                        fechaInicioMysql,
                        fechaFinMysql
                    }, function (response) {

                        if (response.status == 'ok') {

                            //removemos el elemento de la lista de ponencias
                            arg.draggedEl.parentNode.removeChild(arg.draggedEl);

                            //si un div de tematica se queda vacío, lo eliminamos
                            let tematicas = listaPonencias.querySelectorAll('.tematica');
                            tematicas.forEach(tematica => {
                                if (tematica.querySelectorAll('.fc-event').length == 0) {
                                    tematica.parentNode.removeChild(tematica);
                                }
                            });

                            //si ya no hay ponencias por asignar, mostramos un mensaje
                            if (listaPonencias.childElementCount == 0) {
                                let ponenciaDiv = document.createElement('div');
                                ponenciaDiv.innerHTML = 'No hay ponencias por asignar';
                                listaPonencias.appendChild(ponenciaDiv);

                            }
                        } else {
                            //cancelamos el drop
                            arg.revert();
                        }
                    });

                }
            },
            eventReceive: function (info) {
                info.event.overlap = false;
            },
            eventDrop: function (info) {

                let fechaInicioMysql = formatDate(info.event.start);
                let fechaFinMysql = formatDate(info.event.end);
                let ponenciaId = info.event.id;

                getObject('<?= base_url('itinerario/actualizarHora') ?>', {
                    ponenciaId,
                    fechaInicioMysql,
                    fechaFinMysql
                }, function (response) {

                });
            }
        });
        calendar.render();
    });


    $(function () {
        $('.fc-event-main').addClass('text-bg-warning');
    });

</script>

<style>
    #calendar {
        margin: 0 auto;
    }

    #lista-ponencias .fc-event {
        margin: 3px 0;
        cursor: move;
        margin-left: 25px;
    }

    #lista-ponencias .fc-event-main {
        padding: 5px;
        border: 2px solid #EAEAEA;
        border-radius: 10px;
    }

    .tematica {
        margin: 10px 0;
        border: 1px solid #ccc;
        padding: 5px;
        border-radius: 5px;
    }
</style>

<?= $this->endSection(); ?>