// js/ratings-all.js
// Sistema de valoracion que funciona en TODOS los productos

document.addEventListener('DOMContentLoaded', function() {
    /* Inicializando sistema de valoraciones */
    console.log('Inicializando sistema de valoraciones...');
    
    /* Objeto principal del sistema de valoraciones */
    const ratingSystem = {
        /* Almacena todas las valoraciones de productos */
        ratings: new Map(),
        
        /* Metodo para inicializar el sistema */
        init: function() {
            console.log('Buscando productos para valorar...');
            
            /* Cargar valoraciones previamente guardadas */
            this.loadSavedRatings();
            
            /* Configurar eventos para todos los productos */
            this.setupAllEvents();
            
            /* Actualizar todas las visualizaciones de valoraciones */
            this.updateAllDisplays();
            
            /* Generar datos iniciales de prueba si no existen */
            this.generateInitialData();
            
            console.log('Sistema listo Productos encontrados:', this.countProducts());
        },
        
        /* Contar productos con valoracion */
        countProducts: function() {
            return document.querySelectorAll('.rating-simple').length;
        },
        
        /* Cargar valoraciones guardadas en localStorage */
        loadSavedRatings: function() {
            try {
                const saved = localStorage.getItem('tienda_product_ratings');
                if (saved) {
                    const data = JSON.parse(saved);
                    for (const [productId, ratingData] of Object.entries(data)) {
                        this.ratings.set(productId, ratingData);
                    }
                    console.log('Valoraciones cargadas:', this.ratings.size);
                }
            } catch (error) {
                console.warn('No se pudieron cargar las valoraciones');
            }
        },
        
        /* Guardar valoraciones en localStorage */
        saveRatings: function() {
            try {
                const data = Object.fromEntries(this.ratings);
                localStorage.setItem('tienda_product_ratings', JSON.stringify(data));
            } catch (error) {
                console.warn('No se pudieron guardar las valoraciones');
            }
        },
        
        /* Configurar eventos para todos los productos */
        setupAllEvents: function() {
            /* Seleccionar todos los contenedores de valoracion */
            const allContainers = document.querySelectorAll('.rating-simple');
            console.log('Configurando eventos para', allContainers.length, 'productos');
            
            allContainers.forEach(container => {
                const stars = container.querySelectorAll('.star-simple');
                
                stars.forEach(star => {
                    /* Eliminar eventos anteriores clonando la estrella */
                    star.replaceWith(star.cloneNode(true));
                });
                
                /* Volver a seleccionar las estrellas despues del clone */
                const newStars = container.querySelectorAll('.star-simple');
                
                newStars.forEach(star => {
                    /* Evento click para valorar 𐙚 */
                    star.addEventListener('click', (e) => {
                        this.handleStarClick(e, container);
                    });
                    
                    /* Evento hover para previsualizacion 𝜗𝜚 */
                    star.addEventListener('mouseover', (e) => {
                        this.handleStarHover(e, container, true);
                    });
                    
                    star.addEventListener('mouseout', (e) => {
                        this.handleStarHover(e, container, false);
                    });
                });
            });
        },
        
        /* Manejar click en estrella */
        handleStarClick: function(event, container) {
            const star = event.currentTarget;
            const productId = container.dataset.productId;
            const ratingValue = parseInt(star.dataset.value);
            
            console.log(`Valorando producto ${productId} con ${ratingValue} estrellas 𐙚`);
            
            /* Guardar la valoracion en el sistema */
            this.saveRating(productId, ratingValue);
            
            /* Actualizar visualizacion del producto */
            this.updateDisplay(productId);
            
            /* Mostrar mensaje de feedback al usuario */
            this.showFeedback(container, `Valorado con ${ratingValue} estrellas 𝜗𝜚`);
        },
        
        /* Manejar hover sobre las estrellas */
        handleStarHover: function(event, container, isHovering) {
            const star = event.currentTarget;
            const ratingValue = parseInt(star.dataset.value);
            const stars = container.querySelectorAll('.star-simple');
            
            if (isHovering) {
                /* Mostrar previsualizacion de la valoracion */
                stars.forEach((s, index) => {
                    s.classList.remove('hover-preview');
                    if (index < ratingValue) {
                        s.classList.add('hover-preview');
                    }
                });
            } else {
                /* Quitar previsualizacion y restaurar valoracion actual */
                stars.forEach(s => {
                    s.classList.remove('hover-preview');
                });
                this.updateDisplay(container.dataset.productId);
            }
        },
        
        /* Guardar una valoracion de producto */
        saveRating: function(productId, rating) {
            /* Obtener datos actuales o crear nuevo registro */
            let productData = this.ratings.get(productId);
            
            if (!productData) {
                /* Primera valoracion del producto */
                productData = {
                    total: rating,
                    count: 1,
                    average: rating,
                    ratings: [rating]
                };
            } else {
                /* Actualizar datos existentes */
                productData.total += rating;
                productData.count += 1;
                productData.average = productData.total / productData.count;
                productData.ratings.push(rating);
                
                /* Mantener solo ultimas 50 valoraciones */
                if (productData.ratings.length > 50) {
                    productData.ratings.shift();
                }
            }
            
            /* Guardar datos en el sistema y en localStorage */
            this.ratings.set(productId, productData);
            this.saveRatings();
        },
        
        /* Actualizar visualizaciones de todos los productos */
        updateAllDisplays: function() {
            document.querySelectorAll('.rating-simple').forEach(container => {
                const productId = container.dataset.productId;
                this.updateDisplay(productId);
            });
        },
        
        /* Actualizar visualizacion de un producto */
        updateDisplay: function(productId) {
            /* Seleccionar todos los contenedores de este producto */
            const containers = document.querySelectorAll(`.rating-simple[data-product-id="${productId}"]`);
            if (containers.length === 0) return;
            
            const productData = this.ratings.get(productId);
            
            containers.forEach(container => {
                const averageElement = container.querySelector('.rating-average-simple');
                const countElement = container.querySelector('.rating-count-simple');
                const stars = container.querySelectorAll('.star-simple');
                
                if (productData) {
                    /* Actualizar promedio de valoracion */
                    if (averageElement) {
                        averageElement.textContent = productData.average.toFixed(1);
                        averageElement.className = 'rating-average-simple';
                        
                        /* Asignar color segun promedio */
                        if (productData.average >= 4.5) {
                            averageElement.classList.add('rating-excellent');
                        } else if (productData.average >= 3.5) {
                            averageElement.classList.add('rating-good');
                        } else if (productData.average >= 2.5) {
                            averageElement.classList.add('rating-average');
                        } else {
                            averageElement.classList.add('rating-poor');
                        }
                    }
                    
                    /* Actualizar contador de valoraciones */
                    if (countElement) {
                        countElement.textContent = `(${productData.count})`;
                    }
                    
                    /* Actualizar estrellas segun promedio */
                    const fullStars = Math.floor(productData.average);
                    const hasHalfStar = (productData.average % 1) >= 0.3;
                    
                    stars.forEach((star, index) => {
                        const starValue = index + 1;
                        star.classList.remove('active', 'half');
                        
                        if (starValue <= fullStars) {
                            star.classList.add('active');
                        } else if (starValue === fullStars + 1 && hasHalfStar) {
                            star.classList.add('half');
                        }
                    });
                    
                } else {
                    /* Sin datos - valores por defecto */
                    if (averageElement) {
                        averageElement.textContent = '0.0';
                        averageElement.className = 'rating-average-simple';
                    }
                    
                    if (countElement) {
                        countElement.textContent = '(0)';
                    }
                    
                    /* Resetear estrellas */
                    stars.forEach(star => {
                        star.classList.remove('active', 'half', 'hover-preview');
                    });
                }
            });
        },
        
        /* Mostrar mensaje de feedback al usuario */
        showFeedback: function(container, message) {
            const feedback = document.createElement('div');
            feedback.className = 'rating-feedback';
            feedback.textContent = message;
            
            /* Posicionar feedback sobre el contenedor */
            container.style.position = 'relative';
            container.appendChild(feedback);
            
            /* Eliminar mensaje despues de 2 segundos */
            setTimeout(() => {
                if (feedback.parentNode) {
                    feedback.parentNode.removeChild(feedback);
                }
            }, 2000);
        },
        
        /* Generar datos iniciales de demostracion si no hay valoraciones */
        generateInitialData: function() {
            if (this.ratings.size > 0) return;
            
            console.log('Generando datos de demostracion 𝜗𝜚...');
            
            document.querySelectorAll('.rating-simple').forEach(container => {
                const productId = container.dataset.productId;
                const productElement = container.closest('.producto');
                const productName = productElement ? productElement.querySelector('h3')?.textContent || '' : '';
                
                let baseRating, baseCount;
                
                if (productName.includes('Premium') || productElement?.querySelector('.badge-premium')) {
                    baseRating = 4.5 + (Math.random() * 0.4 - 0.2)
                    baseCount = Math.floor(Math.random() * 30) + 20
                } else if (productName.includes('Oferta') || productElement?.querySelector('.badge-oferta')) {
                    baseRating = 4.2 + (Math.random() * 0.4 - 0.2)
                    baseCount = Math.floor(Math.random() * 40) + 10
                } else {
                    baseRating = 3.8 + (Math.random() * 0.6 - 0.3)
                    baseCount = Math.floor(Math.random() * 25) + 5
                }
                
                baseRating = Math.max(1, Math.min(5, baseRating))
                
                this.ratings.set(productId, {
                    total: baseRating * baseCount,
                    count: baseCount,
                    average: baseRating,
                    ratings: Array(baseCount).fill().map(() => 
                        Math.max(1, Math.min(5, baseRating + (Math.random() - 0.5)))
                    )
                });
            });
            
            /* Guardar y actualizar visualizaciones */
            this.saveRatings();
            this.updateAllDisplays();
            
            console.log('Datos de demostracion generados para', this.ratings.size, 'productos 𐙚');
        },
        
        /* Metodo de depuracion para consola */
        debug: function() {
            console.log('=== DEBUG RATING SYSTEM ===');
            console.log('Productos totales:', this.countProducts());
            console.log('Valoraciones guardadas:', this.ratings.size);
            console.log('=== FIN DEBUG ===');
        }
    };
    
    /* Inicializar sistema */
    ratingSystem.init();
    
    /* Hacer sistema disponible globalmente */
    window.ratingSystem = ratingSystem;
    
    /* Ejecutar debug en consola despues de 1 segundo */
    setTimeout(() => ratingSystem.debug(), 1000);
    
    /* Crear estilos CSS dinamicamente */
    const style = document.createElement('style');
    style.textContent = `
        .star-simple.hover-preview {
            color: #ffc107 !important;
            transform: scale(1.1);
        }
        
        .rating-feedback {
            position: absolute;
            top: -35px;
            left: 50%;
            transform: translateX(-50%);
            background: #28a745;
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
            z-index: 100;
            animation: ratingFeedback 2s forwards;
            box-shadow: 0 3px 10px rgba(0,0,0,0.2);
        }
        
        @keyframes ratingFeedback {
            0% { opacity: 0; transform: translateX(-50%) translateY(10px); }
            20% { opacity: 1; transform: translateX(-50%) translateY(0); }
            80% { opacity: 1; transform: translateX(-50%) translateY(0); }
            100% { opacity: 0; transform: translateX(-50%) translateY(-10px); }
        }
        
        .rating-excellent { color: #28a745 !important; }
        .rating-good { color: #17a2b8 !important; }
        .rating-average { color: #ffc107 !important; }
        .rating-poor { color: #dc3545 !important; }
    `;
    document.head.appendChild(style);
});
