document.getElementById("form").addEventListener("submit", function(e) {
    e.preventDefault(); // Evitar que el formulario se envíe automáticamente
    this.submit(); // Enviar el formulario
  });
  
  document.getElementById("desde").addEventListener("input", function() {
    var fechaInicial = new Date(this.value); // Obtener la fecha inicial seleccionada
  
    if (!isNaN(fechaInicial)) {
      var fechaMaxima = new Date(fechaInicial);
      fechaMaxima.setDate(fechaMaxima.getDate() + 4); // Agregar 4 días a la fecha inicial
  
      // Formatear la fecha máxima en formato YYYY-MM-DD
      var yyyy = fechaMaxima.getFullYear();
      var mm = String(fechaMaxima.getMonth() + 1).padStart(2, "0");
      var dd = String(fechaMaxima.getDate()).padStart(2, "0");
      var fechaMaximaFormatted = yyyy + "-" + mm + "-" + dd;
  
      // Establecer las propiedades 'min' y 'max' del campo de fecha
      this.setAttribute("min", this.value);
      this.setAttribute("max", fechaMaximaFormatted);
    }
  });