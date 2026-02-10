// js/carousel.js
// Carrusel de productos para la tienda de llaveros - Corregido

document.addEventListener('DOMContentLoaded', function() {
    const carousel = {
        track: document.querySelector('.carousel-track'), /* Contenedor de los slides del carrusel 𝜗𝜚 */
        slides: document.querySelectorAll('.carousel-slide'), /* Todos los slides individuales ࣪˖ */
        prevBtn: document.querySelector('.carousel-btn.prev'), /* Boton para ir al grupo anterior ִ𐙚 */
        nextBtn: document.querySelector('.carousel-btn.next'), /* Boton para ir al grupo siguiente 𝜗𝜚 */
        indicatorsContainer: document.querySelector('.carousel-indicators'), /* Contenedor de indicadores del carrusel ࣪˖ */
        currentSlide: 0, /* Indice del slide actual ִ𐙚 */
        slidesPerView: 2, /* Cantidad de slides visibles por vista 𝜗𝜚 */
        autoPlayInterval: null, /* Intervalo para autoplay ࣪˖ */
        autoPlayDelay: 5000, /* Tiempo entre autoplay en ms ִ𐙚 */
        isTransitioning: false, /* Flag para saber si hay transicion activa 𝜗𝜚 */
        totalSlides: 0, /* Total de slides disponibles ࣪˖ */
        
        init: function() {
            /* Verificar que el carrusel existe y tiene slides ִ𐙚 */
            if (!this.track || this.slides.length === 0) return;
            
            this.totalSlides = this.slides.length; /* Guardar total de slides 𝜗𝜚 */
            this.createIndicators(); /* Crear indicadores iniciales ࣪˖ */
            this.setupEventListeners(); /* Configurar eventos de botones y touch ִ𐙚 */
            this.updateCarousel(); /* Renderizar carrusel por primera vez 𝜗𝜚 */
            this.startAutoPlay(); /* Iniciar autoplay ࣪˖ */
            
            /* Configurar ancho y estilo de los slides */
            this.setupSlides();
            
            /* Event listener para ajustar responsive al redimensionar */
            window.addEventListener('resize', () => {
                this.setupSlides(); /* Ajustar ancho de slides ִ𐙚 */
                this.updateCarousel(); /* Recalcular posicion 𝜗𝜚 */
                this.updateIndicators(); /* Actualizar indicadores ࣪˖ */
            });
        },
        
        setupSlides: function() {
            /* Calcular ancho de cada slide basado en el contenedor y slidesPerView ִ𐙚 */
            const containerWidth = this.track.parentElement.clientWidth;
            const slideWidth = (containerWidth / this.slidesPerView) - (25 * (this.slidesPerView - 1)) / this.slidesPerView;
            
            /* Aplicar estilos de ancho y flex a cada slide 𝜗𝜚 */
            this.slides.forEach(slide => {
                slide.style.width = `${slideWidth}px`;
                slide.style.flex = `0 0 ${slideWidth}px`;
            });
        },
        
        createIndicators: function() {
            /* Crear indicadores solo si existe el contenedor ࣪˖ */
            if (!this.indicatorsContainer) return;
            
            this.indicatorsContainer.innerHTML = ''; /* Limpiar indicadores previos ִ𐙚 */
            const totalGroups = Math.ceil(this.totalSlides / this.slidesPerView); /* Calcular cantidad de grupos 𝜗𝜚 */
            
            /* Crear cada indicador y asignar eventos de click ࣪˖ */
            for (let i = 0; i < totalGroups; i++) {
                const indicator = document.createElement('div');
                indicator.className = 'carousel-indicator';
                if (i === 0) indicator.classList.add('active'); /* Marcar primer indicador activo ִ𐙚 */
                indicator.setAttribute('data-index', i);
                indicator.addEventListener('click', () => this.goToGroup(i)); /* Ir al grupo seleccionado 𝜗𝜚 */
                this.indicatorsContainer.appendChild(indicator);
            }
        },
        
        setupEventListeners: function() {
            /* Eventos para botones prev y next ࣪˖ */
            if (this.prevBtn) {
                this.prevBtn.addEventListener('click', () => this.prevGroup());
            }
            
            if (this.nextBtn) {
                this.nextBtn.addEventListener('click', () => this.nextGroup());
            }
            
            /* Configuracion de eventos touch para dispositivos moviles ִ𐙚 */
            let startX = 0;
            let endX = 0;
            let isSwiping = false;
            
            if (this.track) {
                this.track.addEventListener('touchstart', (e) => {
                    startX = e.touches[0].clientX;
                    isSwiping = true;
                    this.stopAutoPlay(); /* Pausar autoplay mientras se desliza 𝜗𝜚 */
                }, { passive: true });
                
                this.track.addEventListener('touchmove', (e) => {
                    if (!isSwiping) return;
                    endX = e.touches[0].clientX;
                }, { passive: true });
                
                this.track.addEventListener('touchend', () => {
                    if (!isSwiping) return;
                    
                    const threshold = 50; /* Distancia minima para considerar swipe ࣪˖ */
                    const diff = startX - endX;
                    
                    /* Determinar direccion del swipe ִ𐙚 */
                    if (Math.abs(diff) > threshold) {
                        if (diff > 0) {
                            this.nextGroup(); /* Swipe hacia la izquierda 𝜗𝜚 */
                        } else {
                            this.prevGroup(); /* Swipe hacia la derecha ࣪˖ */
                        }
                    }
                    
                    isSwiping = false;
                    this.startAutoPlay(); /* Reanudar autoplay ִ𐙚 */
                });
            }
            
            /* Pausar autoplay al interactuar con el carrusel 𝜗𝜚 */
            const carouselContainer = document.querySelector('.carousel-container');
            if (carouselContainer) {
                carouselContainer.addEventListener('mouseenter', () => this.stopAutoPlay());
                carouselContainer.addEventListener('mouseleave', () => this.startAutoPlay());
            }
        },
        
        updateCarousel: function() {
            /* Evitar actualizar si hay transicion activa o no existe track ࣪˖ */
            if (this.isTransitioning || !this.track) return;
            
            this.isTransitioning = true; /* Marcar inicio de transicion ִ𐙚 */
            
            /* Calcular desplazamiento horizontal basado en slideWidth y gap 𝜗𝜚 */
            const slideWidth = this.slides[0]?.offsetWidth || 300;
            const gap = 25;
            const translateX = -(this.currentSlide * (slideWidth + gap));
            
            this.track.style.transform = `translateX(${translateX}px)`; /* Aplicar transform al contenedor ࣪˖ */
            this.track.style.transition = 'transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94)'; /* Animacion de transicion ִ𐙚 */
            
            /* Actualizar estado de botones y indicadores 𝜗𝜚 */
            this.updateButtons();
            this.updateIndicators();
            
            /* Resetear flag de transicion despues del tiempo 𝜗𝜚 */
            setTimeout(() => {
                this.isTransitioning = false;
            }, 500);
        },
        
        updateButtons: function() {
            /* Habilitar o deshabilitar boton prev ִ𐙚 */
            if (this.prevBtn) {
                this.prevBtn.disabled = this.currentSlide === 0;
            }
            
            /* Habilitar o deshabilitar boton next 𝜗𝜚 */
            if (this.nextBtn) {
                const maxSlide = Math.max(0, this.totalSlides - this.slidesPerView);
                this.nextBtn.disabled = this.currentSlide >= maxSlide;
            }
        },
        
        updateIndicators: function() {
            /* Actualizar indicadores solo si existe contenedor ࣪˖ */
            if (!this.indicatorsContainer) return;
            
            const indicators = this.indicatorsContainer.querySelectorAll('.carousel-indicator');
            const currentGroup = Math.floor(this.currentSlide / this.slidesPerView); /* Grupo activo ִ𐙚 */
            
            /* Activar indicador correspondiente y desactivar los demas 𝜗𝜚 */
            indicators.forEach((indicator, index) => {
                indicator.classList.toggle('active', index === currentGroup);
            });
        },
        
        prevGroup: function() {
            /* Ir al grupo anterior si no es el primero y no hay transicion activa ࣪˖ */
            if (this.currentSlide > 0 && !this.isTransitioning) {
                this.currentSlide = Math.max(0, this.currentSlide - this.slidesPerView);
                this.updateCarousel();
            }
        },
        
        nextGroup: function() {
            /* Ir al siguiente grupo si no es el ultimo y no hay transicion activa ִ𐙚 */
            const maxSlide = Math.max(0, this.totalSlides - this.slidesPerView);
            if (this.currentSlide < maxSlide && !this.isTransitioning) {
                this.currentSlide = Math.min(maxSlide, this.currentSlide + this.slidesPerView);
                this.updateCarousel();
            }
        },
        
        goToGroup: function(groupIndex) {
            /* Ir a un grupo especifico si no hay transicion activa 𝜗𝜚 */
            if (this.isTransitioning) return;
            
            this.currentSlide = groupIndex * this.slidesPerView;
            this.updateCarousel();
        },
        
        startAutoPlay: function() {
            /* Iniciar autoplay y limpiar intervalos previos ࣪˖ */
            this.stopAutoPlay();
            
            this.autoPlayInterval = setInterval(() => {
                const maxSlide = Math.max(0, this.totalSlides - this.slidesPerView);
                
                /* Reiniciar al inicio si llega al final ִ𐙚 */
                if (this.currentSlide >= maxSlide) {
                    this.currentSlide = 0;
                } else {
                    this.currentSlide += this.slidesPerView;
                }
                
                this.updateCarousel(); /* Actualizar carrusel 𝜗𝜚 */
            }, this.autoPlayDelay);
        },
        
        stopAutoPlay: function() {
            /* Detener autoplay si existe intervalo activo ࣪˖ */
            if (this.autoPlayInterval) {
                clearInterval(this.autoPlayInterval);
                this.autoPlayInterval = null;
            }
        }
    };
    
    /* Inicializar carrusel despues de un breve retardo ִ𐙚 */
    setTimeout(() => {
        carousel.init();
    }, 100);
});
