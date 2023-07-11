<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Función para actualizar la actividad de la sesión mediante una petición AJAX
        function actualizarActividad() {
            $.ajax({
                url: 'ActualizarActividad.php', // Ruta al archivo PHP que actualizará la actividad
                type: 'POST',
                data: { actualizacion: true },
                success: function(response) {
                    console.log('Actividad de sesión actualizada');
                },
                error: function(xhr, status, error) {
                    console.error('Error al actualizar la actividad de sesión:', error);
                }
            });
        }

        // Ejecutar la función de actualización de actividad cada 5 minutos (ajusta este tiempo según tus necesidades)
        setInterval(actualizarActividad, 5 * 60 * 1000); // 5 minutos en milisegundos
    });
</script>