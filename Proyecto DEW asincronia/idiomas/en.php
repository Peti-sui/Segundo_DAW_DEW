<?php

/* Archivo de configuracion de textos multilenguaje utilizado en toda la aplicacion para mostrar mensajes etiquetas y titulos dinamicos segun el idioma seleccionado */
return [

    /* Seccion general con textos comunes utilizados en toda la aplicacion como titulos mensajes de bienvenida y descripciones generales */
    'tienda_titulo' => 'Keychain Store',
    'bienvenido_admin' => 'Welcome administrator',
    'bienvenido_usuario' => 'Welcome',
    'bienvenido' => 'Welcome to our store',
    'descripcion_tienda' => 'Specialized store in keychains and customized packages',

    /* Textos de navegacion para enlaces de retorno entre distintas vistas del sistema */
    'volver_tienda' => 'Back to store',
    'volver_login' => 'Back to login',
    'volver_carrito' => 'Back to cart',
    'volver_admin' => 'Back to Panel',
    'volver_listado' => 'Back to list',
    'volver_pedidos' => 'Back to Orders',
    'volver_pendiente' => 'Back to Pending',

    /* Etiquetas para acciones comunes del sistema como guardar editar eliminar o confirmar */
    'guardar' => 'Save',
    'editar' => 'Edit',
    'eliminar' => 'Delete',
    'actualizar' => 'Update',
    'cancelar' => 'Cancel',
    'aceptar' => 'Accept',
    'confirmar' => 'Confirm',
    'ver' => 'View',

    /* Opciones principales del menu de navegacion de la tienda */
    'ver_carrito' => 'View cart',
    'panel_admin' => 'Admin panel',
    'preferencias' => 'Preferences',
    'iniciar_sesion' => 'Login',
    'salir' => 'Logout',

    /* Textos relacionados con la gestion y visualizacion de productos */
    'productos' => 'Products',
    'producto' => 'Product',
    'nombre' => 'Name',
    'precio' => 'Price',
    'precio_unitario' => 'Unit price',
    'cantidad' => 'Quantity',
    'subtotal' => 'Subtotal',
    'total' => 'Total',
    'tipo' => 'Type',
    'imagen' => 'Image',
    'acciones' => 'Actions',
    'anadir_carrito' => 'Add to cart',
    'nuevo_producto' => 'New product',
    'editar_producto' => 'Edit product',
    'sin_imagen' => 'No image',
    'imagen_actual' => 'Current image',
    'nueva_imagen' => 'New image',

    /* Tipos de productos definidos en el sistema para clasificar los articulos */
    'llaves' => 'Keys',
    'bolso' => 'Bag',
    'mochila' => 'Backpack',

    /* Mensajes y etiquetas para productos en oferta y precios especiales */
    'oferta' => 'OFFER',
    'oferta_especial' => 'Special Offer',
    'producto_premium' => 'Premium Product',
    'precio_efectivo' => 'effective price',
    'precio_fijo_desc' => 'Fixed price regardless of quantity',
    'precio_doble_desc' => 'Double price per unit',

    /* Textos relacionados con el carrito de compra y su gestion */
    'carrito' => 'Cart',
    'carrito_vacio' => 'Your cart is empty',
    'carrito_vacio_pago' => 'Cart is empty',
    'vaciar_carrito' => 'Empty cart',
    'seguir_comprando' => 'Continue shopping',
    'resumen_carrito' => 'Cart Summary',
    'proceder_pago' => 'Proceed to checkout',

    /* Mensajes de control para evitar duplicados en el carrito */
    'producto_ya_en_carrito' => 'This product is already in your cart',
    'producto_ya_en_carrito_alerta' => 'This product is already in your cart. Go to cart to modify the quantity.',
    'ir_carrito_modificar' => 'Go to cart to modify the quantity',
    'producto_añadido' => 'Product added to cart',
    'ya_en_carrito' => 'Already in cart',

    /* Instrucciones visuales para el uso correcto del carrito */
    'instrucciones_carrito' => 'Instructions:',
    'instruccion_1' => 'Each product can only be added once to the cart',
    'instruccion_2' => 'To change the quantity, go to the cart',
    'instruccion_3' => 'Use the + and - buttons in the cart to modify',

    /* Textos del sistema de preferencias del usuario */
    'preferencias_titulo' => 'User Preferences',
    'configuracion_actual' => 'Current settings',
    'idioma' => 'Language',
    'tema' => 'Theme',
    'espanol' => 'Spanish',
    'ingles' => 'English',
    'claro' => 'Light',
    'oscuro' => 'Dark',
    'guardar_preferencias' => 'Save Preferences',

    /* Etiquetas para autenticacion registro y acceso de usuarios */
    'usuario' => 'Username',
    'password' => 'Password',
    'entrar' => 'Login',
    'registrarse' => 'Register',
    'crear_cuenta' => 'Create account',
    'registro_titulo' => 'Registration',

    /* Mensajes de validacion y control de errores del sistema */
    'error' => 'Error',
    'exito' => 'Success',

    'error_acceso_denegado' => 'Access denied',
    'error_producto_no_encontrado' => 'Product not found',
    'error_eliminar_producto' => 'Delete product?',
    'error_todos_campos' => 'All fields are required',
    'error_usuario_existe' => 'User already exists',
    'error_usuario_no_permitido' => 'That username is not allowed',
    'error_usuario_no_registrado' => 'User not registered',
    'error_contrasena_incorrecta' => 'Incorrect password',

    'error_nombre_vacio' => 'Name cannot be empty',
    'error_precio_invalido' => 'Price must be greater than 0',
    'error_nombre_tipo_duplicado' => 'A product with this name and type already exists',
    'error_guardar_producto' => 'Error saving product',
    'error_actualizar_producto' => 'Error updating product',

    /* Mensajes de confirmacion tras operaciones exitosas */
    'exito_producto_creado' => 'Product created successfully',
    'exito_producto_actualizado' => 'Product updated successfully',

    /* Textos de ayuda y validacion para formularios */
    'ejemplo_nombre' => 'Ex: Star keychain',
    'seleccionar_tipo' => 'Select type',
    'regla_nombre_tipo' => 'Cannot have two products with the same name and type',
    'regla_tipo_unico' => 'You can use the same name if the type is different',
    'regla_tipo_seleccionado' => 'Selected type',

    /* Informacion para gestion de imagenes de productos */
    'formato_imagen' => 'Accepted formats: JPG, PNG, GIF. Max size: 2MB',
    'mantener_imagen' => 'Leave empty to keep current image',
    'producto_duplicado' => 'A product with this name and type already exists',

    /* Textos del panel de administracion */
    'panel_titulo' => 'Administration Panel',
    'no_productos' => 'No products registered',

    /* Textos relacionados con pedidos pagos y estados */
    'pedido' => 'Order',
    'mis_pedidos' => 'My Orders',
    'no_pedidos' => 'You have no orders',
    'hacer_primer_pedido' => 'Make my first order',

    'confirmacion_pago' => 'Payment Confirmation',
    'pago_exitoso' => 'Payment Successful!',
    'proceso_fallido' => 'Payment process failed',

    'resumen_pedido' => 'Order Summary',
    'fecha' => 'Date',
    'estado' => 'Status',
    'detalles_pedido' => 'Order Details',
    'detalle_pedido' => 'Order Detail',
    'detalle_pedido_admin' => 'Order Detail (Admin)',
    'informacion_pedido' => 'Order Information',
    'productos_pedido' => 'Order Products',
    'total_productos' => 'Total Products',

    'ver_detalles' => 'View Details',
    'ver_mis_pedidos' => 'View my orders',

    'pedido_no_encontrado' => 'Order not found',

    'gestion_pedidos' => 'Order Management',
    'estado_actualizado' => 'Order status updated',
    'no_hay_pedidos' => 'No orders registered',

    'procesar' => 'Process',
    'completar' => 'Complete',

    'confirmar_procesar' => 'Mark this order as processed?',
    'confirmar_cancelar' => 'Cancel this order?',
    'confirmar_completar' => 'Mark this order as completed?',

    'marcar_procesado' => 'Mark as Processed',
    'cancelar_pedido' => 'Cancel Order',
    'marcar_completado' => 'Mark as Completed',

    'estado_pendiente' => 'Status: Pending',
    'espera_confirmacion' => 'Waiting for administrator confirmation',
    'pedido_completado' => 'Order Completed',
    'pedido_cancelado' => 'Order Cancelled',
    'no_acciones_disponibles' => 'No actions available for this status',

    /* Sistema de lista de deseos del usuario */
    'lista_deseos' => 'Wishlist',
    'anadir_deseos' => 'Add to Wishlist',
    'ya_en_deseos' => 'In Wishlist',
    'producto_ya_en_deseos' => 'This product is already in your wishlist',
    'producto_ya_en_deseos_alerta' => 'This product is already in your wishlist',
    'producto_añadido_deseos' => 'Product added to wishlist',

    'deseos_vacio' => 'Your wishlist is empty',
    'agrega_productos_deseos' => 'Add products you like to see them here',

    'mover_carrito' => 'Move to Cart',
    'mover_carrito_success' => 'Product moved to cart',
    'error_mover' => 'Error moving to cart',

    'eliminar_deseos_confirm' => 'Remove from wishlist?',
    'eliminado_success' => 'Product removed from wishlist',

    'vaciar_deseos' => 'Clear Wishlist',
    'vaciar_deseos_confirm' => 'Clear entire wishlist?',
    'vaciado_success' => 'Wishlist cleared',

    'agregado_el' => 'Added on',
    'productos_destacados' => 'Featured Products',
    'anterior' => 'Previous',
    'siguiente' => 'Next',

    /* Sistema de valoraciones de productos por parte de los usuarios */
    'valoracion' => 'Rating',
    'valoraciones' => 'Ratings',

    'valorar_producto' => 'Rate product',
    'estrellas' => 'stars',
    'promedio' => 'Average',
    'tu_valoracion' => 'Your rating',
    'valoracion_enviada' => 'Rating submitted',
    'valoracion_error' => 'Error submitting rating',
];
