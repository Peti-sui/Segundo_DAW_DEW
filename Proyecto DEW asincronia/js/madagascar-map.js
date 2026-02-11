/* Archivo js madagascar-map js encargado de inicializar y configurar un mapa interactivo usando Leaflet mostrando una ubicacion especifica y gestionando su comportamiento dinamico */

document.addEventListener('DOMContentLoaded', function() {
    /* Evento que espera a que el documento este completamente cargado antes de ejecutar la logica principal */
    console.log('𝜗𐙚 Inicializando mapa de Madagascar 𝜗࣪˖');

    /* Definicion de las coordenadas centrales del mapa correspondientes a Antananarivo Madagascar */
    const madagascarCoords = [-18.8792, 47.5079];

    /* Obtencion del contenedor donde se renderizara el mapa */
    const mapContainer = document.getElementById('madagascar-map');

    /* Validacion de la existencia del contenedor para evitar errores de ejecucion */
    if (!mapContainer) {
        console.warn('࣪˖𐙚 Contenedor del mapa no encontrado 𝜗');
        return;
    }

    /* Inicializacion del mapa con la libreria Leaflet estableciendo centro y nivel de zoom */
    const map = L.map('madagascar-map').setView(madagascarCoords, 13);

    /* Deteccion del tema visual actual para seleccionar el estilo del mapa */
    const tema = document.body.classList.contains('tema-oscuro') ? 'dark' : 'light';

    /* Seleccion dinamica de la capa base del mapa segun el tema activo */
    if (tema === 'dark') {
        /* Carga de la capa de mapa en modo oscuro */
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>, &copy; CartoDB',
            subdomains: 'abcd',
            maxZoom: 19
        }).addTo(map);
    } else {
        /* Carga de la capa de mapa en modo claro */
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>, &copy; CartoDB',
            subdomains: 'abcd',
            maxZoom: 19
        }).addTo(map);
    }

    /* Creacion de un icono personalizado para representar la ubicacion principal */
    const madagascarIcon = L.divIcon({
        className: 'madagascar-marker',
        html: `<div style="
            background: linear-gradient(135deg, #00b894 0%, #00cec9 100%);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 3px solid white;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
            color: white;
            font-size: 20px;
        ">𝜗𐙚</div>`,
        iconSize: [40, 40],
        iconAnchor: [20, 40],
        popupAnchor: [0, -40]
    });

    /* Insercion del marcador en el mapa usando el icono personalizado */
    const marker = L.marker(madagascarCoords, { icon: madagascarIcon }).addTo(map);

    /* Definicion del contenido HTML del popup asociado al marcador */
    const popupContent = `
        <div style="text-align: center; padding: 5px;">
            <div style="font-size: 24px; margin-bottom: 5px;">𝜗࣪˖</div>
            <h3 style="margin: 5px 0; color: #00b894;">Tienda Llaveros</h3>
            <p style="margin: 5px 0; font-weight: bold;">Antananarivo, Madagascar</p>
            <p style="margin: 5px 0;">Av. de la Independencia, 45</p>
            <p style="margin: 5px 0; color: #0984e3;">࣪˖𐙚 +34 647 382 917</p>
            <hr style="margin: 10px 0;">
            <p style="margin: 5px 0; font-style: italic;">${tema === 'dark' ? '𝜗𐙚 Distribuidora oficial' : '𝜗𐙚 Distribuidora oficial'}</p>
        </div>
    `;

    /* Asociacion del popup al marcador con configuracion de estilo y tamano */
    marker.bindPopup(popupContent, {
        maxWidth: 300,
        className: 'madagascar-popup'
    });

    /* Apertura automatica del popup tras un pequeno retraso para mejorar la experiencia visual */
    setTimeout(() => {
        marker.openPopup();
    }, 1000);

    /* Ajuste automatico del tamano del mapa cuando la ventana cambia de dimensiones */
    window.addEventListener('resize', function() {
        setTimeout(() => {
            map.invalidateSize();
        }, 100);
    });

    /* Mensaje final indicando la correcta inicializacion del mapa */
    console.log('𐙚࣪˖ Mapa de Madagascar inicializado correctamente 𝜗');
});